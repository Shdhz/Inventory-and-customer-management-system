<?php

namespace App\Observers;

use App\Models\ProductStock;
use App\Models\TransaksiDetail;

class TransaksiDetailObserver
{
    /**
     * Mengurangi stok setelah transaksi dibuat.
     */
    public function created(TransaksiDetail $transaksiDetail)
    {
        ProductStock::where('id_stok', $transaksiDetail->stok_id)
            ->decrement('jumlah_stok', $transaksiDetail->qty);
    }

    /**
     * Menyesuaikan stok saat transaksi diperbarui.
     */
    public function updated(TransaksiDetail $transaksiDetail)
    {
        $originalQty = $transaksiDetail->getOriginal('qty');
        $currentQty = $transaksiDetail->qty;
        $selisih = $currentQty - $originalQty;

        if ($selisih != 0) {
            ProductStock::where('id_stok', $transaksiDetail->stok_id)
                ->increment('jumlah_stok', $selisih);
        }
    }

    /**
     * Mengembalikan stok saat transaksi dihapus.
     */
    public function deleted(TransaksiDetail $transaksiDetail)
    {
        ProductStock::where('id_stok', $transaksiDetail->stok_id)
            ->increment('jumlah_stok', $transaksiDetail->qty);
    }
}
