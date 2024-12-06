<?php

namespace App\Http\Controllers\transaksi;

use App\Http\Controllers\Controller;
use App\Models\barangMasuk;
use App\Models\productStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class barangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = "Barang Masuk";
        $button = "Tambah barang Masuk";

        if ($request->ajax()) {
            $barangMasuk = BarangMasuk::with('product')->get();
            return DataTables::of($barangMasuk)
                ->addIndexColumn()
                ->addColumn('nama_produk', function ($row) {
                    return $row->product ? $row->product->nama_produk : 'N/A';
                })
                ->addColumn('kategori_id', function ($row) {
                    return $row->product && $row->product->category ? $row->product->category->nama_kategori : 'N/A';
                })
                ->addColumn('tanggal_barang_masuk', function ($row) {
                    return Carbon::parse($row->tanggal_barang_masuk)->format('d M Y');
                })
                ->addColumn('actions', function ($row) {
                    return view('components.button.action-btn', [
                        'edit' => route('barang-masuk.edit', $row->id_barang_masuk),
                        'delete' => route('barang-masuk.destroy', $row->id_barang_masuk),
                    ])->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('v-produksi.transaksi.barang-masuk.index', compact('title', 'button'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangMasuk = productStock::all();
        $title = "Tambah Barang Masuk";
        $backUrl = route('barang-masuk.index');

        return view('v-produksi.transaksi.barang-masuk.create', compact('barangMasuk', 'title', 'backUrl'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_barang_masuk' => 'required|date',
            'stok_id' => 'required|exists:tb_products,id_stok',
            'jumlah_barang_masuk' => 'required|integer|min:1',
        ]);

        // Simpan Data Barang Masuk
        BarangMasuk::create([
            'tanggal_barang_masuk' => $request->tanggal_barang_masuk,
            'stok_id' => $request->stok_id,
            'jumlah_barang_masuk' => $request->jumlah_barang_masuk,
        ]);

        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil ditambahkan.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = "Edit Barang Masuk";
        $backUrl = route('barang-masuk.index');
        $barangMasuk = BarangMasuk::findOrFail($id);
        $stokBarang = productStock::all();

        return view('v-produksi.transaksi.barang-masuk.edit', compact('title', 'backUrl', 'barangMasuk', 'stokBarang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tanggal_barang_masuk' => 'required|date',
            'stok_id' => 'required|exists:tb_products,id_stok',
            'jumlah_barang_masuk' => 'required|integer|min:1',
        ]);

        // Update Data Barang Masuk
        $barangMasuk = BarangMasuk::findOrFail($id);
        $barangMasuk->update([
            'tanggal_masuk' => $request->tanggal_barang_masuk,
            'stok_id' => $request->stok_id,
            'jumlah_barang_masuk' => $request->jumlah_barang_masuk,
        ]);

        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
        $barangMasuk->delete();

        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil dihapus.');
    }
}
