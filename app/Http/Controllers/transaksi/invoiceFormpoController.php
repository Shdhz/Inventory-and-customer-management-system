<?php

namespace App\Http\Controllers\transaksi;

use App\Http\Controllers\Controller;
use App\Models\formPo;
use App\Models\invoice;
use App\Models\invoiceFormPo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class invoiceFormpoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = "Kelola Invoice Form PO";

        if ($request->ajax()) {
            // Ambil data invoice form PO dengan relasi yang dibutuhkan
            $data = InvoiceFormPO::with([
                'invoice',
                'formPO.customerOrder.draftCustomer'
            ])
                ->whereHas('formPO.customerOrder.draftCustomer', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->get();

            // Kelompokkan data berdasarkan nama customer
            $groupedData = $data->groupBy(function ($item) {
                return $item->formPO->customerOrder->draftCustomer->Nama ?? '-';
            });

            // Ambil hanya data pertama dari setiap grup
            $uniqueData = $groupedData->map(function ($items) {
                return $items->first();
            });

            return DataTables::of($uniqueData)
                ->addColumn('customer_name', function ($row) {
                    return $row->formPO->customerOrder->draftCustomer->Nama ?? '-';
                })
                ->addColumn('nota_no', function ($row) {
                    return $row->invoice->nota_no ?? 'N/A';
                })
                ->addColumn('subtotal', function ($row) {
                    return 'Rp.' . number_format($row->invoice->subtotal ?? 0, 0, ',', '.');
                })
                ->addColumn('nama_produk', function ($row) {
                    // Gabungkan nama produk dari setiap invoice_detail
                    $produkNames = $row->invoice
                        ->invoiceFormPo
                        ->map(function ($detail) {
                            return $detail->formPo->keterangan ?? '-';
                        })
                        ->toArray();

                    return implode(',<br>', $produkNames);
                })
                ->addColumn('ongkir', function ($row) {
                    return 'Rp.' . number_format($row->invoice->ongkir ?? 0, 0, ',', '.');
                })
                ->addColumn('total', function ($row) {
                    return 'Rp.' . number_format($row->invoice->total ?? 0, 0, ',', '.');
                })
                ->addColumn('dp', function ($row) {
                    return 'Rp.' . number_format($row->invoice->down_payment ?? 0, 0, ',', '.');
                })
                ->addColumn('status_pembayaran', function ($row) {
                    $status = ucfirst($row->invoice->status_pembayaran ?? 'N/A');

                    // Menentukan badge berdasarkan status pembayaran
                    if ($status === 'Lunas') {
                        return '<span class="badge bg-primary text-white">' . $status . '</span>';
                    } elseif ($status === 'Belum lunas') {
                        return '<span class="badge bg-danger text-white">' . $status . '</span>';
                    } else {
                        return '<span class="badge bg-secondary text-white">' . $status . '</span>';
                    }
                })
                ->addColumn('tenggat_invoice', function ($row) {
                    return $row->invoice->tenggat_invoice
                        ? \Carbon\Carbon::parse($row->invoice->tenggat_invoice)->format('d F Y')
                        : '-';
                })
                ->addColumn('actions', function ($row) {
                    return view('components.button.inv-actionbtn', [
                        'edit' => route('form-po-invoice.edit', $row->invoice->invoice_id),
                        'delete' => route('form-po-invoice.destroy', $row->invoice->invoice_id),
                        'show' => route('form-po-invoice.show', $row->invoice->invoice_id)
                    ])->render();
                })
                ->rawColumns(['actions', 'nama_produk', 'status_pembayaran'])
                ->make(true);
        }

        return view('transaksi.invoice.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $backUrl = url()->previous();
        $title = 'Tambah Invoice';

        $formPoQuery = formPo::with(['customerOrder.draftCustomer', 'invoiceFormPo'])
            ->where('status_form_po', true)
            ->whereHas('customerOrder.draftCustomer', function ($query) {
                $query->where('user_id', Auth::id());
            });

        if ($request->has('customer_id')) {
            $formPoQuery->whereHas('customerOrder.draftCustomer', function ($query) use ($request) {
                $query->where('id', $request->customer_id);
            });
        }

        $formPo = $formPoQuery->get()->groupBy('customer_order_id')->map(function ($items) {
            $firstItem = $items->first();

            $invoiceFormPo = $firstItem->invoiceFormPo;

            if ($invoiceFormPo && $invoiceFormPo->isEmpty()) {
                return [
                    'customer_order_id' => $firstItem->id_form_po,
                    'customer_name' => $firstItem->customerOrder->draftCustomer->Nama ?? 'Nama Tidak Ditemukan',
                    'data' => $items->map(function ($item) {
                        return [
                            'form_po_id' => $item->id_form_po,
                            'keterangan' => $item->keterangan,
                            'qty' => $item->qty,
                        ];
                    }),
                ];
            }

            return null;
        })->filter()->values();
        // dd($formPo);

        return view('transaksi.invoice.pre_order.create', compact('backUrl', 'title', 'formPo'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nota_no' => 'required|string|max:255',
            'tenggat_invoice' => 'required|date|after_or_equal:today',
            'nama_pelanggan' => 'required|array',
            'nama_pelanggan.*' => 'required|exists:tb_form_po,id_form_po',
            'qty.*' => 'required|integer|min:1',
            'harga.*' => 'required|numeric|min:1',
            'ongkir' => 'required|numeric|min:0',
            'dp' => 'required|numeric|between:0,100',
            'form_po_id' => 'required|array',
            'form_po_id.*' => 'required|exists:tb_form_po,id_form_po',
        ], [
            // Pesan untuk 'nota_no'
            'nota_no.required' => 'Nomor nota wajib diisi.',
            'nota_no.string' => 'Nomor nota harus berupa teks.',
            'nota_no.max' => 'Nomor nota maksimal 255 karakter.',
            'tenggat_invoice.required' => 'Tenggat waktu wajib diisi.',
            'tenggat_invoice.date' => 'Tenggat waktu harus berupa tanggal yang valid.',
            'tenggat_invoice.after_or_equal' => 'Tenggat waktu tidak boleh lebih kecil dari tanggal hari ini.',
            'nama_pelanggan.required' => 'Nama pelanggan wajib dipilih.',
            'nama_pelanggan.array' => 'Nama pelanggan harus berupa array.',
            'nama_pelanggan.*.exists' => 'Nama pelanggan tidak valid, silakan pilih nama yang sesuai.',
            'Nama_Barang.*.required' => 'Nama barang wajib diisi.',
            'Nama_Barang.*.string' => 'Nama barang harus berupa teks.',
            'Nama_Barang.*.max' => 'Nama barang maksimal 255 karakter.',
            'qty.*.required' => 'Jumlah barang wajib diisi.',
            'qty.*.integer' => 'Jumlah barang harus berupa angka.',
            'qty.*.min' => 'Jumlah barang harus lebih besar dari 0.',
            'harga.*.required' => 'Harga barang wajib diisi.',
            'harga.*.numeric' => 'Harga barang harus berupa angka.',
            'harga.*.min' => 'Harga barang harus lebih besar dari 0.',
            'ongkir.required' => 'Biaya ongkir wajib diisi.',
            'ongkir.numeric' => 'Biaya ongkir harus berupa angka.',
            'ongkir.min' => 'Biaya ongkir tidak boleh kurang dari 0.',
            'dp.required' => 'Jumlah Down Payment (DP) wajib diisi.',
            'dp.numeric' => 'Down Payment (DP) harus berupa angka.',
            'dp.between' => 'Down Payment (DP) harus antara 0 dan 100 persen.',
            'form_po_id.distinct' => 'Terdapat duplikasi pada Form PO ID.',
            'form_po_id.*.exists' => 'Form PO ID tidak valid.',
            'form_po_id.*.required' => 'ID Form PO wajib diisi.',
            'form_po_id.*.exists' => 'ID Form PO tidak valid.',
        ]);

        $subtotal = 0;
        $totalQty = 0;

        foreach ($validatedData['qty'] as $index => $qty) {
            $harga = $validatedData['harga'][$index];
            $subtotal += $qty * $harga;
            $totalQty += $qty;
        }
        $subtotal += $validatedData['ongkir'];

        // Perhitungan total: dp - subtotal
        $downPayment = ($validatedData['dp'] / 100) * $subtotal;
        $total = $subtotal - $downPayment;

        $statusPembayaran = $downPayment >= $subtotal ? 'Lunas' : 'Belum Lunas';

        // dd($request->all());
        DB::beginTransaction();

        try {
            // Simpan data ke tabel tb_invoice
            $invoice = Invoice::create([
                'nota_no' => $validatedData['nota_no'],
                'status_pembayaran' => $statusPembayaran,
                'subtotal' => $subtotal,
                'jumlah' => $totalQty,
                'ongkir' => $validatedData['ongkir'],
                'down_payment' => $downPayment,
                'total' => $total,
                'tenggat_invoice' => $validatedData['tenggat_invoice'],
            ]);


            $uniqueFormPoIds = array_unique($validatedData['form_po_id']);

            foreach ($uniqueFormPoIds as $index => $formPoId) {
                $qty = $validatedData['qty'][$index];
                $hargaSatuan = $validatedData['harga'][$index];
                $subtotalHargaSatuan = $qty * $hargaSatuan;

                invoiceFormPo::create([
                    'invoice_id' => $invoice->invoice_id,
                    'form_po_id' => $formPoId,
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $subtotalHargaSatuan,
                ]);
            }
            // Commit transaksi
            DB::commit();

            return redirect()->route('kelola-invoice.index')->with('success', 'Invoice berhasil dibuat');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();
            return back()->with('message', 'Gagal menyimpan data invoice po.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoice = invoice::with([
            'invoiceFormPo.formPo.customerOrder.draftCustomer',
            'invoiceFormPo.formPo',
        ])->findOrFail($id);

        $invoiceFormPo = $invoice->invoiceFormPo;

        $title = 'Detail Invoice';
        $backUrl = url()->previous();

        return view('transaksi.invoice.pre_order.show', compact('invoice', 'invoiceFormPo', 'title', 'backUrl'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $title = 'Edit Invoice';
        $backUrl = url()->previous();

        $invoice = Invoice::with([
            'invoiceFormPo.formPo.customerOrder.draftCustomer',
            'invoiceFormPo.formPo.products',
        ])->findOrFail($id);

        $invoiceFormPo = $invoice->invoiceFormPo;

        // Mengambil nama pelanggan dari item pertama
        $firstItem = $invoiceFormPo->first();
        $namaPelanggan = optional($firstItem?->formPo?->customerOrder?->draftCustomer)->Nama ?? 'Tidak ada nama';

        $detail_produk = $invoiceFormPo->map(function ($item) {
            return [
                'form_po_id' => $item->form_po_id ?? null,
                'keterangan' => $item->formPo->keterangan ?? 'Tidak ada keterangan',
                'qty' => $item->formPo->qty ?? 0,
                'harga_satuan' => $item->harga_satuan ?? 0,
            ];
        });

        $subtotal = $invoice->subtotal ?? 0;
        $dpReal = $invoice->down_payment ?? 0;
        $dpPersen = $subtotal > 0 ? ($dpReal / $subtotal) * 100 : 0;

        // Kirim data ke view
        return view('transaksi.invoice.pre_order.edit', compact('title', 'backUrl', 'invoice', 'namaPelanggan', 'detail_produk', 'dpPersen'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $validatedData = $request->validate([
            'nota_no' => 'required|string|max:255',
            'tenggat_invoice' => 'required|date|after_or_equal:today',
            'nama_pelanggan' => 'required|string',
            'keterangan.*' => 'required|string|max:255',
            'qty.*' => 'required|integer|min:1',
            'harga_satuan.*' => 'required|numeric|min:1',
            'ongkir' => 'required|numeric|min:0',
            'dp' => 'required|numeric|min:0|max:100',
            'form_po_id' => 'required|array',
            'form_po_id.*' => 'required|exists:tb_form_po,id_form_po',
        ]);
    
        DB::beginTransaction();

    try {
        // Temukan invoice berdasarkan ID
        $invoice = Invoice::findOrFail($id);

        // Perhitungan subtotal dan total
        $subtotal = 0;
        $totalQty = 0;

        foreach ($validatedData['qty'] as $index => $qty) {
            $hargaSatuan = $validatedData['harga_satuan'][$index];
            $subtotal += $qty * $hargaSatuan;
            $totalQty += $qty;
        }
        $subtotal += $validatedData['ongkir'];

        $downPayment = ($validatedData['dp'] / 100) * $subtotal;
        $total = $subtotal - $downPayment;

        $statusPembayaran = $downPayment >= $subtotal ? 'Lunas' : 'Belum Lunas';

        $invoice->update([
            'nota_no' => $validatedData['nota_no'],
            'status_pembayaran' => $statusPembayaran,
            'subtotal' => $subtotal,
            'jumlah' => $totalQty,
            'ongkir' => $validatedData['ongkir'],
            'down_payment' => $downPayment,
            'total' => $total,
            'tenggat_invoice' => $validatedData['tenggat_invoice'],
        ]);

        $invoice->invoiceFormPo()->delete(); // Hapus data lama

        foreach ($validatedData['keterangan'] as $index => $keterangan) {
            $qty = $validatedData['qty'][$index];
            $hargaSatuan = $validatedData['harga_satuan'][$index];
            $subtotalHargaSatuan = $qty * $hargaSatuan;

            invoiceFormPo::create([
                'invoice_id' => $invoice->invoice_id,
                'form_po_id' => $request->form_po_id[$index],
                'harga_satuan' => $hargaSatuan,
                'subtotal' => $subtotalHargaSatuan,
            ]);
        }

        DB::commit();

        return redirect()->route('kelola-invoice.index')->with('success', 'Invoice berhasil diperbarui');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Gagal memperbarui invoice. ' . $e->getMessage()]);
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return back()->with('success', 'Order Customer berhasil dihapus!');
    }
}
