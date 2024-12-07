<?php

use App\Http\Controllers\stok\barangRusakController;
use App\Http\Controllers\dashboardAdminController;
use App\Http\Controllers\customers\draftCustomerController;
use App\Http\Controllers\stok\kategoriBarangController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\customers\orderController;
use App\Http\Controllers\dashboardSupervisorController;
use App\Http\Controllers\manageAdmiinController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\rencana_produksi\rencanaProduksiController;
use App\Http\Controllers\stok\stokBarangController;
use App\Http\Controllers\transaksi\barangMasukController;
use App\Http\Controllers\transaksi\formPoController;
use App\Http\Controllers\transaksi\TransaksiController;
use App\Http\Controllers\transaksi\TransaksiDetailController;
use Illuminate\Support\Facades\Route;



Route::controller(LoginController::class)->group(function(){
    Route::middleware('guest')->group(function(){
        Route::get('/', 'showLoginForm')->name('login');
        Route::post('login_auth', 'authenticate')->name('Authlogin');
    });
    Route::post('/logout', 'logout')->name('logout');
});

// admin + supervisor
Route::group(['middleware' => ['auth', 'verified', 'role:admin|supervisor']], function() {
    Route::get('dashboard-admin', [dashboardAdminController::class, 'index'])->name('dashboardAdmin.index');
    Route::resource('draft-customer', draftCustomerController::class);
    Route::resource('order-customer', orderController::class);
    Route::resource('transaksi-customer', TransaksiController::class);
    Route::resource('form-po', formPoController::class);
});

// verifikasi form po
Route::middleware(['auth', 'role:supervisor'])->group(function () {
    Route::post('/form-po/update-status/{id}', [formPoController::class, 'updateStatus'])
        ->name('form-po.update-status');
});

// Route::resource('stok-barang', stokBarangController::class);

// Produksi
Route::group(['middleware' => ['auth', 'verified', 'role:produksi']], function(){
    Route::get('/dashboard-produksi', [ProduksiController::class, 'index'])->name('dashboardProduksi.index')->middleware('auth', 'verified', 'role:produksi');
    Route::resource('kategori-barang', kategoriBarangController::class);
    Route::get('/api/generate-kode-produk', [stokBarangController::class, 'generateKodeProduk']);
    Route::resource('transaksi-detail', TransaksiDetailController::class);
    Route::resource('barang-masuk', barangMasukController::class);
});

Route::middleware(['auth', 'verified', 'role:admin|produksi|supervisor'])->group(function () {
    Route::resource('rencana-produksi', rencanaProduksiController::class);
    Route::resource('stok-barang', stokBarangController::class);
    Route::resource('barang-rusak', barangRusakController::class);
});

// Role supervisor

Route::middleware(['auth', 'verified', 'role:supervisor'])->group(function () {
    Route::get('dashboard-supervisor', [dashboardSupervisorController::class, 'index'])->name('dashboardSupervisor.index');
    Route::resource('kelola-admin', manageAdmiinController::class);
});
