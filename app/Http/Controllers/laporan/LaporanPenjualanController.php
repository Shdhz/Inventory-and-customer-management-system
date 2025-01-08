<?php

namespace App\Http\Controllers\laporan;

use App\Http\Controllers\Controller;
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

    public function exportPdf(Request $request)
    {
        // $search = $request->get('search', '');
        // $start_date = $request->input('start_date');
        // $end_date = $request->input('end_date');
        $user = Auth::user();

        $query = transaksiDetail::with(['transaksi', 'stok']);

        if ($user->hasRole('admin')) {
            $query->whereHas('transaksi.customerOrder.draftCustomer', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        if ($request->start_date) {
            $query->whereDate('tanggal_keluar', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('tanggal_keluar', '<=', $request->end_date);
        }

        // if (!empty($search)) {
        //     $query->whereHas('transaksi', function ($query) use ($search) {
        //         $query->where('field_name', 'like', "%{$search}%");
        //     });
        // }

        $data = $query->get();
        $pdf = Pdf::loadView('laporan.penjualan.pdf', compact('data', 'request'))->setPaper('a4', 'landscape');
        return $pdf->download('laporan_penjualan_' . Carbon::now()->format('Ymd') . '.pdf');
    }
}
