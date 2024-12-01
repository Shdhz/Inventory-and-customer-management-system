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
        if (request()->ajax()) {
            $data = DraftCustomer::with('user')->where('user_id', Auth::id())->get();
            return DataTables::of($data)
                ->addColumn('actions', function ($row) {
                    return view('components.button.action-btn', [
                        'edit' => route('draft-customer.edit', $row->draft_customers_id),
                        'delete' => route('draft-customer.destroy', $row->draft_customers_id),
                    ])->render();
                })->rawColumns(['actions'])->make(true);
        }
        return view('v-admin.draft_customers.index');
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
        $validatedData = $request->validate([
            'Nama' => 'required|min:5|max:50',
            'no_hp' => 'required|numeric',
            'email' => 'nullable|email',
            'provinsi' => 'nullable|string',
            'kota' => 'nullable|string',
            'alamat_lengkap' => 'nullable|string',
            'sumber' => 'required',
        ]);

        // Tambahkan user_id secara manual
        $validatedData['user_id'] = Auth::id();
        DraftCustomer::create($validatedData);
        return redirect()->route('draft-customer.index')->with('success', 'Draft customer berhasil ditambahkan!');
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
        $title = 'Edit draft customer';
        $backUrl = Route('draft-customer.index');

        // Ambil data berdasarkan draft_customer_id dan validasi user_id
        $id = DraftCustomer::with('user')
            ->where('draft_customers_id', $id)->where('user_id', Auth::id()) // Validasi hanya data milik user yang sedang login
            ->firstOrFail();
        // Pass data ke view
        return view('v-admin.draft_customers.edit', compact('title', 'backUrl', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'Nama' => 'required|min:5|max:50',
            'no_hp' => 'required|numeric',
            'email' => 'nullable|email',
            'provinsi' => 'nullable|string',
            'kota' => 'nullable|string',
            'alamat_lengkap' => 'nullable|string',
            'sumber' => 'required',
        ]);
        // dd($validatedData);
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
