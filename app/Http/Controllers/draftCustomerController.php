<?php

namespace App\Http\Controllers;

use App\Models\DraftCustomer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class draftCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $draftCustomers = DraftCustomer::all();
        // return view('admin.draft-customer', compact('draftCustomers'));
        if(request()->ajax()){
            return DataTables::make(DraftCustomer::all())->make(true);
        }
        return view('admin.draft-customer');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
