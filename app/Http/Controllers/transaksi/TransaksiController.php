<?php

namespace App\Http\Controllers\transaksi;

use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use App\Models\productStock;
use App\Models\transaksi;
use App\Models\transaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $button = "Tambah transaksi";

        if (request()->ajax()) {
            // Mengambil data transaksi dengan relasi yang diperlukan
            $data = transaksi::with(['customerOrder', 'transaksiDetails']) // Mengambil transaksi dengan detail transaksi
                ->whereHas('customerOrder.draftCustomer', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->get();

            return DataTables::of($data)
                ->addColumn('customer_name', function ($row) {
                    return $row->customerOrder->draftCustomer->Nama ?? 'N/A';  // Nama customer
                })
                ->addColumn('jumlah_item', function ($row) {
                    return $row->transaksiDetails->sum('qty');
                })
                ->addColumn('harga_satuan', function ($row) {
                    // Mengambil harga satuan dari transaksi details pertama, jika ada
                    $firstDetail = $row->transaksiDetails->first();
                    return $firstDetail ? number_format($firstDetail->harga_satuan, 0, ',', '.') : '-';
                })
                ->addColumn('total_harga', function ($row) {
                    // Menghitung total harga
                    $total_harga = $row->transaksiDetails->sum(function ($detail) {
                        return $detail->harga_satuan * $detail->qty;
                    });
                    return number_format($total_harga, 2, '.', ',');
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
                    return number_format($grand_total, 2, '.', ',');
                })
                ->addColumn('metode_pembayaran', function ($row) {
                    return ucfirst($row->metode_pembayaran);
                })
                ->addColumn('actions', function ($row) {
                    return view('components.button.action-btn', [
                        'edit' => route('transaksi-customer.edit', $row->id_transaksi),
                        'delete' => route('transaksi-customer.destroy', $row->id_transaksi),
                    ])->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('transaksi.transaksi-customer.index', compact('button'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tambah Data Transaksi";
        $backUrl = route('transaksi-customer.index');

        $customers = CustomerOrder::with('draftCustomer')->get();
        foreach ($customers as $customer) {
            $customer->sumber = $customer->draftCustomer->sumber ?? 'Tidak Diketahui';
        }
        $products = productStock::all();
        if ($products->isEmpty()) {
            return redirect($backUrl)->with('error', 'Tidak ada data produk yang tersedia.');
        }

        return view('transaksi.transaksi-customer.create', compact('customers', 'products', 'title', 'backUrl'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'customer_order_id' => 'required|exists:tb_customer_orders,customer_order_id',
            'products.*.stok_id' => 'required|exists:tb_products,id_stok',
            'products.*.qty' => 'required|integer|min:1',
            'products.*.harga_satuan' => 'required|numeric|min:1',
            'payment_method' => 'required|string|in:cod,transfer',
            'expedition' => 'required|string|max:255',
            'discount_product_percent' => 'nullable|numeric|min:0|max:100',
        ]);

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
            'diskon_ongkir' => 0, // Sesuaikan jika ada diskon ongkir
            'ekspedisi' => $request->expedition,
            'metode_pembayaran' => $request->payment_method,
        ]);

        // Simpan detail transaksi
        foreach ($request->products as $product) {
            $subtotalDetail = $product['qty'] * $product['harga_satuan']; // Subtotal per item

            // Pastikan harga satuan yang disimpan adalah angka bulat
            $hargaSatuan = round($product['harga_satuan']); // Membulatkan harga satuan jika perlu

            transaksiDetail::create([
                'transaksi_id' => $transaksi->id_transaksi,
                'stok_id' => $product['stok_id'],
                'qty' => $product['qty'],
                'harga_satuan' => $hargaSatuan,  // Menyimpan harga satuan yang dibulatkan
                'subtotal' => $subtotalDetail, // Subtotal per item
                'jumlah' => $product['qty'], // Jumlah barang keluar
                'tanggal_keluar' => now(), // Sesuaikan dengan kebutuhan
            ]);
        }
        // Update total transaksi setelah diskon
        $transaksi->update([
            'total' => $total,
        ]);

        return redirect()->route('transaksi-customer.index')->with('success', 'Transaksi berhasil disimpan!');
    }


    /**
     * Display the specified resource.
     */
    public function show(transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, transaksi $transaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(transaksi $transaksi)
    {
        //
    }
}
