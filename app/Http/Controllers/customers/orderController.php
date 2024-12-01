<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use App\Models\DraftCustomer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class orderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customerOrders = CustomerOrder::with('draftCustomer')->get(); // Fetch data with the relation

            return Datatables::of($customerOrders)
                ->addIndexColumn()
                ->addColumn('updated_at', function ($row) {
                    return Carbon::parse($row->updated_at)->format('d M Y, H:i'); // Contoh: 01 Dec 2024, 19:59
                })
                ->addColumn('Nama', function ($row) {
                    return $row->draftCustomer ? $row->draftCustomer->Nama : 'N/A';
                })
                ->addColumn('sumber', function ($row) {
                    return $row->draftCustomer ? $row->draftCustomer->sumber : 'N/A';
                })
                ->addColumn('actions', function ($row) {
                    return view('components.button.action-btn', [
                        'edit' => route('draft-customer.edit', $row->customer_order_id),
                        'delete' => route('draft-customer.destroy', $row->customer_order_id),
                    ])->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('v-admin.order_customers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tambah order Customer";
        $backUrl = Route('order-customer.index');

        $draftCustomers = DraftCustomer::select('draft_customers_id', 'Nama', 'sumber')->get();

        return view('v-admin.order_customers.create', compact('title', 'backUrl', 'draftCustomers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'draft_customer_id' => 'required|exists:tb_draft_customers,draft_customers_id',
            'jenis_order' => 'required|in:pre order,ready stock',
            'keterangan' => 'nullable|string',
        ]);

        // Tentukan tipe order berdasarkan sumber dalam satu variabel
        $tipeOrder = match (true) {
            $sumber = strtolower(DraftCustomer::findOrFail($validated['draft_customer_id'])->sumber),
            in_array($sumber, ['shopee', 'tokopedia', 'lazada', 'tiktok shop']) => 'cashless',
            in_array($sumber, ['whatsapp', 'instagram', 'facebook']) => 'cash',
            default => null,
        };

        if (!$tipeOrder) {
            return redirect()->back()->withErrors(['draft_customer_id' => 'Sumber tidak valid.']);
        }

        // Proses selanjutnya
        CustomerOrder::create([
            'draft_customer_id' => $validated['draft_customer_id'],
            'tipe_order' => $tipeOrder,
            'jenis_order' => $validated['jenis_order'],
            'keterangan' => $validated['keterangan'],
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('order-customer.index')->with('success', 'Order berhasil ditambahkan!');
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
