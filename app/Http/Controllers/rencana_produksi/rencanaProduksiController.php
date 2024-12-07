<?php

namespace App\Http\Controllers\rencana_produksi;

use App\Http\Controllers\Controller;
use App\Models\formPo;
use App\Models\rencanaProduksi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class rencanaProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = rencanaProduksi::with([
                'formPo.customerOrder.draftCustomer' => function ($queryDraft) {
                    $queryDraft->where('user_id', auth()->id());
                }
            ])->get();
        
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nomor_po', function ($row) {
                    return $row->formPo->id_form_po ?? 'Tidak Ada';
                })
                ->addColumn('nama_barang', function ($row) {
                    return $row->formPo->keterangan ?? 'Tidak Ada';
                })
                ->addColumn('actions', function ($row) {
                    if (auth()->user()->hasRole('produksi')) {
                        return view('components.button.action-btn', [
                            'edit' => route('rencana-produksi.edit', $row->id_rencana_produksi),
                            'delete' => route('rencana-produksi.destroy', $row->id_rencana_produksi),
                        ])->render();
                    }
                    return '-';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $canAdd = auth()->user()->hasRole('produksi');

        return view('v-produksi.rencana-produksi.index', [
            'title' => 'Kelola Rencana Produksi',
            'button' => $canAdd ? 'Tambah Rencana Produksi' : null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $backUrl = url()->previous();
        $formPo = FormPo::where('status_form_po', true)->get();

        return view('v-produksi.rencana-produksi.create', compact('formPo', 'backUrl'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'form_po_id' => 'required|exists:tb_form_po,id_form_po',
            'prioritas' => 'required|in:high,medium,low',
            'mulai_produksi' => 'required|date|after_or_equal:today',
            'berakhir_produksi' => 'required|date|after_or_equal:mulai_produksi',
            'status' => 'required|in:produksi,selesai',
            'nama_pengrajin' => 'required|string|max:255',
        ], [
            'form_po_id.required' => 'Form PO wajib dipilih.',
            'form_po_id.exists' => 'Form PO yang dipilih tidak valid.',
            'prioritas.required' => 'Prioritas PO wajib dipilih.',
            'prioritas.in' => 'Prioritas PO hanya boleh High, Medium, atau Low.',
            'mulai_produksi.required' => 'Tanggal mulai wajib diisi.',
            'mulai_produksi.after_or_equal' => 'Tanggal mulai tidak boleh kurang dari hari ini.',
            'berakhir_produksi.required' => 'Tanggal selesai wajib diisi.',
            'berakhir_produksi.after_or_equal' => 'Tanggal selesai tidak boleh kurang dari tanggal mulai.',
            'status.required' => 'Status produksi wajib diisi.',
            'status.in' => 'Status produksi hanya boleh Produksi atau Selesai.',
            'nama_pengrajin.required' => 'Nama pengrajin wajib diisi.',
            'nama_pengrajin.max' => 'Nama pengrajin tidak boleh lebih dari 255 karakter.',
        ]);

        // Mapping prioritas
        $prioritasMapping = [
            'high' => 1,
            'medium' => 2,
            'low' => 3,
        ];

        // dd($request->all());

        try {
            // Simpan data ke dalam database
            $rencanaProduksi = new RencanaProduksi();
            $rencanaProduksi->form_po_id = $validated['form_po_id'];
            $rencanaProduksi->prioritas = $prioritasMapping[$validated['prioritas']];
            $rencanaProduksi->mulai_produksi = $validated['mulai_produksi'];
            $rencanaProduksi->berakhir_produksi = $validated['berakhir_produksi'];
            $rencanaProduksi->status = $validated['status'];
            $rencanaProduksi->nama_pengrajin = $validated['nama_pengrajin']; // Menyimpan nama pengrajin
            $rencanaProduksi->save();

            // Redirect dengan pesan sukses
            return redirect()
                ->route('rencana-produksi.index')
                ->with('success', 'Rencana produksi berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Handle error
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])
                ->withInput();
        }
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
