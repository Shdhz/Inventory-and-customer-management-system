<?php

namespace App\Http\Controllers\stok;

use App\Http\Controllers\Controller;
use App\Models\categoriesProduct;
use App\Models\productStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class stokBarangController extends Controller
{

    public function index(Request $request)
    {
        $title = 'Stok Barang';

        null;

        if (Auth::user()->hasRole('produksi')) {
            $button = 'Tambah Stok barang';

            if ($request->ajax()) {
                $productStocks = productStock::with('category')->get();
                return DataTables::of($productStocks)
                    ->addIndexColumn()
                    ->addColumn('updated_at', function ($row) {
                        return Carbon::parse($row->updated_at)->format('d M Y, H:i');
                    })
                    ->addColumn('nama_kategori', function ($row) {
                        return $row->category ? $row->category->nama_kategori : 'N/A';
                    })
                    ->addColumn('kelompok_produksi', function ($row) {
                        return $row->category ? $row->category->kelompok_produksi : 'N/A';
                    })
                    ->addColumn('foto_produk', function ($row) {
                        return $row->foto_produk
                            ? '<img src="' . asset('storage/uploads/stok-barang/' . $row->foto_produk) . '" alt="Foto Produk" width="50">'
                            : 'N/A';
                    })
                    ->addColumn('actions', function ($row) {
                        return view('components.button.action-btn', [
                            'edit' => route('stok-barang.edit', $row->id_stok),
                            'delete' => route('stok-barang.destroy', $row->id_stok),
                        ])->render();
                    })
                    ->rawColumns(['foto_produk', 'actions'])
                    ->make(true);
            }
            return view('v-produksi.stok-barang.stok.index', compact('title', 'button'));
        } else {
            if ($request->ajax()) {
                $productStocks = productStock::with('category')->get();
                return DataTables::of($productStocks)
                    ->addIndexColumn()
                    ->addColumn('updated_at', function ($row) {
                        return Carbon::parse($row->updated_at)->format('d M Y, H:i');
                    })
                    ->addColumn('nama_kategori', function ($row) {
                        return $row->category ? $row->category->nama_kategori : 'N/A';
                    })
                    ->addColumn('kelompok_produksi', function ($row) {
                        return $row->category ? $row->category->kelompok_produksi : 'N/A';
                    })
                    ->addColumn('foto_produk', function ($row) {
                        $imageUrl = $row->foto_produk ? asset('storage/uploads/stok-barang/' . $row->foto_produk) : null;
                        return $imageUrl
                            ? '<img src="' . $imageUrl . '" alt="Foto Produk" width="50">'
                            : 'N/A';
                    })
                    ->rawColumns(['foto_produk'])
                    ->make(true);
            }
            return view('v-produksi.stok-barang.stok.index', compact('title'));
        }
    }


    public function create()
    {
        $title = "Tambah stok barang";
        $backUrl = route('stok-barang.index');
        $categories = categoriesProduct::select('id_kategori', 'nama_kategori', 'kelompok_produksi')->get();
        return view('v-produksi.stok-barang.stok.create', compact('title', 'backUrl', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:tb_categories_products,id_kategori',
            'kode_produk' => 'required|unique:tb_products,kode_produk',
            'nama_produk' => 'required|max:100',
            'jumlah_stok' => 'required|integer|min:0',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Proses file upload
        if ($request->hasFile('foto_produk')) {
            $gambarFile = $request->file('foto_produk');
            $gambarFileName = uniqid() . '.' . $gambarFile->getClientOriginalExtension();
            $gambarFile->storeAs('uploads/stok-barang', $gambarFileName, 'public');
        }

        try {
            // Simpan data ke database
            productStock::create([
                'kategori_id' => $request->kategori_id,
                'kode_produk' => $request->kode_produk,
                'nama_produk' => $request->nama_produk,
                'jumlah_stok' => $request->jumlah_stok,
                'foto_produk' => $gambarFileName,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }

        return redirect()->route('stok-barang.index')->with('success', 'Stok barang berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $title = "Edit stok barang";
        $backUrl = route('stok-barang.index');

        $product = ProductStock::findOrFail($id);

        $categories = CategoriesProduct::select('id_kategori', 'nama_kategori', 'kelompok_produksi')->get();
        $currentCategory = $categories->firstWhere('id_kategori', $product->kategori_id);
        $product->kelompok_produksi = $currentCategory ? $currentCategory->kelompok_produksi : $product->kelompok_produksi;

        return view('v-produksi.stok-barang.stok.edit', compact('title', 'backUrl', 'product', 'categories'));
    }

    // update
    public function update(Request $request, string $id)
    {
        $product = productStock::findOrFail($id);

        $request->validate([
            'kategori_id' => 'required|exists:tb_categories_products,id_kategori',
            'kode_produk' => 'required|unique:tb_products,kode_produk,' . $product->id_stok . ',id_stok',
            'nama_produk' => 'required|max:100',
            'jumlah_stok' => 'required|integer|min:0',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Periksa apakah kode produk berubah
        $oldPrefix = substr($product->kode_produk, 0, 2);
        $oldNumber = (int) substr($product->kode_produk, 2);
        $newPrefix = substr($request->kode_produk, 0, 2);
        $newNumber = (int) substr($request->kode_produk, 2);

        if ($oldPrefix === $newPrefix && $oldNumber !== $newNumber) {
            // Perbarui kode produk lain jika nomor berubah
            if ($oldNumber < $newNumber) {
                // Geser ke atas (kurangi nomor urut untuk barang di antara)
                productStock::where('kode_produk', 'like', $oldPrefix . '%')
                    ->whereRaw('CAST(SUBSTR(kode_produk, 3) AS UNSIGNED) BETWEEN ? AND ?', [$oldNumber + 1, $newNumber])
                    ->orderBy('kode_produk')
                    ->get()
                    ->each(function ($affectedProduct) {
                        $currentNumber = (int) substr($affectedProduct->kode_produk, 2);
                        $affectedProduct->update([
                            'kode_produk' => substr($affectedProduct->kode_produk, 0, 2) . str_pad($currentNumber - 1, 4, '0', STR_PAD_LEFT),
                        ]);
                    });
            } else {
                // Geser ke bawah (tambah nomor urut untuk barang di antara)
                productStock::where('kode_produk', 'like', $oldPrefix . '%')
                    ->whereRaw('CAST(SUBSTR(kode_produk, 3) AS UNSIGNED) BETWEEN ? AND ?', [$newNumber, $oldNumber - 1])
                    ->orderBy('kode_produk', 'desc')
                    ->get()
                    ->each(function ($affectedProduct) {
                        $currentNumber = (int) substr($affectedProduct->kode_produk, 2);
                        $affectedProduct->update([
                            'kode_produk' => substr($affectedProduct->kode_produk, 0, 2) . str_pad($currentNumber + 1, 4, '0', STR_PAD_LEFT),
                        ]);
                    });
            }
        }

        // Update foto produk
        $gambarFileName = $product->foto_produk;
        if ($request->hasFile('foto_produk')) {
            if ($product->foto_produk && Storage::disk('public')->exists('uploads/stok-barang/' . $product->foto_produk)) {
                Storage::disk('public')->delete('uploads/stok-barang/' . $product->foto_produk);
            }

            $gambarFile = $request->file('foto_produk');
            $gambarFileName = uniqid() . '.' . $gambarFile->getClientOriginalExtension();
            $gambarFile->storeAs('uploads/stok-barang', $gambarFileName, 'public');
        }

        // Update data produk
        $product->update([
            'kategori_id' => $request->kategori_id,
            'kode_produk' => $request->kode_produk,
            'nama_produk' => $request->nama_produk,
            'jumlah_stok' => $request->jumlah_stok,
            'foto_produk' => $gambarFileName,
        ]);

        return redirect()->route('stok-barang.index')->with('success', 'Stok barang berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $product = productStock::findOrFail($id);

        $prefix = substr($product->kode_produk, 0, 2);
        $deletedNumber = (int) substr($product->kode_produk, 2);

        if ($product->foto_produk) {
            Storage::delete('storage/uploads/stok-barang/' . $product->foto_produk);
        }
        $product->delete();

        $affectedProducts = productStock::where('kode_produk', 'like', $prefix . '%')
            ->whereRaw('CAST(SUBSTR(kode_produk, 3) AS UNSIGNED) > ?', [$deletedNumber])
            ->orderBy('kode_produk')
            ->get();

        foreach ($affectedProducts as $affectedProduct) {
            $oldNumber = (int) substr($affectedProduct->kode_produk, 2);
            $newNumber = str_pad($oldNumber - 1, 4, '0', STR_PAD_LEFT);
            $affectedProduct->update([
                'kode_produk' => $prefix . $newNumber,
            ]);
        }

        return redirect()->route('stok-barang.index')->with('success', 'Stok barang berhasil dihapus dan kode barang telah diperbarui.');
    }

    // generate kode product
    public function generateKodeProduk(Request $request)
    {
        $prefix = $request->query('prefix');
        if (!$prefix || !in_array($prefix, ['BU', 'BX', 'TS'])) {
            return response()->json(['error' => 'Prefix tidak valid'], 400);
        }

        $lastProduct = productStock::where('kode_produk', 'like', $prefix . '%')
            ->orderByDesc('created_at')
            ->first();

        // Generate nomor acak baru
        $randomNumber = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT); // Angka acak dari 0001 hingga 9999

        if (!$lastProduct) {
            $kodeProduk = $prefix . $randomNumber;
        } else {
            $lastRandomNumber = substr($lastProduct->kode_produk, strlen($prefix));
            $newNumber = str_pad((int)$lastRandomNumber + 1, 4, '0', STR_PAD_LEFT);
            $kodeProduk = $prefix . $newNumber;
        }

        return response()->json(['kode_produk' => $kodeProduk]);
    }
}
