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
use Illuminate\Support\Facades\Cache;
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

                $cacheDuration = now()->addMinutes(10);

                // Hitung penjualan harian dengan cache
                $dailySales = Cache::remember('daily_sales_' . $adminId . '_' . $today, $cacheDuration, function () use ($salesQuery, $today) {
                    return $salesQuery('invoiceDetails.transaksiDetail.transaksi.customerOrder.draftCustomer')
                        ->whereDate('updated_at', $today)
                        ->sum('down_payment')
                        +
                        $salesQuery('invoiceFormPo.formPo.customerOrder.draftCustomer')
                        ->whereDate('updated_at', $today)
                        ->sum('down_payment');
                });

                // Hitung penjualan mingguan dengan cache
                $weeklySales = Cache::remember('weekly_sales_' . $adminId . '_' . $startOfWeek . '_' . $endOfWeek, $cacheDuration, function () use ($salesQuery, $startOfWeek, $endOfWeek) {
                    return $salesQuery('invoiceDetails.transaksiDetail.transaksi.customerOrder.DraftCustomer')
                        ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                        ->sum('down_payment')
                        +
                        $salesQuery('invoiceFormPo.formPo.customerOrder.DraftCustomer')
                        ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                        ->sum('down_payment');
                });

                // Hitung penjualan bulanan dengan cache
                $monthlySales = Cache::remember('monthly_sales_' . $adminId . '_' . $currentMonth . '_' . $currentYear, $cacheDuration, function () use ($salesQuery, $currentMonth, $currentYear) {
                    return $salesQuery('invoiceDetails.transaksiDetail.transaksi.customerOrder.DraftCustomer')
                        ->whereMonth('updated_at', $currentMonth)
                        ->whereYear('updated_at', $currentYear)
                        ->sum('down_payment')
                        +
                        $salesQuery('invoiceFormPo.formPo.customerOrder.DraftCustomer')
                        ->whereMonth('updated_at', $currentMonth)
                        ->whereYear('updated_at', $currentYear)
                        ->sum('down_payment');
                });

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

    public function getSalesSources(Request $request)
    {
        if ($request->ajax()) {
            try {
                $adminId = Auth::user()->id;

                // Kategori sumber penjualan
                $marketplaceSources = ['shopee', 'tokopedia', 'lazada'];
                $directSources = ['whatsapp', 'instagram', 'facebook', 'youtube', 'tiktok', 'tiktok shop'];

                // Struktur data awal
                $salesData = [
                    'direct' => [
                        'ready_stock' => ['count' => 0, 'down_payment' => 0],
                        'pre_order' => ['count' => 0, 'down_payment' => 0]
                    ],
                    'marketplace' => [
                        'ready_stock' => ['count' => 0, 'down_payment' => 0],
                        'pre_order' => ['count' => 0, 'down_payment' => 0]
                    ]
                ];

                // Proses data per sumber
                foreach (array_merge($marketplaceSources, $directSources) as $source) {
                    $category = in_array($source, $marketplaceSources) ? 'marketplace' : 'direct';

                    $readyStockQuery = Invoice::whereHas('invoiceDetails.transaksiDetail.transaksi.customerOrder.draftCustomer', function ($query) use ($source, $adminId) {
                        $query->where('jenis_order', 'ready stock')
                            ->where('user_id', $adminId)
                            ->where('sumber', $source);
                    });

                    $countReadyStock = $readyStockQuery->count();
                    $totalReadyStockDP = $readyStockQuery->sum('down_payment');

                    $preOrderQuery = Invoice::whereHas('invoiceFormPo.formPo.customerOrder.draftCustomer', function ($query) use ($source, $adminId) {
                        $query->where('jenis_order', 'pre order')
                            ->where('user_id', $adminId)
                            ->where('sumber', $source);
                    });

                    $countPreOrder = $preOrderQuery->count();
                    $totalPreOrderDP = $preOrderQuery->sum('down_payment');

                    $salesData[$category]['ready_stock']['count'] += $countReadyStock;
                    $salesData[$category]['ready_stock']['down_payment'] += $totalReadyStockDP;

                    $salesData[$category]['pre_order']['count'] += $countPreOrder;
                    $salesData[$category]['pre_order']['down_payment'] += $totalPreOrderDP;
                }

                return response()->json($salesData);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }
}
