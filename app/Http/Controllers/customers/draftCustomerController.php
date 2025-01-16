<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use App\Models\DraftCustomer;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class draftCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $button = "Tambah draft customer";
        if (request()->ajax()) {
            $data = DraftCustomer::with('user')
                ->when(Auth::user()->hasRole('admin'), function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->get();
                
            return DataTables::of($data)
                ->addColumn('actions', function ($row) {
                    return view('components.button.action-btn', [
                        'edit' => route('draft-customer.edit', $row->draft_customers_id),
                        'delete' => route('draft-customer.destroy', $row->draft_customers_id),
                    ])->render();
                })->rawColumns(['actions'])->make(true);
        }
        return view('v-admin.draft_customers.index', compact('button'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah draft customer';
        $backUrl = Route('draft-customer.index');

        return view('v-admin.draft_customers.create', compact('title', 'backUrl'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'Nama' => 'required|min:5|max:50',
                'no_hp' => 'required|numeric',
                'email' => 'nullable|email',
                'provinsi' => 'nullable|string',
                'kota' => 'nullable|string',
                'alamat_lengkap' => 'nullable|string',
                'sumber' => ['required', 'regex:/^(shopee|tokopedia|lazada|tiktok shop|tiktok|whatsapp|instagram|facebook)$/i'],
            ],
            [
                'Nama.required' => 'Nama wajib diisi.',
                'Nama.min' => 'Nama harus memiliki minimal 5 karakter.',
                'Nama.max' => 'Nama tidak boleh lebih dari 50 karakter.',
                'no_hp.required' => 'Nomor HP wajib diisi.',
                'no_hp.numeric' => 'Nomor HP hanya boleh berisi angka.',
                'email.email' => 'Format email yang Anda masukkan tidak valid.',
                'provinsi.string' => 'Provinsi harus berupa teks.',
                'kota.string' => 'Kota harus berupa teks.',
                'alamat_lengkap.string' => 'Alamat lengkap harus berupa teks.',
                'sumber.required' => 'Sumber wajib dipilih.',
                'sumber.regex' => 'Sumber tidak valid. Pilih salah satu dari sumber dibawah ini.',
            ]
        );

        // Tambahkan user_id secara manual
        $validatedData['user_id'] = Auth::id();
        DraftCustomer::create($validatedData);
        return redirect()->route('draft-customer.index')->with('success', 'Draft customer berhasil ditambahkan!');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = 'Edit draft customer';
        $backUrl = Route('draft-customer.index');

        $id = DraftCustomer::with('user')
            ->when(Auth::user()->hasRole('admin'), function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('draft_customers_id', $id)
            ->firstOrFail();

        // Pass data ke view
        return view('v-admin.draft_customers.edit', compact('title', 'backUrl', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate(
            [
                'Nama' => 'required|min:5|max:50',
                'no_hp' => 'required|numeric',
                'email' => 'nullable|email',
                'provinsi' => 'nullable|string',
                'kota' => 'nullable|string',
                'alamat_lengkap' => 'nullable|string',
                'sumber' => ['required', 'regex:/^(shopee|tokopedia|lazada|tiktok shop|tiktok|whatsapp|instagram|facebook)$/i'],
            ],
            [
                'Nama.required' => 'Nama wajib diisi.',
                'Nama.min' => 'Nama harus memiliki minimal 5 karakter.',
                'Nama.max' => 'Nama tidak boleh lebih dari 50 karakter.',
                'no_hp.required' => 'Nomor HP wajib diisi.',
                'no_hp.numeric' => 'Nomor HP hanya boleh berisi angka.',
                'email.email' => 'Format email yang Anda masukkan tidak valid.',
                'provinsi.string' => 'Provinsi harus berupa teks.',
                'kota.string' => 'Kota harus berupa teks.',
                'alamat_lengkap.string' => 'Alamat lengkap harus berupa teks.',
                'sumber.required' => 'Sumber wajib dipilih.',
                'sumber.regex' => 'Sumber tidak valid. Pilih salah satu dari sumber dibawah ini.',
            ]
        );

        $draftCustomer = DraftCustomer::findOrFail($id);
        $draftCustomer->update($validatedData);

        // Redirect ke halaman index dengan pesan keberhasilan
        return redirect()->route('draft-customer.index')->with('success', 'Draft customer berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $draftCustomer = DraftCustomer::findOrFail($id);
        $draftCustomer->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}
