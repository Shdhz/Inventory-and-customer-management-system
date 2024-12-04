<?php

namespace App\Observers;

use App\Models\BarangRusak;
use App\Models\ProductStock;

class BarangRusakObserver
{
    /**
     * Handle the barangRusak "created" event.
     */
    public function created(BarangRusak $barangRusak): void
    {
        $product = ProductStock::find($barangRusak->stok_id);

        if ($product) {
            $product->jumlah_stok -= $barangRusak->jumlah_barang_rusak;
            $product->save();
        }
    }

    /**
     * Handle the barangRusak "updated" event.
     */
    public function updated(BarangRusak $barangRusak): void
    {
        // Dapatkan produk yang terkait dengan barang rusak ini
        $product = ProductStock::find($barangRusak->stok_id);

        if ($product) {
            // Jika stok_id barang rusak telah berubah
            if ($barangRusak->getOriginal('stok_id') != $barangRusak->stok_id) {
                // Kembalikan stok barang rusak yang lama
                $originalProduct = ProductStock::find($barangRusak->getOriginal('stok_id'));
                if ($originalProduct) {
                    // Mengembalikan stok yang sebelumnya dikurangi
                    $originalProduct->jumlah_stok += $barangRusak->getOriginal('jumlah_barang_rusak');
                    $originalProduct->save();
                }

                // Kurangi stok pada barang yang baru
                $newProduct = ProductStock::find($barangRusak->stok_id);
                if ($newProduct) {
                    // Mengurangi stok produk baru
                    $newProduct->jumlah_stok -= $barangRusak->jumlah_barang_rusak;
                    $newProduct->save();
                }
            } else {
                // Jika stok_id tetap sama, cukup sesuaikan stok berdasarkan perubahan jumlah
                $difference = $barangRusak->jumlah_barang_rusak - $barangRusak->getOriginal('jumlah_barang_rusak');
                $product->jumlah_stok -= $difference;
                $product->save();
            }
        }
    }

    /**
     * Handle the barangRusak "deleted" event.
     */
    public function deleted(BarangRusak $barangRusak): void
    {
        $product = ProductStock::find($barangRusak->stok_id);

        if ($product) {
            // Kembalikan stok yang telah dikurangi saat barang rusak dihapus
            $product->jumlah_stok += $barangRusak->jumlah_barang_rusak;
            $product->save();
        }
    }

    /**
     * Handle the barangRusak "restored" event.
     */
    public function restored(BarangRusak $barangRusak): void
    {
        // Logic for restored items if needed
    }

    /**
     * Handle the barangRusak "force deleted" event.
     */
    public function forceDeleted(BarangRusak $barangRusak): void
    {
        // Logic for force deleted items if needed
    }
}

