<?php

namespace App\Http\Controllers\transaksi;

use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use App\Models\productStock;
use App\Models\transaksi;
use App\Models\transaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $button = "Tambah transaksi";
        $btn_invoice = "Tambah Invoice";

        if (request()->ajax()) {
            $data = transaksi::with(['customerOrder', 'transaksiDetails.stok'])
                ->when(Auth::user()->hasRole('admin'), function ($query) {
                    $query->whereHas('customerOrder.draftCustomer', function ($subQuery) {
                        $subQuery->where('user_id', Auth::id());
                    });
                })
                ->get();

            return DataTables::of($data)
                ->addColumn('customer_name', function ($row) {
                    return $row->customerOrder->draftCustomer->Nama ?? '-';
                })
                ->addColumn('ekspedisi', function ($row) {
                    return $row->ekspedisi ?? 'N/A';  // Nama customer
                })
                ->addColumn('item_pilih', function ($row) {
                    $produkDanJumlah = $row->transaksiDetails->map(function ($detail) {
                        $namaProduk = $detail->stok->nama_produk ?? '-';
                        $jumlahItem = $detail->qty ?? 0;
                        return "$namaProduk : $jumlahItem";
                    })->implode('<br>');
                    return $produkDanJumlah;
                })
                ->addColumn('harga_satuan', function ($row) {
                    $details = $row->transaksiDetails;
                    if ($details->isEmpty()) {
                        return '-';
                    }
                    $hargaList = $details->map(function ($detail) {
                        return number_format($detail->harga_satuan, 0, ',', '.');
                    })->implode('<br>Rp ');

                    return $hargaList;
                })
                ->addColumn('total_harga', function ($row) {
                    // Menghitung total harga
                    $total_harga = $row->transaksiDetails->sum(function ($detail) {
                        return $detail->harga_satuan * $detail->qty;
                    });
                    return  number_format($total_harga, 0, ',', '.');
                })
                ->addColumn('diskon', function ($row) {
                    return number_format($row->diskon_produk ?? 0, 2, '.', ',');
                })
                ->addColumn('grand_total', function ($row) {
                    // Menghitung grand total setelah diskon
                    $total_harga = $row->transaksiDetails->sum(function ($detail) {
                        return $detail->harga_satuan * $detail->qty;
                    });
                    $diskon = $row->diskon_produk ?? 0;
                    // Pastikan grand total dihitung dengan benar
                    $grand_total = $total_harga - ($total_harga * $diskon / 100);
                    return number_format($grand_total, 0, ',', '.');
                })
                ->addColumn('metode_pembayaran', function ($row) {
                    return ucfirst($row->metode_pembayaran);
                })
                ->addColumn('admin_name', function ($row) {
                    return $row->customerOrder->draftCustomer->user->name ?? '-';
                })
                ->addColumn('actions', function ($row) {
                    return view('components.button.action-btn', [
                        'edit' => route('transaksi-customer.edit', $row->id_transaksi),
                        'delete' => route('transaksi-customer.destroy', $row->id_transaksi),
                    ])->render();
                })
                ->rawColumns(['actions', 'item_pilih', 'harga_satuan'])
                ->make(true);
        }
        return view('transaksi.transaksi-customer.index', compact('button', 'btn_invoice'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tambah Data Transaksi";
        $backUrl = url()->previous();

        $customers = CustomerOrder::with('draftCustomer')
            ->where('jenis_order', 'ready stock')
            ->when(Auth::user()->hasRole('admin'), function ($query) {
                $query->whereHas('draftCustomer.user', function ($subQuery) {
                    $subQuery->where('user_id', Auth::id());
                });
            })
            ->whereDoesntHave('transaksi')
            ->get();

        // Tambahkan sumber dari draftCustomer
        foreach ($customers as $customer) {
            $customer->sumber = $customer->draftCustomer->sumber ?? 'Tidak Diketahui';
        }

        // Ambil data produk
        $products = ProductStock::all();
        if ($products->isEmpty()) {
            return redirect($backUrl)->with('error', 'Tidak ada data produk yang tersedia.');
        }

        $transaksiDetails = TransaksiDetail::where('transaksi_id')->get();

        return view('transaksi.transaksi-customer.create', compact('customers', 'products', 'title', 'backUrl', 'transaksiDetails'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_order_id' => 'required|exists:tb_customer_orders,customer_order_id',
            'products.*.stok_id' => 'required|exists:tb_products,id_stok',
            'products.*.qty' => 'required|integer|min:1',
            'products.*.harga_satuan' => 'required|numeric|min:1',
            'payment_method' => 'required|string|in:cod,transfer',
            'expedition' => 'nullable|string|max:255',
            'discount_product_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        // Validasi jika produk yang sama sudah dipilih lebih dari sekali
        $selectedProductIds = collect($request->products)->pluck('stok_id')->toArray();
        if (count($selectedProductIds) !== count(array_unique($selectedProductIds))) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Produk yang sama tidak boleh dipilih lebih dari sekali.']);
        }

        // Validasi stok produk
        $insufficientStock = [];
        foreach ($request->products as $product) {
            $stok = ProductStock::findOrFail($product['stok_id']);
            if ($product['qty'] > $stok->jumlah_stok) {
                $insufficientStock[] = $stok->nama_produk;
            }
        }

        if (!empty($insufficientStock)) {
            return redirect()->back()->withInput()->withErrors([
                'error' => "Stok tidak mencukupi untuk produk: " . implode(', ', $insufficientStock),
            ]);
        }

        // Hitung subtotal transaksi
        $subtotal = collect($request->products)->reduce(function ($carry, $product) {
            return $carry + ($product['qty'] * $product['harga_satuan']);
        }, 0);

        // Hitung diskon produk
        $discountProductPercent = $request->discount_product_percent ?? 0;
        $discountProduct = ($subtotal * $discountProductPercent) / 100;
        $total = $subtotal - $discountProduct;

        // Simpan data transaksi
        $transaksi = Transaksi::create([
            'customer_order_id' => $request->customer_order_id,
            'diskon_produk' => $discountProductPercent,
            'diskon_ongkir' => 0,
            'ekspedisi' => $request->expedition,
            'metode_pembayaran' => $request->payment_method,
        ]);

        // Simpan detail transaksi
        foreach ($request->products as $product) {
            $subtotalDetail = $product['qty'] * $product['harga_satuan'];

            $hargaSatuan = round($product['harga_satuan']);

            TransaksiDetail::create([
                'transaksi_id' => $transaksi->id_transaksi,
                'stok_id' => $product['stok_id'],
                'qty' => $product['qty'],
                'harga_satuan' => $hargaSatuan,
                'subtotal' => $subtotalDetail,
                'jumlah' => $product['qty'] * $product['harga_satuan'],
                'tanggal_keluar' => now(),
            ]);
        }

        // Update total transaksi setelah diskon
        $transaksi->update([
            'total' => $total,
        ]);

        return redirect()->route('transaksi-customer.index')->with('success', 'Transaksi berhasil disimpan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Ambil data transaksi beserta detail dan relasi yang diperlukan
        $transaksi = Transaksi::with(['customerOrder.draftCustomer', 'transaksiDetails.stok'])
            ->findOrFail($id);

        $customers = CustomerOrder::with('draftCustomer')
            ->when(Auth::user()->hasRole('admin'), function ($query) {
                $query->whereHas('draftCustomer.user', function ($subQuery) {
                    $subQuery->where('user_id', Auth::id());
                });
            })
            ->where('customer_order_id', $transaksi->customer_order_id)
            ->get();

        foreach ($customers as $customer) {
            $customer->sumber = $customer->draftCustomer->sumber ?? 'Tidak Diketahui';
        }
        $products = ProductStock::all();

        if ($products->isEmpty()) {
            return redirect()->route('transaksi-customer.index')->with('error', 'Tidak ada data produk yang tersedia.');
        }

        // Siapkan variabel untuk tampilan
        $title = "Edit Transaksi";
        $backUrl = route('transaksi-customer.index');
        $transaksiDetails = $transaksi->transaksiDetails; // Detail transaksi terkait

        return view('transaksi.transaksi-customer.edit', compact('transaksi', 'transaksiDetails', 'customers', 'products', 'title', 'backUrl'));
    }
    /**
     * Update the specified resource in storage.
     */

    public function calculateTotal($transaksi)
    {
        $subtotal = 0;
        foreach ($transaksi->transaksiDetails as $detail) {
            $subtotal += $detail->qty * $detail->harga_satuan;
        }

        $diskon = $transaksi->diskon_produk ?? 0;
        $diskonAmount = ($subtotal * $diskon) / 100;

        $total = $subtotal - $diskonAmount;

        $transaksi->update([
            'subtotal' => $subtotal,
            'total' => $total
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_order_id' => 'required|exists:tb_customer_orders,customer_order_id',
            'products.*.stok_id' => 'required|exists:tb_products,id_stok',
            'products.*.qty' => 'required|integer|min:1',
            'products.*.harga_satuan' => 'required|numeric|min:1',
            'payment_method' => 'required|string|in:cod,transfer',
            'expedition' => 'nullable|string|max:255',
            'discount_product_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update([
            'customer_order_id' => $request->customer_order_id,
            'diskon_produk' => $request->discount_product_percent ?? 0,
            'metode_pembayaran' => $request->payment_method,
            'ekspedisi' => $request->expedition,
        ]);

        // Update atau tambahkan detail transaksi
        foreach ($request->products as $product) {
            $detail = $transaksi->transaksiDetails()->where('stok_id', $product['stok_id'])->first();

            if ($detail) {
                $detail->update([
                    'qty' => $product['qty'],
                    'harga_satuan' => round(str_replace('.', '', $product['harga_satuan'])),
                    'subtotal' => $product['qty'] * round(str_replace('.', '', $product['harga_satuan'])),
                ]);
            } else {
                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id_transaksi,
                    'stok_id' => $product['stok_id'],
                    'qty' => $product['qty'],
                    'harga_satuan' => round(str_replace('.', '', $product['harga_satuan'])),
                    'subtotal' => $product['qty'] * round(str_replace('.', '', $product['harga_satuan'])),
                    'jumlah' => $product['qty'] * $product['harga_satuan'],
                    'tanggal_keluar' => now(),
                ]);
            }
        }
        $this->calculateTotal($transaksi);
        return redirect()->route('transaksi-customer.index')->with('success', 'Transaksi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $transaksi = Transaksi::with('transaksiDetails')->findOrFail($id);
                foreach ($transaksi->transaksiDetails as $detail) {
                    $detail->delete();
                }

                // Hapus transaksi utama
                $transaksi->delete();
            });

            return back()->with('success', 'Transaksi berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error("Error menghapus transaksi ID: $id. Pesan: " . $e->getMessage());

            return back()->with('error', 'Gagal menghapus transaksi. Silakan coba lagi.');
        }
    }
}
