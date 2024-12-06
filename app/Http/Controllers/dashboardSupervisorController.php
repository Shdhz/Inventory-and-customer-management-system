<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class dashboardSupervisorController extends Controller
{
    public function index(){
        $draftCustomerCount = DB::table('tb_draft_customers')->count();
        $orderCustomerCount = DB::table('tb_customer_orders')->count();
        return view('v-supervisor.dashboard', compact('draftCustomerCount', 'orderCustomerCount'));
    }
}
