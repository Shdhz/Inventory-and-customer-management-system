<?php

namespace App\Http\Controllers\Stok;

use App\Http\Controllers\Controller;
use App\Models\barangRusak;
use App\Models\productStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class barangRusakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = "Produk rusak";
        $button = null;

        if (Auth::user()->hasRole('produksi')) {
            $button = 'Tambah produk rusak';
            if ($request->ajax()) {
                $barangRusak = barangRusak::with('product.category')->get();
                return DataTables::of($barangRusak)
                    ->addIndexColumn()
                    ->addColumn('nama_produk', function ($row) {
                        return $row->product ? $row->product->nama_produk : 'N/A';
                    })
                    ->addColumn('kategori_id', function ($row) {
                        return $row->product && $row->product->category ? $row->product->category->nama_kategori : 'N/A'; // Menampilkan nama kategori
                    })
                    ->addColumn('updated_at', function ($row) {
                        return Carbon::parse($row->updated_at)->format('d M Y, H:i');
                    })
                    ->addColumn('actions', function ($row) {
                        return view('components.button.action-btn', [
                            'edit' => route('barang-rusak.edit', $row->barang_rusak_id),
                            'delete' => route('barang-rusak.destroy', $row->barang_rusak_id),
                        ])->render();
                    })
                    ->rawColumns(['actions'])
                    ->make(true);
            }
            return view('v-produksi.stok-barang.barang_rusak.index', compact('title', 'button'));
        }else{
            if ($request->ajax()) {
                $barangRusak = barangRusak::with('product.category')->get();
                return DataTables::of($barangRusak)
                    ->addIndexColumn()
                    ->addColumn('nama_produk', function ($row) {
                        return $row->product ? $row->product->nama_produk : 'N/A';
                    })
                    ->addColumn('kategori_id', function ($row) {
                        return $row->product && $row->product->category ? $row->product->category->nama_kategori : 'N/A';
                    })
                    ->addColumn('updated_at', function ($row) {
                        return Carbon::parse($row->updated_at)->format('d M Y, H:i');
                    })
                    ->make(true);
            }
            return view('v-produksi.stok-barang.barang_rusak.index', compact('title'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tambah Barang rusak";
        $backUrl = route('barang-rusak.index');
        $stokBarang = productStock::all();
        return view('v-produksi.stok-barang.barang_rusak.create', compact('title', 'backUrl', 'stokBarang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_stok' => 'required|exists:tb_products,id_stok',
            'jumlah_barang_rusak' => 'required|integer|min:1',
        ]);
        try {
            // Simpan data ke database
            barangRusak::create([
                'stok_id' => $request->id_stok,
                'jumlah_barang_rusak' => $request->jumlah_barang_rusak
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
        return redirect()->route('barang-rusak.index')->with('success', 'Barang rusak berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = "Edit Barang rusak";
        $backUrl = route('barang-rusak.index');

        $barangRusak = barangRusak::findOrFail($id);
        $stokBarang = productStock::all();
        return view('v-produksi.stok-barang.barang_rusak.edit', compact('title', 'backUrl', 'barangRusak', 'stokBarang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validated = $request->validate([
            'id_stok' => 'required|exists:tb_products,id_stok',
            'jumlah_barang_rusak' => 'required|integer|min:1',
        ]);

        $barangRusak = barangRusak::findOrFail($id);
        $barangRusak->update($validated);

        // Kembalikan ke halaman index dengan pesan sukses
        return redirect()->route('barang-rusak.index')->with('success', 'Barang rusak berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barangRusak = barangRusak::findOrFail($id);
        $barangRusak->delete();

        return back()->with('success', 'Barang rusak berhasil dihapus dan stok telah dikembalikan.');
    }
}
