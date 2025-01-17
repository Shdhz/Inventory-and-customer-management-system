<?php

namespace App\Http\Controllers\transaksi;

use App\Http\Controllers\Controller;
use App\Models\transaksiDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class barangKeluarController extends Controller
{
    public function index()
    {
        $title = "Barang keluar";
        if (request()->ajax()) {
            $query = transaksiDetail::with(['transaksi', 'stok'])->get();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('tanggal_keluar', function ($row) {
                    return Carbon::parse($row->tanggal_keluar)->format('d F Y');
                })
                ->addColumn('kategori_barang', function ($row) {
                    return $row->stok->category->nama_kategori ?? '-';
                })
                ->addColumn('nama_barang', function ($row) {
                    return $row->stok->nama_produk ?? '-';
                })
                ->addColumn('jumlah_keluar', function ($row) {
                    return $row->qty ?? '-';
                })
                ->rawColumns(['tanggal_keluar', 'kategori_barang', 'nama_barang', 'jumlah_keluar'])
                ->make(true);
        }
        return view('v-produksi.transaksi.barang-keluar.index', compact('title'));
    }
}
