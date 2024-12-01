<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class dashboardAdminController extends Controller
{
    public function index(){

        $draftCustomerCount = DB::table('tb_draft_customers')->count();
        $orderCustomerCount = DB::table('tb_customer_orders')->count();
        return view('v-admin.dashboard', compact('draftCustomerCount', 'orderCustomerCount'));
    }
}
