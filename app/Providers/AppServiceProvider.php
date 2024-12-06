<?php

namespace App\Providers;

use App\Models\barangMasuk;
use App\Models\barangRusak;
use App\Models\transaksiDetail;
use App\Observers\BarangMasukObserver;
use App\Observers\BarangRusakObserver;
use App\Observers\TransaksiDetailObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        barangRusak::observe(BarangRusakObserver::class);
        barangMasuk::observe(BarangMasukObserver::class);
        transaksiDetail::observe(TransaksiDetailObserver::class);
    }
}
