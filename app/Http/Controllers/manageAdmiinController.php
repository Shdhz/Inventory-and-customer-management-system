<?php

namespace App\Http\Controllers;

use App\Models\instagramForAdmin;
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

        if ($request->ajax()) {
            $users = User::with('roles', 'instagramForAdmin');

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('updated_at', function ($row) {
                    return Carbon::parse($row->updated_at)->format('d M Y');
                })
                ->addColumn('no_hp', function ($user) {
                    return $user->no_hp ?: '-';
                })
                ->addColumn('nama_instagram', function ($user) {
                    $instagramList = $user->instagramForAdmin->pluck('nama_instagram');
                    if ($instagramList->isEmpty()) {
                        return '-';
                    }
                    return $instagramList->map(function ($ig) {
                        return "<span class='badge bg-info text-white'>{$ig}</span>";
                    })->implode(' '); 
                })
                ->addColumn('roles', function ($user) {
                    $roles = $user->roles->pluck('name'); 
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
                ->rawColumns(['roles', 'actions', 'updated_at', 'nama_instagram'])
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
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'password' => [
                    'nullable',
                    'string',
                    'min:6',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/'
                ],
                'role' => 'required|exists:roles,name',
                'no_hp' => 'numeric|digits_between:10,13',
                'instagram.*' => 'required|string|max:255'
            ],
            [
                'name.required' => 'Nama lengkap wajib diisi.',
                'username.required' => 'Username wajib diisi.',
                'username.unique' => 'Username sudah digunakan.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password harus minimal 6 karakter.',
                'password.regex' => 'Password harus mengandung minimal 1 huruf besar, 1 huruf kecil, 1 angka, dan 1 karakter khusus.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'role.required' => 'Role wajib dipilih.',
                'role.exists' => 'Role yang dipilih tidak valid.',
                'no_hp.numeric' => 'Nomor HP harus berupa angka.',
                'no_hp.digits_between' => 'Nomor HP harus memiliki panjang antara 10 hingga 13 digit.',
                'instagram.*' => 'Instagram harus berupa string dengan panjang maksimal 255 karakter.',
                'instagram.required' => 'Instagram harus diisi.'
            ]
        );

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        if ($request->has('instagram')) {
            foreach ($request->instagram as $instagram) {
                if (!empty($instagram)) {
                    instagramForAdmin::create([
                        'id_user' => $user->id,
                        'nama_instagram' => $instagram,
                    ]);
                }
            }
        }

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
        $instagram = instagramForAdmin::where('id_user', $id)->pluck('nama_instagram')->toArray();
        return view('v-supervisor.data-admin.edit', compact('title', 'backUrl', 'user', 'roles', 'instagram'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $id,
                'password' => [
                    'nullable',
                    'string',
                    'min:6',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/'
                ],
                'role' => 'required|exists:roles,name',
                'instagram.*' => 'required|string|max:255'
            ],
            [
                'name.required' => 'Nama lengkap wajib diisi.',
                'username.required' => 'Username wajib diisi.',
                'username.unique' => 'Username sudah digunakan, silahkan gunakan username.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password harus minimal 6 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'password.regex' => 'Password harus mengandung minimal 1 huruf besar, 1 huruf kecil, 1 angka, dan 1 karakter khusus.',
                'role.required' => 'Role wajib dipilih.',
                'role.exists' => 'Role yang dipilih tidak valid.',
                'instagram.*' => 'Instagram harus berupa string dengan panjang maksimal 255 karakter.',
                'instagram.required' => 'Instagram harus diisi.'
            ]
        );

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->username = $request->username;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $user->syncRoles([$request->role]);

        instagramForAdmin::where('id_user', $id)->delete();

        // Menyimpan Instagram yang baru
        if ($request->has('instagram')) {
            foreach ($request->instagram as $instagram) {
                if (!empty($instagram)) {
                    instagramForAdmin::create([
                        'id_user' => $user->id,
                        'nama_instagram' => $instagram,
                    ]);
                }
            }
        }

        return redirect()->route('kelola-admin.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        instagramForAdmin::where('id_user', $id)->delete();
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with(['success' => 'Data pengguna berhasil dihapus.']);
    }
}
