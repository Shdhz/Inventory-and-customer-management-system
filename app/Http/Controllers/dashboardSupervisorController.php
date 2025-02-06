<?php

namespace App\Http\Controllers;

use App\Models\barangRusak;
use App\Models\invoice;
use App\Models\productStock;
use App\Models\rencanaProduksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class dashboardSupervisorController extends Controller
{
    public function index(request $request)
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
                    return invoice::whereHas($relationPath);
                };

                $cacheDuration = now()->addMinutes(10);

                $dailySales = Cache::remember('daily_sales_' . $today, $cacheDuration, function () use ($salesQuery, $today) {
                    return $salesQuery('invoiceDetails.transaksiDetail.transaksi.customerOrder.draftCustomer')
                        ->whereDate('updated_at', $today)
                        ->sum('down_payment')
                        +
                        $salesQuery('invoiceFormPo.formPo.customerOrder.draftCustomer')
                        ->whereDate('updated_at', $today)
                        ->sum('down_payment');
                });

                $weeklySales = Cache::remember('weekly_sales_' . $startOfWeek . '_' . $endOfWeek, $cacheDuration, function () use ($salesQuery, $startOfWeek, $endOfWeek) {
                    return $salesQuery('invoiceDetails.transaksiDetail.transaksi.customerOrder.draftCustomer')
                        ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                        ->sum('down_payment')
                        +
                        $salesQuery('invoiceFormPo.formPo.customerOrder.draftCustomer')
                        ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                        ->sum('down_payment');
                });

                $monthlySales = Cache::remember('monthly_sales_' . $currentMonth . '_' . $currentYear, $cacheDuration, function () use ($salesQuery, $currentMonth, $currentYear) {
                    return $salesQuery('invoiceDetails.transaksiDetail.transaksi.customerOrder.draftCustomer')
                        ->whereMonth('updated_at', $currentMonth)
                        ->whereYear('updated_at', $currentYear)
                        ->sum('down_payment')
                        +
                        $salesQuery('invoiceFormPo.formPo.customerOrder.draftCustomer')
                        ->whereMonth('updated_at', $currentMonth)
                        ->whereYear('updated_at', $currentYear)
                        ->sum('down_payment');
                });

                return response()->json([
                    'daily' => [
                        'sales' => $dailySales
                    ],
                    'weekly' => [
                        'sales' => $weeklySales
                    ],
                    'monthly' => [
                        'sales' => $monthlySales
                    ]
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

    public function getSalesSources(Request $request)
    {
        if ($request->ajax()) {
            try {
                // Kategori sumber penjualan
                $marketplaceSources = ['shopee', 'tokopedia', 'lazada'];
                $directSources = ['whatsapp', 'instagram', 'facebook', 'youtube', 'tiktok', 'tiktok shop'];

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

                    $readyStockQuery = invoice::whereHas('invoiceDetails.transaksiDetail.transaksi.customerOrder', function ($query) use ($source) {
                        $query->where('jenis_order', 'ready stock')
                            ->whereHas('draftCustomer', function ($subQuery) use ($source) {
                                $subQuery->where('sumber', $source);
                            });
                    });

                    $countReadyStock = $readyStockQuery->count();
                    $totalReadyStockDP = $readyStockQuery->sum('down_payment');

                    $preOrderQuery = invoice::whereHas('invoiceFormPo.formPo.customerOrder', function ($query) use ($source) {
                        $query->where('jenis_order', 'pre order')
                            ->whereHas('draftCustomer', function ($subQuery) use ($source) {
                                $subQuery->where('sumber', $source);
                            });
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
                return response()->json(['error' => 'Failed to load data'], 500);
            }
        }
    }

    public function getUserDownPayments(Request $request)
    {
        if ($request->ajax()) {
            try {
                $userDownPayments = invoice::with([
                    'invoiceDetails.transaksiDetail.transaksi.customerOrder.draftCustomer.user:id,name',
                    'invoiceFormPo.formPo.customerOrder.draftCustomer.user:id,name'
                ])
                    ->where('down_payment', '>', 0)
                    ->select('invoice_id', 'down_payment', 'created_at')
                    ->get();

                $todayDate = now()->startOfDay();
                $startOfWeek = now()->startOfWeek();
                $startOfMonth = now()->startOfMonth();

                $users = [];

                foreach ($userDownPayments as $invoice) {
                    $userName = null;
                    
                    $invoiceDetail = $invoice->invoiceDetails->first();
                    if ($invoiceDetail && $invoiceDetail->transaksiDetail && $invoiceDetail->transaksiDetail->transaksi) {
                        $userName = $invoiceDetail->transaksiDetail->transaksi->customerOrder->draftCustomer->user->name ?? null;
                    }
                    
                    if (!$userName && $invoice->invoiceFormPo->isNotEmpty()) {
                        $formPo = $invoice->invoiceFormPo->first();
                        $userName = $formPo->formPo->customerOrder->draftCustomer->user->name ?? null;
                    }

                    if ($userName) {
                        if (!isset($users[$userName])) {
                            $users[$userName] = [
                                'harian' => 0,
                                'mingguan' => 0,
                                'bulanan' => 0
                            ];
                        }

                        $createdAt = $invoice->created_at;

                        // Tambahkan DP ke periode yang sesuai
                        if ($createdAt >= $todayDate) {
                            $users[$userName]['harian'] += $invoice->down_payment;
                        }
                        if ($createdAt >= $startOfWeek) {
                            $users[$userName]['mingguan'] += $invoice->down_payment;
                        }
                        if ($createdAt >= $startOfMonth) {
                            $users[$userName]['bulanan'] += $invoice->down_payment;
                        }
                    }
                }

                // Format untuk Chart.js
                $labels = ['Harian', 'Mingguan', 'Bulanan'];
                $datasets = [];

                foreach ($users as $userName => $data) {
                    $datasets[] = [
                        'label' => $userName,
                        'data' => [
                            $data['harian'],
                            $data['mingguan'],
                            $data['bulanan']
                        ]
                    ];
                }

                return response()->json([
                    'labels' => $labels,
                    'datasets' => $datasets
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }
}
