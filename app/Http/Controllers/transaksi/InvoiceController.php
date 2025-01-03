<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\formPo;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\TransaksiDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // Invoice ready stock 
    public function index(Request $request)
    {
        $title = "Kelola Invoice";

        if ($request->ajax()) {
            // Ambil data invoice detail dengan relasi yang dibutuhkan
            $data = InvoiceDetail::with([
                'invoice',
                'transaksiDetail.transaksi.customerOrder.draftCustomer',
                'transaksiDetail.stok',
            ])
                ->whereHas('transaksiDetail.transaksi.customerOrder.draftCustomer', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->get();

            // Kelompokkan data berdasarkan nama customer
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
                    // Gabungkan nama produk dari setiap invoice_detail
                    $produkNames = $row->invoice
                        ->invoiceDetails
                        ->map(function ($detail) {
                            return $detail->transaksiDetail->stok->nama_produk ?? '-';
                        })
                        ->toArray();

                    return implode(',<br>', $produkNames);
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
                    $status = ucfirst($row->invoice->status_pembayaran ?? 'N/A');

                    // Menentukan badge berdasarkan status pembayaran
                    if ($status === 'Lunas') {
                        return '<span class="badge bg-primary text-white">' . $status . '</span>';
                    } elseif ($status === 'Belum lunas') {
                        return '<span class="badge bg-danger text-white">' . $status . '</span>';
                    } else {
                        return '<span class="badge bg-secondary text-white">' . $status . '</span>';
                    }
                })
                ->addColumn('tenggat_invoice', function ($row) {
                    return $row->invoice->tenggat_invoice
                        ? \Carbon\Carbon::parse($row->invoice->tenggat_invoice)->format('d F Y')
                        : '-';
                })
                ->addColumn('actions', function ($row) {
                    return view('components.button.inv-actionbtn', [
                        'edit' => route('kelola-invoice.edit', $row->invoice_detail_id),
                        'delete' => route('kelola-invoice.destroy', $row->invoice_detail_id),
                        'show' => route('kelola-invoice.show', $row->invoice->invoice_id)
                    ])->render();
                })
                ->rawColumns(['actions', 'status_pembayaran', 'nama_produk'])
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

        // pengambilna data invoice ready stock belum sesuai user login
        $customers = TransaksiDetail::with([
            'transaksi.customerOrder.draftCustomer',
            'stok',
            'invoiceDetails'
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

                $invoiceDetails = $firstItem->invoiceDetails;

                if ($invoiceDetails->isEmpty()) { 
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
                }

                return null;
            })
            ->filter()
            ->values();
        return view('transaksi.invoice.ready_stok.create', compact('backUrl', 'title', 'customers'));
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
            'status_pembayaran' => $statusPembayaran,
            'harga_satuan' => $subtotal / $totalQty,
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
    public function show($invoice_id)
    {
        $invoice = Invoice::with([
            'invoiceDetails.transaksiDetail.stok',
            'invoiceDetails.transaksiDetail.transaksi.customerOrder.draftCustomer',
        ])->findOrFail($invoice_id);

        $invoiceDetails = $invoice->invoiceDetails;

        $title = 'Detail Invoice';
        $backUrl = url()->previous();

        return view('transaksi.invoice.ready_stok.show', compact('invoice', 'invoiceDetails', 'title', 'backUrl'));
    }

    public function downloadPdf($invoice_id)
    {
        $invoice = Invoice::with([
            'invoiceDetails.transaksiDetail.stok',
            'invoiceDetails.transaksiDetail.transaksi.customerOrder.draftCustomer',
        ])->findOrFail($invoice_id);

        $invoiceDetails = $invoice->invoiceDetails;

        // Sanitize the nota_no for filename
        $nota_no = $invoice->nota_no ?? 'DefaultNota';
        $nota_no = preg_replace('/[\/\\\]/', '_', $nota_no);
        $filename = 'Invoice-' . $nota_no . '.pdf';

        $pdf = Pdf::loadView('transaksi.invoice.ready_stok.pdf', compact('invoice', 'invoiceDetails'));
        $pdf->setPaper('b5', 'portrait');
        return $pdf->download($filename);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        $title = 'Edit Invoice';
        return view('transaksi.invoice.ready_stok.edit', compact('invoice', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return response()->json(['message' => 'Invoice berhasil dihapus']);
    }
}
