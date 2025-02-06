<?php

namespace App\Http\Controllers;

use App\Models\formPo;

class ProduksiController extends Controller
{
    public function index()
    {
        $formPoActive = formPo::with('customerOrder.draftCustomer')
            ->where('status_form_po', 1)
            ->whereDoesntHave('rencanaProduksi')
            ->get();
        return view('v-produksi.dashbord', compact('formPoActive'));
    }
}
