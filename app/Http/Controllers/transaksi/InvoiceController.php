<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use App\Models\FormPO;
use App\Models\Invoice;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Kelola Invoice';
        return view('transaksi.invoice.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $backUrl = url()->previous();
        $title = 'Tambah Invoice';

        $formPo = FormPO::with(['customerOrder.draftCustomer', 'products'])->get();
        $customers = TransaksiDetail::with([
            'transaksi.customerOrder.draftCustomer',
            'stok'
        ])
            ->get()
            ->groupBy(function ($item) {
                return $item->transaksi->customerOrder->draftCustomer->draft_customers_id;
            })
            ->map(function ($group) {
                $firstItem = $group->first();

                // Add null checks to prevent potential errors
                $draftCustomer = optional($firstItem->transaksi->customerOrder)->draftCustomer;
                $transaksi = $firstItem->transaksi;

                if (!$draftCustomer) {
                    return null; // Skip this group if no draft customer
                }

                return [
                    'id' => $draftCustomer->draft_customers_id ?? null,
                    'nama' => $draftCustomer->Nama ?? 'Unnamed Customer',
                    'produk' => $group->map(function ($item) {
                        return [
                            'nama_produk' => optional($item->stok)->nama_produk ?? 'Unknown Product',
                            'qty' => $item->qty ?? 0,  // Changed from 'jumlah' to 'qty'
                            'harga_satuan' => $item->harga_satuan ?? 0,  // Changed from 'harga' to 'harga_satuan'
                            'subtotal' => $item->subtotal ?? 0,
                            'tanggal_keluar' => $item->tanggal_keluar ?? null
                        ];
                    })->filter()->values()->toArray(),
                    'metode_pembayaran' => $transaksi->metode_pembayaran ?? null,
                    'diskon_produk' => $transaksi->diskon_produk ?? 0,
                    'diskon_ongkir' => $transaksi->diskon_ongkir ?? 0,
                    'ekspedisi' => $transaksi->ekspedisi ?? null
                ];
            })
            ->filter()
            ->values();
        return view('transaksi.invoice.create', compact('backUrl', 'title', 'formPo', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nota_no' => 'required|string', // nota_no already set in the form as hidden
            'tanggal' => 'required|date', // Ensure tanggal is a valid date
            'nama_pelanggan' => 'required|exists:customers,id', // Validates if customer exists in the 'customers' table
            'Nama_Barang' => 'required|array', // Nama_Barang should be an array (multiple items possible)
            'Nama_Barang.*' => 'required|string', // Each Nama_Barang should be a string
            'qty' => 'required|array', // qty should be an array
            'qty.*' => 'required|numeric|min:1', // Each qty should be numeric and >= 1
            'harga' => 'required|array', // harga should be an array
            'harga.*' => 'required|numeric|min:0', // Each harga should be numeric and >= 0
            'ongkir' => 'required|numeric|min:0', // ongkir (shipping cost)
            'dp' => 'required|numeric|min:0', // dp (down payment)
        ]);

        // Generate nota_no in the format: INV-YYYYMMDD-00001
        $nota_no = 'INV-' . date('Ymd') . '-' . str_pad(Invoice::count() + 1, 5, '0', STR_PAD_LEFT);
        dd($request->all());

        try {
            // Begin database transaction to ensure atomic operations
            DB::beginTransaction();

            // Prepare invoice data from the validated input
            $invoiceData = [
                'nota_no' => $nota_no,
                'form_po_id' => $request->form_po_id, // Assuming you have a form_po_id field in your form
                'transaksi_detail_id' => $request->transaksi_detail_id, // Similarly, transaksi_detail_id
                'status_pembayaran' => $request->status_pembayaran,
                'harga_satuan' => $request->harga_satuan,
                'subtotal' => $request->subtotal,
                'jumlah' => $request->jumlah,
                'ongkir' => $request->ongkir,
                'down_payment' => $request->dp, // You may want to rename 'dp' to 'down_payment' for consistency
                'total' => $request->total,
                'tenggat_invoice' => $request->tenggat_invoice,
            ];

            // Create the invoice record in the database
            Invoice::create($invoiceData);

            // Commit the transaction if everything is successful
            DB::commit();

            // Redirect to the invoice list with success message
            return redirect()->route('invoice.index')->with('success', 'Invoice berhasil dibuat');
        } catch (\Exception $e) {
            // Rollback if there is any error
            DB::rollBack();
            Log::error('Error creating invoice: ' . $e->getMessage());

            // Redirect back with error message
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat invoice. Silakan coba lagi.');
        }
    }

    /**
     * Proses Form PO untuk membuat invoice.
     */
    private function processFormPO(Request $request)
    {
        // Tambahkan logika khusus untuk Form PO
        Invoice::create([
            'form_po_id' => $request->input('form_po_id'),
            'transaksi_detail_id' => null,
            'status_pembayaran' => 'belum lunas',
            'harga_satuan' => $request->input('harga_satuan'),
            'jumlah' => $request->input('jumlah'),
            'subtotal' => $request->input('harga_satuan') * $request->input('jumlah'),
            'ongkir' => $request->input('ongkir', 0),
            'down_payment' => $request->input('down_payment', 0),
            'total' => $this->calculateTotal($request),
            'terbit_invoice' => now(),
            'tenggat' => now()->addDays(7),
        ]);
    }

    /**
     * Proses Transaksi Detail untuk membuat invoice.
     */
    private function processTransaksiDetail(Request $request)
    {
        $this->markDone($request->input('transaksi_detail_id'));

        Invoice::create([
            'form_po_id' => null,
            'transaksi_detail_id' => $request->input('transaksi_detail_id'),
            'status_pembayaran' => 'belum lunas',
            'harga_satuan' => $request->input('harga_satuan'),
            'jumlah' => $request->input('jumlah'),
            'subtotal' => $request->input('harga_satuan') * $request->input('jumlah'),
            'ongkir' => $request->input('ongkir', 0),
            'down_payment' => $request->input('down_payment', 0),
            'total' => $this->calculateTotal($request),
            'terbit_invoice' => now(),
            'tenggat' => now()->addDays(7),
        ]);
    }

    /**
     * Menghitung total.
     */
    private function calculateTotal(Request $request)
    {
        $subtotal = $request->input('harga_satuan') * $request->input('jumlah');
        $ongkir = $request->input('ongkir', 0);
        $downPayment = $request->input('down_payment', 0);

        return $subtotal + $ongkir - $downPayment;
    }

    /**
     * Menandai transaksi detail sebagai done.
     */
    private function markDone($transaksiDetailId)
    {
        $transaksi = TransaksiDetail::find($transaksiDetailId);
        if ($transaksi) {
            $transaksi->status = 'done';
            $transaksi->save();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $title = 'Detail Invoice';
        return view('transaksi.invoice.show', compact('invoice', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        $title = 'Edit Invoice';
        return view('transaksi.invoice.edit', compact('invoice', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:cash,cashless',
            'harga_satuan' => 'required|numeric',
            'jumlah' => 'required|numeric',
            'ongkir' => 'nullable|numeric',
            'down_payment' => 'nullable|numeric',
        ]);

        $invoice->update([
            'status_pembayaran' => $request->input('status_pembayaran'),
            'harga_satuan' => $request->input('harga_satuan'),
            'jumlah' => $request->input('jumlah'),
            'subtotal' => $request->input('harga_satuan') * $request->input('jumlah'),
            'ongkir' => $request->input('ongkir', 0),
            'down_payment' => $request->input('down_payment', 0),
            'total' => $this->calculateTotal($request),
        ]);

        return response()->json(['message' => 'Invoice berhasil diperbarui']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return response()->json(['message' => 'Invoice berhasil dihapus']);
    }
}
