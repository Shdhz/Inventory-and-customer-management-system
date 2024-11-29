<?php

namespace App\Http\Controllers\stok;

use App\Http\Controllers\Controller;
use App\Models\categoriesProduct;
use Illuminate\Http\Request;

class kategoriBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Kategori barang';
        return view('v-produksi.stok-barang.kategori.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tambah kategori barang";
        return view('v-produksi.stok-barang.kategori.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        // Menyimpan kategori ke database
        $category = categoriesProduct::create([
            'name' => $validatedData['name']
        ]);

        // Mengembalikan response jika berhasil
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
