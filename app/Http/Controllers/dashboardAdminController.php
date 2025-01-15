<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use App\Models\DraftCustomer;
use App\Models\invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class dashboardAdminController extends Controller
{
    public function index()
    {

        $adminId = Auth::id();

        // Hitung jumlah draft customers berdasarkan admin yang sedang login
        $draftCustomerCount = DraftCustomer::where('user_id', $adminId)
            ->count();

        // Hitung jumlah customer orders berdasarkan admin yang sedang login
        $orderCustomerCount = CustomerOrder::whereHas('draftCustomer', function ($query) use ($adminId) {
            $query->where('user_id', $adminId);
        })
            ->count();


        return view('v-admin.dashboard', compact('draftCustomerCount', 'orderCustomerCount'));
    }
}
