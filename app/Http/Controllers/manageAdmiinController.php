<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class manageAdmiinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Kelola Data Pengguna';
        $button = 'Tambah Data Pengguna';
        // Cek jika request berasal dari DataTables
        if ($request->ajax()) {
            $users = User::with('roles');

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('roles', function ($user) {
                    return $user->roles->map(function ($role) {
                        return '<span class="badge bg-primary text-white">' . $role->name . '</span>';
                    })->implode(' '); // Gabungkan badges dengan spasi
                })
                ->addColumn('actions', function ($row) {
                    return view('components.button.action-btn', [
                        'edit' => route('stok-barang.edit', $row->id),
                        'delete' => route('stok-barang.destroy', $row->id),
                    ])->render();
                })
                ->rawColumns(['roles', 'actions'])
                ->make(true); // Buat response DataTables
        }

        return view('v-supervisor.data-admin.index', compact('title', 'button'));
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
