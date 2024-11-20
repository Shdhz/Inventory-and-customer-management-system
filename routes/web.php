<?php

use App\Http\Controllers\dashboardAdminController;
use App\Http\Controllers\draftCustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [dashboardAdminController::class, 'index'])->name('dashboardAdmin.index');
Route::get('/draft-customer', [draftCustomerController::class, 'index'])->name('draftCustomer.index');

Route::get('/order-customer', function(){
    return view('admin.order-customer');
});
