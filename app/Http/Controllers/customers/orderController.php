<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use App\Models\DraftCustomer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class orderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customerOrders = CustomerOrder::with('draftCustomer')
                ->when(Auth::user()->hasRole('admin'), function ($query) {
                    $query->whereHas('draftCustomer', function ($subQuery) {
                        $subQuery->where('user_id', Auth::id());
                    });
                })
                ->get();

            return Datatables::of($customerOrders)
                ->addIndexColumn()
                ->addColumn('updated_at', function ($row) {
                    return Carbon::parse($row->updated_at)->format('d F Y');
                })
                ->addColumn('Nama', function ($row) {
                    return $row->draftCustomer ? $row->draftCustomer->Nama : '-';
                })
                ->addColumn('admin_name', function ($row) {
                    return $row->draftCustomer ? $row->draftCustomer->user->name : '-';
                })
                ->addColumn('sumber', function ($row) {
                    return $row->draftCustomer ? $row->draftCustomer->sumber : '-';
                })
                ->addColumn('actions', function ($row) {
                    return view('components.button.action-btn', [
                        'edit' => route('order-customer.edit', $row->customer_order_id),
                        'delete' => route('order-customer.destroy', $row->customer_order_id),
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
        $backUrl = url()->previous();

        $draftCustomers = DraftCustomer::select('draft_customers_id', 'Nama', 'sumber')
            ->when(Auth::user()->hasRole('admin'), function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->whereDoesntHave('CustomerOrder')
            ->get();

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

        // Tentukan tipe order
        $sumber = strtolower(DraftCustomer::findOrFail($validated['draft_customer_id'])->sumber);

        $tipeOrder = match (true) {
            in_array($sumber, ['shopee', 'tokopedia', 'lazada', 'tiktok shop', 'tiktok']) => 'cashless',
            in_array($sumber, ['whatsapp', 'instagram', 'facebook', 'youtube']) => 'cash', // Pastikan 'youtube' ditangani
            default => null,
        };

        if (!$tipeOrder) {
            return redirect()->back()->withErrors(['draft_customer_id' => 'Sumber tidak valid.']);
        }

        // store data ke database
        CustomerOrder::create([
            'draft_customer_id' => $validated['draft_customer_id'],
            'tipe_order' => $tipeOrder,
            'jenis_order' => $validated['jenis_order'],
            'keterangan' => $validated['keterangan'],
        ]);
        return redirect()->route('order-customer.index')->with('success', 'Order customer berhasil ditambahkan!');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = "Edit Order Customer";
        $backUrl = url()->previous();

        $orderCustomer = CustomerOrder::findOrFail($id);

        // Ambil daftar draft customer untuk dropdown
        $draftCustomers = DraftCustomer::select('draft_customers_id', 'Nama', 'sumber')
            ->when(Auth::user()->hasRole('admin'), function ($query) {
                $query->whereHas('user', function ($subQuery) {
                    $subQuery->where('user_id', Auth::id());
                });
            })->get();

        return view('v-admin.order_customers.edit', compact('title', 'backUrl', 'orderCustomer', 'draftCustomers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'draft_customer_id' => 'required|exists:tb_draft_customers,draft_customers_id',
            'jenis_order' => 'required|in:pre order,ready stock',
            'keterangan' => 'nullable|string',
        ]);

        // Ambil data order customer berdasarkan ID
        $orderCustomer = CustomerOrder::findOrFail($id);
        $sumber = strtolower(DraftCustomer::findOrFail($validated['draft_customer_id'])->sumber);

        $tipeOrder = match (true) {
            in_array($sumber, ['shopee', 'tokopedia', 'lazada', 'tiktok shop', 'tiktok']) => 'cashless',
            in_array($sumber, ['whatsapp', 'instagram', 'facebook', 'youtube']) => 'cash',
            default => null,
        };

        if (!$tipeOrder) {
            return redirect()->back()->withErrors(['draft_customer_id' => 'Sumber tidak valid.']);
        }

        // Update data di database
        $orderCustomer->update([
            'draft_customer_id' => $validated['draft_customer_id'],
            'tipe_order' => $tipeOrder,
            'jenis_order' => $validated['jenis_order'],
            'keterangan' => $validated['keterangan'],
        ]);
        return redirect()->route('order-customer.index')->with('success', 'Order berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $orderCustomer = CustomerOrder::findOrFail($id);
        $orderCustomer->delete();

        return back()->with('success', 'Order Customer berhasil dihapus!');
    }
}
