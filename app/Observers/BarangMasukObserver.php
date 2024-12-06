<?php

namespace App\Observers;

use App\Models\BarangMasuk;
use App\Models\ProductStock;
use Illuminate\Support\Facades\DB;

class BarangMasukObserver
{
    /**
     * Handle the BarangMasuk "creating" event.
     */
    public function creating(BarangMasuk $barangMasuk)
    {
        $product = ProductStock::find($barangMasuk->stok_id);

        if ($product) {
            $product->jumlah_stok += $barangMasuk->jumlah_barang_masuk;
            $product->save();
        }
    }

    /**
     * Handle the BarangMasuk "updating" event.
     */
    public function updating(BarangMasuk $barangMasuk)
    {
        DB::transaction(function () use ($barangMasuk) {
            $original = $barangMasuk->getOriginal();

            $product = ProductStock::find($barangMasuk->stok_id);

            if ($product) {
                $product->jumlah_stok -= $original['jumlah_barang_masuk'];

                // Tambah stok dengan nilai baru
                $product->jumlah_stok += $barangMasuk->jumlah_barang_masuk;

                if ($product->jumlah_stok < 0) {
                    throw new \Exception('Stok tidak boleh negatif.');
                }

                $product->save();
            }
        });
    }

    /**
     * Handle the BarangMasuk "deleting" event.
     */
    public function deleting(BarangMasuk $barangMasuk)
    {
        $product = ProductStock::find($barangMasuk->stok_id);

        if ($product) {
            $product->jumlah_stok -= $barangMasuk->jumlah_barang_masuk;

            if ($product->jumlah_stok < 0) {
                throw new \Exception('Stok tidak boleh negatif.');
            }

            $product->save();
        }
    }
}
