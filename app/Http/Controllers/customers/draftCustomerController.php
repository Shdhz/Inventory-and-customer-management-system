<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use App\Models\DraftCustomer;
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
                        'edit' => route('draft-customer.edit', $row->user_id),
                        'delete' => route('draft-customer.destroy', $row->user_id),
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
        return view('v-admin.draft_customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
