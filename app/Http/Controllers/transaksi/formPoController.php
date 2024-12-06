<?php

namespace App\Http\Controllers\transaksi;

use App\Http\Controllers\Controller;
use App\Models\categoriesProduct;
use App\Models\CustomerOrder;
use App\Models\DraftCustomer;
use App\Models\formPo;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class formPoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = formPo::with(['customerOrder', 'category'])->get();

            return DataTables::of($data)
                ->addColumn('model', function ($row) {
                    return $row->model
                        ? '<img src="' . asset('storage/uploads/stok-barang/' . $row->model) . '" alt="Foto Produk" width="50">'
                        : 'N/A';
                })
                ->addColumn('status_form_po', function ($row) {
                    return $row->status_form_po
                        ? '<span class="badge bg-success text-white">Active</span>'
                        : '<span class="badge bg-danger text-white">Inactive</span>';
                })
                ->addColumn('actions', function ($row) {
                    if (auth()->user()->hasRole('supervisor')) {
                        // Dropdown untuk supervisor
                        $statusOptions = '
                            <select class="form-select form-select-sm update-status" data-id="' . $row->id_form_po . '">
                                <option value="1" ' . ($row->status_form_po ? 'selected' : '') . '>Active</option>
                                <option value="0" ' . (!$row->status_form_po ? 'selected' : '') . '>Inactive</option>
                            </select>';
                        return $statusOptions;
                    } elseif (auth()->user()->hasRole('admin')) {
                        // Tombol aksi untuk admin
                        return view('components.button.action-btn', [
                            'edit' => route('form-po.edit', $row->id_form_po),
                            'delete' => route('form-po.destroy', $row->id_form_po),
                        ])->render();
                    }
                    return ''; // Jika bukan admin atau supervisor, kosongkan aksi
                })
                ->rawColumns(['model', 'status_form_po', 'actions'])
                ->make(true);
        }
        return view('transaksi.form-po.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $backUrl = url()->previous();
        $customers = CustomerOrder::with('draftCustomer')
            ->where('jenis_order', 'pre order')
            ->get();

        foreach ($customers as $customer) {
            $customer->sumber = $customer->draftCustomer->sumber ?? 'Tidak Diketahui';
        }

        $categories = categoriesProduct::all(); // Pastikan model Category sudah ada
        return view('transaksi.form-po.create', compact('backUrl', 'customers', 'categories'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_order_id' => 'required|exists:tb_customer_orders,customer_order_id',
            'kategori_id' => 'required|exists:tb_categories_products,id_kategori',
            'model' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'qty' => 'required|integer|min:1',
            'ukuran' => 'required|string|max:255',
            'warna' => 'required|string|max:255',
            'bahan' => 'required|string|max:255',
            'aksesoris' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'metode_pembayaran' => 'required|in:cod,transfer',
        ]);

        // Handle file upload
        if ($request->hasFile('model')) {
            $file = $request->file('model');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('uploads/stok-barang', $fileName, 'public');
            $data['model'] = $fileName;
        }

        // Save data
        formPo::create([
            'customer_order_id' => $request->customer_order_id,
            'kategori_id' => $request->kategori_id,
            'model' => $fileName,
            'qty' => $request->qty,
            'ukuran' => $request->ukuran,
            'warna' => $request->warna,
            'bahan' => $request->bahan,
            'aksesoris' => $request->aksesoris,
            'keterangan' => $request->keterangan,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status_form_po' => false, // Default active
        ]);
        return redirect()->route('form-po.index')->with('success', 'Form PO berhasil ditambahkan.');
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
        $formPo = formPo::findOrFail($id);

        // Hapus file gambar jika ada
        if ($formPo->model) {
            $filePath = public_path('storage/uploads/stok-barang/' . $formPo->model);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Hapus data dari database
        $formPo->delete();

        return back()->with('success', 'Form Po berhasil dihapus.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $formPo = formPo::findOrFail($id);
        $formPo->status_form_po = $request->status;
        $formPo->save();

        return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui.']);
    }
}
