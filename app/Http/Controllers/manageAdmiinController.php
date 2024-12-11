<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
                ->addColumn('updated_at', function ($row) {
                    return Carbon::parse($row->updated_at)->format('d M Y');
                })
                ->addColumn('roles', function ($user) {
                    // Cek apakah user memiliki role 'admin', 'supervisor', atau 'produksi'
                    $roles = $user->roles->pluck('name'); // Mengambil semua nama role dari user

                    $roleBadges = '';

                    // Memeriksa setiap role dan memberikan badge sesuai role yang dimiliki
                    if ($roles->contains('admin')) {
                        $roleBadges .= '<span class="badge bg-primary text-white">Admin</span> ';
                    }
                    if ($roles->contains('supervisor')) {
                        $roleBadges .= '<span class="badge bg-warning text-white">Supervisor</span> ';
                    }
                    if ($roles->contains('produksi')) {
                        $roleBadges .= '<span class="badge bg-green text-white">Produksi</span> ';
                    }

                    // Jika tidak ada role yang cocok
                    if ($roleBadges === '') {
                        $roleBadges = 'No roles assigned';
                    }

                    return $roleBadges;
                })
                ->addColumn('actions', function ($row) {
                    return view('components.button.action-btn', [
                        'edit' => route('kelola-admin.edit', $row->id),
                        'delete' => route('kelola-admin.destroy', $row->id),
                    ])->render();
                })
                ->rawColumns(['roles', 'actions', 'updated_at'])
                ->make(true);
        }

        return view('v-supervisor.data-admin.index', compact('title', 'button'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Data Pengguna';
        $backUrl = url()->previous();
        $roles = Role::all(); // Ambil semua role
        return view('v-supervisor.data-admin.create', compact('title', 'backUrl', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('kelola-admin.index')->with('success', 'Data pengguna berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = 'Edit Data Pengguna';
        $backUrl = url()->previous();
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('v-supervisor.data-admin.edit', compact('title', 'backUrl', 'user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->username = $request->username;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $user->syncRoles([$request->role]);

        return redirect()->route('kelola-admin.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with(['success' => 'Data pengguna berhasil dihapus.']);
    }
}
