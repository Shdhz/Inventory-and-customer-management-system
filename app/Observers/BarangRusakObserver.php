<?php

namespace App\Observers;

use App\Models\barangRusak;
use App\Models\productStock;

class BarangRusakObserver
{
    /**
     * Handle the barangRusak "created" event.
     */
    public function created(barangRusak $barangRusak): void
    {
        $product = productStock::find($barangRusak->stok_id);
        if ($product) {
            $product->decrement('jumlah_stok', $barangRusak->jumlah_barang_rusak);
        }
    }

    /**
     * Handle the barangRusak "updated" event.
     */
    public function updated(barangRusak $barangRusak): void
    {
        //
    }

    /**
     * Handle the barangRusak "deleted" event.
     */
    public function deleted(barangRusak $barangRusak): void
    {
        //
    }

    /**
     * Handle the barangRusak "restored" event.
     */
    public function restored(barangRusak $barangRusak): void
    {
        //
    }

    /**
     * Handle the barangRusak "force deleted" event.
     */
    public function forceDeleted(barangRusak $barangRusak): void
    {
        //
    }
}
