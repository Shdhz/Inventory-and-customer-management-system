<?php

use App\Http\Controllers\stok\barangRusakController;
use App\Http\Controllers\dashboardAdminController;
use App\Http\Controllers\draftCustomerController;
use App\Http\Controllers\stok\kategoriBarangController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\orderController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\stok\stokBarangController;
use Illuminate\Support\Facades\Route;


// Route login
Route::controller(LoginController::class)->group(function(){
    Route::get('/', 'showLoginForm');  // Menambahkan rute default ke login
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login_auth', 'authenticate')->name('Authlogin');
    Route::post('/logout', 'logout')->name('logout');
});

// Route role admin
Route::group(['middleware' => ['auth', 'verified', 'role:admin']], function() {
    Route::get('dashboard-admin', [dashboardAdminController::class, 'index'])->name('dashboardAdmin.index');
    Route::resource('draft-customer', draftCustomerController::class);
    Route::get('order-customer', [orderController::class, 'index'])->name('oc.index');
});

// Route role Produksi
Route::group(['middleware' => ['auth', 'verified', 'role:produksi']], function(){
    Route::get('/dashboard-produksi', [ProduksiController::class, 'index'])->name('dashboardProduksi.index')->middleware('auth', 'verified', 'role:produksi');
    Route::resource('kategori-barang', kategoriBarangController::class);
    Route::resource('stok-barang', stokBarangController::class);
    Route::resource('barang-rusak', barangRusakController::class);
});
