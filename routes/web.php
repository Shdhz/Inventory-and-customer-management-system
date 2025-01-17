<?php

use App\Http\Controllers\stok\barangRusakController;
use App\Http\Controllers\dashboardAdminController;
use App\Http\Controllers\customers\draftCustomerController;
use App\Http\Controllers\stok\kategoriBarangController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\customers\orderController;
use App\Http\Controllers\dashboardSupervisorController;
use App\Http\Controllers\laporan\LaporanPenjualanController;
use App\Http\Controllers\laporan\riwayatTransaksiController;
use App\Http\Controllers\manageAdmiinController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\rencana_produksi\rencanaProduksiController;
use App\Http\Controllers\stok\stokBarangController;
use App\Http\Controllers\transaksi\barangKeluarController;
use App\Http\Controllers\transaksi\barangMasukController;
use App\Http\Controllers\transaksi\formPoController;
use App\Http\Controllers\transaksi\InvoiceController;
use App\Http\Controllers\transaksi\invoiceFormpoController;
use App\Http\Controllers\transaksi\TransaksiController;
use App\Http\Controllers\transaksi\TransaksiDetailController;
use Illuminate\Support\Facades\Route;



Route::controller(LoginController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/', 'showLoginForm')->name('login');
        Route::post('login_auth', 'authenticate')->name('Authlogin');
    });
    Route::post('/logout', 'logout')->name('logout');
});

// admin + supervisor
Route::group(['middleware' => ['auth', 'verified', 'role:admin|supervisor']], function () {
    Route::get('dashboard-admin', [dashboardAdminController::class, 'index'])->name('dashboardAdmin.index');
    Route::get('/sales-statistics', [dashboardAdminController::class, 'salesStatistics'])->name('salesStatistics');
    Route::get('/unpaid-invoices', [dashboardAdminController::class, 'unpaidInvoice'])->name('admin.unpaidInvoice');
    Route::get('/admin/production-plan', [dashboardAdminController::class, 'getProductionPlan'])->name('admin.getProductionPlan');
    Route::resource('draft-customer', draftCustomerController::class);
    Route::resource('order-customer', orderController::class);
    Route::resource('transaksi-customer', TransaksiController::class);

    Route::controller(InvoiceController::class)->group(function () {
        Route::resource('kelola-invoice', InvoiceController::class);
        Route::get('kelola-invoice/{invoice_id}/download-pdf', 'downloadPdf')->name('invoice.downloadPdf');
        Route::resource('form-po-invoice', invoiceFormpoController::class);
        Route::get('form-po-invoice/downloadPdf/{invoice_id}', [invoiceFormpoController::class, 'downloadPdf'])->name('invoiceFormPo.downloadPdf');
    });

    Route::resource('form-po', formPoController::class);

    Route::get('laporan-penjualan', [LaporanPenjualanController::class, 'index'])->name('laporan.penjualan');
    Route::get('laporan-penjualan/export-pdf', [LaporanPenjualanController::class, 'exportPdf'])->name('laporan.penjualan.pdf');

    Route::get('riwayat-transaksi', [riwayatTransaksiController::class, 'index'])->name('riwayat.transaksi');
    Route::get('riwayat-transaksi/export-pdf', [riwayatTransaksiController::class, 'exportPdf'])->name('riwayat.transaksi.pdf');
});

// verifikasi form po
Route::middleware(['auth', 'role:supervisor'])->group(function () {
    Route::post('/form-po/update-status/{id}', [formPoController::class, 'updateStatus'])
        ->name('form-po.update-status');
});


// Produksi
Route::group(['middleware' => ['auth', 'verified', 'role:produksi']], function () {
    Route::get('/dashboard-produksi', [ProduksiController::class, 'index'])->name('dashboardProduksi.index');
    Route::resource('kategori-barang', kategoriBarangController::class);
    Route::get('/api/generate-kode-produk', [stokBarangController::class, 'generateKodeProduk']);
    Route::resource('transaksi-detail', TransaksiDetailController::class);
    Route::resource('barang-masuk', barangMasukController::class);
    Route::get('barang-keluar', [barangKeluarController::class, 'index'])->name('barang-keluar.index');
});

Route::middleware(['auth', 'verified', 'role:admin|produksi|supervisor'])->group(function () {
    Route::resource('rencana-produksi', rencanaProduksiController::class);
    Route::resource('stok-barang', stokBarangController::class);
    Route::resource('barang-rusak', barangRusakController::class);
});

// Role supervisor

Route::middleware(['auth', 'verified', 'role:supervisor'])->group(function () {
    Route::get('dashboard-supervisor', [dashboardSupervisorController::class, 'index'])->name('dashboardSupervisor.index');
    Route::get('/all-sales-statistics', [dashboardSupervisorController::class, 'salesStatistics'])->name('allSalesStatistics');
    Route::get('/unpaid-invoice-supervisor', [dashboardSupervisorController::class, 'unpaidInvoice'])->name('supervisor.unpaidInvoice');
    Route::get('/get-production-plan-supervisor', [dashboardSupervisorController::class, 'getProductionPlan'])->name('supervisor.getProductionPlan');
    Route::resource('kelola-admin', manageAdmiinController::class);
});
