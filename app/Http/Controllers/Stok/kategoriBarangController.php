<?php

namespace App\Http\Controllers\stok;

use App\Http\Controllers\Controller;
use App\Models\categoriesProduct;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class kategoriBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Kategori barang';

        if (request()->ajax()) {
            $data = categoriesProduct::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    return view('components.button.action-btn', [
                        'edit' => route('kategori-barang.edit', $row->id_kategori),
                        'delete' => route('kategori-barang.destroy', $row->id_kategori),
                    ])->render();
                })
                ->rawColumns(['actions']) // Supaya tombol HTML dirender
                ->make(true);
        }
        return view('v-produksi.stok-barang.kategori.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tambah kategori barang";
        $backUrl = route('kategori-barang.index');
        
        return view('v-produksi.stok-barang.kategori.create', compact('title', 'backUrl'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // echo json_encode($request->all()); die;
        // Validasi input
        $validatedData = $request->validate([
            'nama_kategori' => 'required|max:20',
            'kelompok_produksi'=>'required'
        ]);
        // dd($validatedData);

        // Menyimpan kategori ke database
        categoriesProduct::create($validatedData);

        return redirect()->route('kategori-barang.index')->with('success', 'Kategori berhasil ditambahkan!');
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
    public function edit($id)
    {
        $title = 'Edit kategori barang';
        $backUrl = route('kategori-barang.index');

        $category = categoriesProduct::where('id_kategori', $id)->firstOrFail();

        return view('v-produksi.stok-barang.kategori.edit', [
            'category' => $category,
            'title' => $title,
            'backUrl'=> $backUrl
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // echo json_encode($request->all()); die;
        $validatedData = $request->validate([
            'nama_kategori' => 'required|max:20',
            'kelompok_produksi'=> 'required'
        ]);

        
        $category = categoriesProduct::where('id_kategori', $id)->firstOrFail();

        // Update data kategori
        $category->update($validatedData);


        // Redirect dengan pesan sukses
        return redirect()->route('kategori-barang.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = categoriesProduct::findOrFail($id);
        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}
