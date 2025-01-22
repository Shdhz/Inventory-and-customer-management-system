<?php

namespace App\Http\Controllers;

use App\Models\barangRusak;
use App\Models\CustomerOrder;
use App\Models\DraftCustomer;
use App\Models\Invoice;
use App\Models\productStock;
use App\Models\rencanaProduksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class dashboardAdminController extends Controller
{
    public function index(Request $request)
    {
        $adminId = Auth::id();

        // Hitung jumlah draft customers dan customer orders
        $draftCustomerCount = DraftCustomer::where('user_id', $adminId)->count();
        $orderCustomerCount = CustomerOrder::whereHas('draftCustomer', function ($query) use ($adminId) {
            $query->where('user_id', $adminId);
        })->count();
        $barangRusakCount = barangRusak::with('product')->count();
        $productstockCount = productStock::sum('jumlah_stok');

        return view('v-admin.dashboard', compact('draftCustomerCount', 'orderCustomerCount', 'barangRusakCount', 'productstockCount'));
    }


    public function salesStatistics(Request $request)
    {
        if ($request->ajax()) {
            try {
                $adminId = Auth::user()->id;

                // Data tanggal
                $today = Carbon::today()->toDateString();
                $startOfWeek = Carbon::now()->startOfWeek()->toDateTimeString();
                $endOfWeek = Carbon::now()->endOfWeek()->toDateTimeString();
                $currentMonth = Carbon::now()->month;
                $currentYear = Carbon::now()->year;

                // Query untuk menghitung total penjualan
                $salesQuery = function ($relationPath) use ($adminId) {
                    return invoice::whereHas($relationPath, function ($query) use ($adminId) {
                        $query->where('user_id', $adminId);
                    });
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
                $weeklySales = $salesQuery('invoiceDetails.transaksiDetail.transaksi.customerOrder.DraftCustomer')
                    ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                    ->sum('down_payment')
                    +
                    $salesQuery('invoiceFormPo.formPo.customerOrder.DraftCustomer')
                    ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                    ->sum('down_payment');

                // Hitung penjualan bulanan
                $monthlySales = $salesQuery('invoiceDetails.transaksiDetail.transaksi.customerOrder.DraftCustomer')
                    ->whereMonth('updated_at', $currentMonth)
                    ->whereYear('updated_at', $currentYear)
                    ->sum('down_payment')
                    +
                    $salesQuery('invoiceFormPo.formPo.customerOrder.DraftCustomer')
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

        return view('v-admin.dashboard');
    }

    public function unpaidInvoice(Request $request)
    {
        if ($request->ajax()) {
            try {
                $adminId = Auth::id();
                $unpaidInvoices = invoice::select([
                    'invoice_id',
                    'nota_no',
                    'status_pembayaran',
                    'total',
                    'tenggat_invoice',
                    'created_at',
                    'updated_at'
                ])
                    ->with([
                        'invoiceDetails.transaksiDetail.transaksi.customerOrder.draftCustomer' => function ($query) {
                            $query->select([
                                'draft_customers_id',
                                'Nama'
                            ]);
                        }
                    ])
                    ->where('status_pembayaran', 'belum lunas')
                    ->whereHas('invoiceDetails.transaksiDetail.transaksi.customerOrder.draftCustomer', function ($query) use ($adminId) {
                        $query->where('user_id', $adminId);
                    })
                    ->get();

                $unpaidFormPoInvoices = Invoice::select([
                    'invoice_id',
                    'nota_no',
                    'status_pembayaran',
                    'total',
                    'tenggat_invoice',
                    'created_at',
                    'updated_at'
                ])
                    ->with([
                        'invoiceFormPo.formPo.customerOrder.draftCustomer' => function ($query) {
                            $query->select([
                                'draft_customers_id',
                                'Nama'
                            ]);
                        }
                    ])
                    ->where('status_pembayaran', 'belum lunas')
                    ->whereHas('invoiceFormPo.formPo.customerOrder.draftCustomer', function ($query) use ($adminId) {
                        $query->where('user_id', $adminId);
                    })
                    ->get();

                $unpaidCustomers = $unpaidInvoices->merge($unpaidFormPoInvoices);

                return response()->json($unpaidCustomers);
            } catch (\Exception $e) {
                // Log error jika ada masalah dalam query
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
            ->whereHas('formPo.customerOrder.draftCustomer', function ($query) {
                $query->where('user_id', Auth::id());
            })
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
