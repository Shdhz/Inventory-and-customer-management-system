<?php

namespace App\Http\Controllers\stok;

use App\Http\Controllers\Controller;
use App\Models\productStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class stokBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Stok Barang';
        if ($request->ajax()) {
            $customerOrders = productStock::with('category')->get();

            return DataTables::of($customerOrders)
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
                ->addColumn('actions', function ($row) {
                    return view('components.button.action-btn', [
                        'edit' => route('stok-barang.edit', $row->id_stok),
                        'delete' => route('stok-barang.destroy', $row->id_stok),
                    ])->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('v-produksi.stok-barang.stok.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
