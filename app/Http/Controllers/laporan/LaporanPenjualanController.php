<?php

namespace App\Http\Controllers\laporan;

use App\Http\Controllers\Controller;
use App\Models\InvoiceDetail;
use App\Models\transaksiDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class LaporanPenjualanController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');

            $query = transaksiDetail::with('transaksi', 'stok')
                ->whereBetween('tanggal_keluar', [$start_date, $end_date])
                ->whereHas('transaksi.customerOrder.draftCustomer', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->get();

            return DataTables::of($query)
                ->addColumn('diskon_produk', function ($row) {
                    return $row->transaksi->diskon_produk ?? '-';
                })
                ->addColumn('nama_produk', function ($row) {
                    return $row->stok->nama_produk ?? '-';
                })
                ->addColumn('diskon_ongkir', function ($row) {
                    return $row->transaksi->diskon_ongkir ?? '-';
                })
                ->addColumn('ekspedisi', function ($row) {
                    return $row->transaksi->ekspedisi ?? '-';
                })
                ->addColumn('metode_pembayaran', function ($row) {
                    return $row->transaksi->metode_pembayaran ?? '-';
                })
                ->addColumn('customer', function ($row) {
                    return $row->transaksi->customerOrder->draftCustomer->Nama ?? '-';
                })
                ->make(true);
        }
        return view('laporan.penjualan.index');
    }

    // public function getLaporanPenjualan(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $start_date = $request->input('start_date');
    //         $end_date = $request->input('end_date');

    //         // Query data berdasarkan tanggal dan user
    //         $query = InvoiceDetail::with([
    //             'invoice',
    //             'transaksiDetail.transaksi.customerOrder.draftCustomer',
    //             'transaksiDetail.stok'
    //         ])
    //             ->whereHas('transaksiDetail.transaksi.customerOrder.draftCustomer', function ($query) {
    //                 $query->where('user_id', Auth::id());
    //             })
    //             ->when($start_date && $end_date, function ($q) use ($start_date, $end_date) {
    //                 $q->whereBetween('tanggal_keluar', [$start_date, $end_date]);
    //             })
    //             ->get();

    //         return DataTables::of($query)
    //             ->addColumn('nomor_invoice', function ($row) {
    //                 return $row->invoice->nota_no ?? '-';
    //             })
    //             ->addColumn('nama_customer', function ($row) {
    //                 return $row->transaksiDetail->transaksi->customerOrder->draftCustomer->Nama ?? '-';
    //             })
    //             ->addColumn('item_dipilih', function ($row) {
    //                 return $row->transaksiDetail->stok->nama_produk ?? '-';
    //             })
    //             ->addColumn('ongkir', function ($row) {
    //                 return $row->invoice->ongkir ?? 0;
    //             })
    //             ->addColumn('subtotal', function ($row) {
    //                 return $row->subtotal ?? 0;
    //             })
    //             ->addColumn('down_payment', function ($row) {
    //                 return $row->invoice->down_payment ?? 0;
    //             })
    //             ->addColumn('total_sisa', function ($row) {
    //                 $subtotal = $row->subtotal ?? 0;
    //                 $dp = $row->invoice->down_payment ?? 0;
    //                 return $subtotal - $dp;
    //             })
    //             ->addColumn('status_pembayaran', function ($row) {
    //                 return $row->invoice->status_pembayaran ?? '-';
    //             })
    //             ->addColumn('tenggat_waktu', function ($row) {
    //                 return $row->invoice->tenggat_invoice
    //                     ? \Carbon\Carbon::parse($row->invoice->tenggat_invoice)->format('d F Y')
    //                     : '-';
    //             })
    //             ->addColumn('dikelola', function ($row) {
    //                 return $row->transaksiDetail->transaksi->customerOrder->draftCustomer->user->name ?? '-';
    //             })
    //             ->make(true);
    //     }

    //     return view('laporan.penjualan.index');
    // }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Ambil data berdasarkan tanggal
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $data = transaksiDetail::with(['transaksi', 'stok'])
            ->whereBetween('tanggal_keluar', [$start_date, $end_date])
            ->get();

        if ($data->isEmpty()) {
            return redirect()->route('laporan.penjualan')
                ->with('error', 'Tidak ada data pada rentang tanggal yang dipilih.');
        }

        // Buat PDF
        $pdf = Pdf::loadView('laporan.penjualan.pdf', compact('data', 'start_date', 'end_date'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan_penjualan_' . Carbon::now()->format('Ymd') . '.pdf');
    }
}
