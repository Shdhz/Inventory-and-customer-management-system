<?php

namespace App\Http\Controllers;

use App\Models\barangRusak;
use App\Models\invoice;
use App\Models\productStock;
use App\Models\rencanaProduksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class dashboardSupervisorController extends Controller
{
    public function index()
    {
        $draftCustomerCount = DB::table('tb_draft_customers')->count();
        $orderCustomerCount = DB::table('tb_customer_orders')->count();
        $barangRusakCount = barangRusak::with('product')->count();
        $productstockCount = productStock::sum('jumlah_stok');
        return view('v-supervisor.dashboard', compact('draftCustomerCount', 'orderCustomerCount', 'barangRusakCount', 'productstockCount'));
    }

    public function salesStatistics(Request $request)
    {
        if ($request->ajax()) {
            try {
                // Data tanggal
                $today = Carbon::today()->toDateString();
                $startOfWeek = Carbon::now()->startOfWeek()->toDateTimeString();
                $endOfWeek = Carbon::now()->endOfWeek()->toDateTimeString();
                $currentMonth = Carbon::now()->month;
                $currentYear = Carbon::now()->year;

                $salesQuery = function ($relationPath) {
                    return Invoice::whereHas($relationPath);
                };

                // Hitung penjualan harian
                $dailySales = $salesQuery('invoiceDetails.transaksiDetail.transaksi.customerOrder.draftCustomer')
                    ->whereDate('updated_at', $today)
                    ->sum('down_payment')
                    +
                    $salesQuery('invoiceFormPo.formPo.customerOrder.draftCustomer')
                    ->whereDate('updated_at', $today)
                    ->sum('down_payment');

                // Hitung penjualan mingguan
                $weeklySales = $salesQuery('invoiceDetails.transaksiDetail.transaksi.customerOrder.draftCustomer')
                    ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                    ->sum('down_payment')
                    +
                    $salesQuery('invoiceFormPo.formPo.customerOrder.draftCustomer')
                    ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                    ->sum('down_payment');

                // Hitung penjualan bulanan
                $monthlySales = $salesQuery('invoiceDetails.transaksiDetail.transaksi.customerOrder.draftCustomer')
                    ->whereMonth('updated_at', $currentMonth)
                    ->whereYear('updated_at', $currentYear)
                    ->sum('down_payment')
                    +
                    $salesQuery('invoiceFormPo.formPo.customerOrder.draftCustomer')
                    ->whereMonth('updated_at', $currentMonth)
                    ->whereYear('updated_at', $currentYear)
                    ->sum('down_payment');

                return response()->json([
                    'daily' => $dailySales,
                    'weekly' => $weeklySales,
                    'monthly' => $monthlySales,
                ]);
            } catch (\Exception $e) {
                // Log error jika ada masalah dalam query
                Log::error('Error on salesStatistics: ' . $e->getMessage());
                return response()->json(['error' => 'Failed to load data'], 500);
            }
        }

        return view('v-supervisor.dashboard');
    }

    public function unpaidInvoice(Request $request)
    {
        if ($request->ajax()) {
            try {
                $unpaidInvoices = Invoice::select([
                    'invoice_id',
                    'nota_no',
                    'status_pembayaran',
                    'total',
                    'tenggat_invoice',
                    'created_at',
                    'updated_at'
                ])
                    ->with([
                        'invoiceDetails.transaksiDetail.transaksi.customerOrder.draftCustomer.user' => function ($query) {
                            $query->select('id', 'name'); 
                        },
                        'invoiceFormPo.formPo.customerOrder.draftCustomer.user' => function ($query) {
                            $query->select('id', 'name'); 
                        }
                    ])
                    ->where('status_pembayaran', 'belum lunas')
                    ->get();

                return response()->json($unpaidInvoices);
            } catch (\Exception $e) {
                Log::error('Error on unpaidInvoice: ' . $e->getMessage());
                return response()->json(['error' => 'Failed to load data'], 500);
            }
        }
    }

    public function getProductionPlan()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $data = rencanaProduksi::with([
            'formPo' => function ($query) {
                $query->select('id_form_po', 'keterangan', 'qty');
            },
            'formPo.customerOrder.draftCustomer' => function ($query) {
                $query->select('id', 'user_id');
            }
        ])
            ->whereBetween('mulai_produksi', [$startOfWeek, $endOfWeek])
            ->select([
                'id_rencana_produksi',
                'form_po_id',
                'nama_pengrajin',
                'mulai_produksi',
                'berakhir_produksi',
                'status',
                'prioritas'
            ])
            ->get();

        return response()->json($data);
    }
}
