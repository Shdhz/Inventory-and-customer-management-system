<?php

namespace App\Http\Controllers\laporan;

use App\Http\Controllers\Controller;
use App\Models\transaksiDetail;
use Illuminate\Http\Request;
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
}
