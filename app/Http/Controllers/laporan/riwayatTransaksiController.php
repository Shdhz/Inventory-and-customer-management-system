<?php

namespace App\Http\Controllers\laporan;

use App\Http\Controllers\Controller;
use App\Models\transaksiDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class riwayatTransaksiController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = transaksiDetail::with(['transaksi', 'stok'])->whereHas('transaksi.customerOrder.draftCustomer.user', function ($query) {
                $query->where('user_id', Auth::id());
            })->get();

            return DataTables::of($query)
                ->addColumn('nama_barang', function ($row) {
                    return $row->stok->nama_produk ?? '-';
                })
                ->addColumn('harga_satuan', function ($row) {
                    return 'Rp ' . number_format($row->harga_satuan, 2, ',', '.');
                })
                ->addColumn('subtotal', function ($row) {
                    return 'Rp ' . number_format($row->subtotal, 2, ',', '.');
                })
                ->addColumn('tanggal_transaksi', function ($row) {
                    return Carbon::parse($row->tanggal_keluar)->format('d F Y');
                })
                ->addColumn('customer', function ($row) {
                    return $row->transaksi->customerOrder->draftCustomer->Nama ?? '-';
                })
                ->rawColumns(['nama_barang', 'harga_satuan', 'subtotal', 'tanggal_keluar', 'customer'])
                ->make(true);
        }
        return view('laporan.riwayat_transaksi.index');
    }

    public function exportPdf(Request $request)
    {
        $search = $request->get('search', '');

        $query = transaksiDetail::with(['transaksi', 'stok']);
        if (!empty($search)) {
            $query->whereHas('transaksi', function ($query) use ($search) {
                $query->where('', 'like', "%{$search}%");
            });
        }
        $data = $query->get();

        $pdf = Pdf::loadView('laporan.riwayat_transaksi.pdf', compact('data'));
        return $pdf->download('laporan_penjualan_' . Carbon::now()->format('Ymd') . '.pdf');
    }


    // public function exportExcel()
    // {
    //     return Excel::download(new LaporanPenjualanExport, 'laporan_penjualan_' . Carbon::now()->format('Ymd') . '.xlsx');
    // }
}
