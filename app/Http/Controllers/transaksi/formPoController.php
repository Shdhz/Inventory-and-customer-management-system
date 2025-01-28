<?php

namespace App\Http\Controllers\transaksi;

use App\Http\Controllers\Controller;
use App\Models\categoriesProduct;
use App\Models\CustomerOrder;
use App\Models\formPo;
use App\Models\modelsFormpo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class formPoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->hasRole('supervisor')) {
                $data = formPo::with(['customerOrder.draftCustomer.user', 'category', 'modelsFormpo'])->get();
            } else {
                $data = formPo::with(['customerOrder.draftCustomer.user', 'category', 'modelsFormpo'])
                    ->whereHas('customerOrder.draftCustomer.user', function ($query) {
                        $query->where('user_id', Auth::user()->id);
                    })->get();
            }
            return DataTables::of($data)
                ->addColumn('model', function ($row) {
                    $models = $row->modelsFormpo;
                    $html = '';
                    $limit = Auth::user()->hasRole('admin') ? 3 : PHP_INT_MAX;
                    $count = 0;

                    foreach ($models as $model) {
                        if ($count >= $limit) {
                            break;
                        }
                        $html .= '<img src="' . asset('storage/uploads/stok-barang/' . $model->model) . '" alt="Foto Produk" width="50" style="margin: 5px;">';
                        $count++;
                    }

                    if ($models->count() > $limit) {
                        $html .= '<span style="font-weight: bold;">+' . ($models->count() - $limit) . '</span>';
                    }

                    return $html ?: 'N/A';
                })
                ->addColumn('po_admin', function ($row) {
                    return $row->customerOrder && $row->customerOrder->draftCustomer && $row->customerOrder->draftCustomer->user
                        ? $row->customerOrder->draftCustomer->user->name
                        : 'N/A';
                })
                ->addColumn('nama_customer', function ($row) {
                    return $row->customerOrder && $row->customerOrder->draftCustomer && $row->customerOrder->draftCustomer->Nama
                        ? $row->customerOrder->draftCustomer->Nama
                        : 'N/A';
                })
                ->addColumn('status_form_po', function ($row) {
                    return $row->status_form_po
                        ? '<span class="badge bg-success text-white">Active</span>'
                        : '<span class="badge bg-danger text-white">Inactive</span>';
                })
                ->addColumn('actions', function ($row) {
                    if (Auth::user()->hasRole('supervisor')) {
                        // Dropdown untuk supervisor
                        $statusOptions = '
                        <select class="form-select form-select-sm update-status" 
                                data-id="' . $row->id_form_po . '" 
                                style="width: auto; min-width: 90px;">
                            <option value="1" ' . ($row->status_form_po ? 'selected' : '') . '>Active</option>
                            <option value="0" ' . (!$row->status_form_po ? 'selected' : '') . '>Inactive</option>
                        </select>';
                        return $statusOptions;
                    } elseif (Auth::user()->hasRole('admin')) {
                        // Tombol aksi untuk admin
                        return view('components.button.action-btn', [
                            'edit' => route('form-po.edit', $row->id_form_po),
                            'delete' => route('form-po.destroy', $row->id_form_po),
                            'show' => route('form-po.show', $row->id_form_po),
                        ])->render();
                    }
                    return '';
                })
                ->rawColumns(['model', 'status_form_po', 'actions', 'po_admin', 'nama_customer'])
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
            ->whereHas('draftCustomer.user', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })->get();

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
            'model' => 'nullable|array',
            'model.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'qty' => 'required|integer|min:1',
            'ukuran' => 'required|string|max:255',
            'warna' => 'required|string|max:255',
            'bahan' => 'required|string|max:255',
            'aksesoris' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'metode_pembayaran' => 'required|in:cod,transfer',
        ]);

        $formPo = formPo::create([
            'customer_order_id' => $request->customer_order_id,
            'kategori_id' => $request->kategori_id,
            'qty' => $request->qty,
            'ukuran' => $request->ukuran,
            'warna' => $request->warna,
            'bahan' => $request->bahan,
            'aksesoris' => $request->aksesoris,
            'keterangan' => $request->keterangan,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status_form_po' => false,
        ]);

        if ($request->hasFile('model')) {
            foreach ($request->file('model') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('uploads/stok-barang', $fileName, 'public');

                modelsFormpo::create([
                    'id_form_po' => $formPo->id_form_po,
                    'model' => $fileName,
                ]);
            }
        }

        return redirect()->route('form-po.index')->with('success', 'Form PO berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $formPo = FormPo::with('modelsFormpo')->findOrFail($id);
        $backUrl = url()->previous();

        $models = $formPo->modelsFormpo;
        return view('transaksi.form-po.show', compact('formPo', 'models', 'backUrl'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $formPo = FormPo::with('modelsFormpo')->findOrFail($id);

        $backUrl = route('form-po.index');
        $customers = CustomerOrder::with('draftCustomer')
            ->whereHas('draftCustomer.user', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->where('jenis_order', 'pre order')
            ->get();
        $categories = categoriesProduct::all();

        return view('transaksi.form-po.edit', compact('formPo', 'customers', 'categories', 'backUrl'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'customer_order_id' => 'required|exists:tb_customer_orders,customer_order_id',
            'kategori_id' => 'required|exists:tb_categories_products,id_kategori',
            'model' => 'nullable|array',
            'model.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'qty' => 'required|integer|min:1',
            'ukuran' => 'required|string|max:255',
            'warna' => 'required|string|max:255',
            'bahan' => 'required|string|max:255',
            'aksesoris' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'metode_pembayaran' => 'required|in:cod,transfer',
        ]);

        // dd($request->all());
        // Hapus gambar yang dipilih
        if ($request->has('deleted_models')) {
            foreach ($request->deleted_models as $modelId) {
                $model = modelsFormpo::find($modelId);
                if ($model) {
                    Storage::disk('public')->delete('uploads/stok-barang/' . $model->model);
                    $model->delete();
                }
            }
        }

        $formPo = FormPo::findOrFail($id);
        $formPo->update([
            'customer_order_id' => $request->customer_order_id,
            'kategori_id' => $request->kategori_id,
            'qty' => $request->qty,
            'ukuran' => $request->ukuran,
            'warna' => $request->warna,
            'bahan' => $request->bahan,
            'aksesoris' => $request->aksesoris,
            'keterangan' => $request->keterangan,
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        // Handle upload gambar baru 
        if ($request->hasFile('model')) {
            foreach ($request->file('model') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('uploads/stok-barang', $fileName, 'public');

                modelsFormpo::create([
                    'id_form_po' => $formPo->id_form_po,
                    'model' => $fileName,
                ]);
            }
        }

        return redirect()->route('form-po.index')->with('success', 'Form PO berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $formPo = FormPo::findOrFail($id);

        // Hapus semua gambar yang terkait dengan FormPo
        if ($formPo->modelsFormpo->isNotEmpty()) {
            foreach ($formPo->modelsFormpo as $model) {
                Storage::disk('public')->delete('uploads/stok-barang/' . $model->model);
                $model->delete();
            }
        }

        // Hapus data FormPo dari database
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
