<?php

use App\Http\Controllers\dashboardAdminController;
use App\Http\Controllers\draftCustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    return view('login');
});
Route::get('/dashboard', [dashboardAdminController::class, 'index'])->name('dashboardAdmin.index');
Route::resource('draft-customer', draftCustomerController::class);

Route::get('/order-customer', function(){
    return view('admin.order-customer');
});
