<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = "Kelola Invoice";

        if ($request->ajax()) {
            $data = InvoiceDetail::with([
                'invoice',
                'transaksiDetail.transaksi.customerOrder.draftCustomer',
                'transaksiDetail.stok',  // Mengambil stok produk yang terkait
            ])
                ->whereHas('transaksiDetail.transaksi.customerOrder.draftCustomer', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->get();

            $groupedData = $data->groupBy(function ($item) {
                return $item->transaksiDetail->transaksi->customerOrder->draftCustomer->Nama ?? '-';
            });

            // Ambil hanya data pertama dari setiap grup
            $uniqueData = $groupedData->map(function ($items) {
                return $items->first();
            });

            return DataTables::of($uniqueData)
                ->addColumn('customer_name', function ($row) {
                    return $row->transaksiDetail->transaksi->customerOrder->draftCustomer->Nama ?? '-';
                })
                ->addColumn('nota_no', function ($row) {
                    return $row->invoice->nota_no ?? 'N/A';
                })
                ->addColumn('nama_produk', function ($row) {
                    $produkNames = InvoiceDetail::where('invoice_id', $row->invoice_id)
                        ->get()
                        ->map(function ($invoiceDetail) {
                            return $invoiceDetail->transaksiDetail->stok->nama_produk ?? '-';
                        })
                        ->toArray();

                    // Gabungkan nama produk dengan koma
                    return implode(', ', $produkNames);
                })
                ->addColumn('subtotal', function ($row) {
                    return number_format($row->invoice->subtotal ?? 0, 0, ',', '.');
                })
                ->addColumn('ongkir', function ($row) {
                    return number_format($row->invoice->ongkir ?? 0, 0, ',', '.');
                })
                ->addColumn('total', function ($row) {
                    return number_format($row->invoice->total ?? 0, 0, ',', '.');
                })
                ->addColumn('dp', function ($row) {
                    return number_format($row->invoice->down_payment ?? 0, 0, ',', '.');
                })
                ->addColumn('status_pembayaran', function ($row) {
                    return ucfirst($row->invoice->status_pembayaran ?? 'N/A');
                })
                ->addColumn('tenggat_invoice', function ($row) {
                    return $row->invoice->tenggat_invoice
                        ? \Carbon\Carbon::parse($row->invoice->tenggat_invoice)->format('d-m-Y')
                        : '-';
                })
                ->addColumn('actions', function ($row) {
                    return view('components.button.inv-actionbtn', [
                        'show' => route('kelola-invoice.show', $row->invoice_detail_id),
                        'edit' => route('kelola-invoice.edit', $row->invoice_detail_id),
                        'delete' => route('kelola-invoice.destroy', $row->invoice_detail_id),
                    ])->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('transaksi.invoice.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $backUrl = url()->previous();
        $title = 'Tambah Invoice';

        // $formPo = FormPO::with(['customerOrder.draftCustomer', 'products'])->get();
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
                    return null;
                }

                return [
                    'id' => $draftCustomer->draft_customers_id ?? null,
                    'nama' => $draftCustomer->Nama ?? 'Unnamed Customer',
                    'produk' => $group->map(function ($item) {
                        $stok = optional($item->stok);
                        return [
                            'transaksi_detail_id' => optional($item)->id_transaksi_detail ?? null,
                            'nama_produk' => $stok->nama_produk ?? 'Unknown Product',
                            'qty' => $item->qty ?? 0,
                            'harga_satuan' => $item->harga_satuan ?? 0,
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
        return view('transaksi.invoice.create', compact('backUrl', 'title', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Validasi data
        $validatedData = $request->validate([
            'nota_no' => 'required|string',
            'tenggat_invoice' => 'required|date',
            'nama_pelanggan' => 'required|string',
            'Nama_Barang' => 'required|array',
            'Nama_Barang.*' => 'required|string',
            'qty' => 'required|array',
            'qty.*' => 'required|integer|min:1',
            'harga' => 'required|array',
            'harga.*' => 'required|numeric|min:0',
            'ongkir' => 'required|numeric|min:0',
            'dp' => 'required|numeric|min:0|max:100',
            'transaksi_detail_id' => 'required|array',
            'transaksi_detail_id.*' => 'required|exists:tb_transaksi_detail,id_transaksi_detail',
        ]);

        $subtotal = 0;
        $totalQty = 0;

        // Hitung subtotal dan total jumlah barang
        foreach ($validatedData['qty'] as $index => $qty) {
            $harga = $validatedData['harga'][$index];
            $subtotal += $qty * $harga;
            $totalQty += $qty;
        }

        $total = $subtotal + $validatedData['ongkir'];
        $downPayment = ($validatedData['dp'] / 100) * $total;

        $statusPembayaran = $downPayment >= $total ? 'Lunas' : 'Belum Lunas';


        // Simpan data ke tabel tb_invoice
        $invoice = Invoice::create([
            'nota_no' => $validatedData['nota_no'],
            'form_po_id' => null, // Sesuaikan jika diperlukan
            'status_pembayaran' => $statusPembayaran,
            'harga_satuan' => $subtotal / $totalQty, // Rata-rata harga satuan
            'subtotal' => $subtotal,
            'jumlah' => $totalQty,
            'ongkir' => $validatedData['ongkir'],
            'down_payment' => $downPayment,
            'total' => $total,
            'tenggat_invoice' => $validatedData['tenggat_invoice'],
        ]);

        // Simpan detail barang ke tabel tb_invoice_detail
        foreach ($validatedData['transaksi_detail_id'] as $transaksiDetailId) {
            InvoiceDetail::create([
                'invoice_id' => $invoice->invoice_id,
                'transaksi_detail_id' => $transaksiDetailId,
            ]);
        }

        return redirect()->route('kelola-invoice.index')->with('success', 'Invoice berhasil dibuat');
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
