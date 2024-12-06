<?php

namespace App\Observers;

use App\Models\productStock;
use App\Models\TransaksiDetail;

class TransaksiDetailObserver
{
    // Mengurangi stok setelah transaksi dibuat
    public function created(TransaksiDetail $transaksiDetail)
    {
        $stok = productStock::find($transaksiDetail->stok_id);
        if ($stok) {
            $stok->jumlah_stok -= $transaksiDetail->qty;
            $stok->save();
        }
    }

    // Menyesuaikan stok saat transaksi diperbarui
    public function updated(TransaksiDetail $transaksiDetail)
    {
        $stok = productStock::find($transaksiDetail->stok_id);
        if ($stok) {
            $selisih = $transaksiDetail->qty - $transaksiDetail->getOriginal('qty');
            $stok->jumlah_stok -= $selisih;
            $stok->save();
        }
    }

    // Mengembalikan stok saat transaksi dihapus
    public function deleted(TransaksiDetail $transaksiDetail)
    {
        $stok = productStock::find($transaksiDetail->stok_id);
        if ($stok) {
            $stok->jumlah_stok += $transaksiDetail->qty;
            $stok->save();
        }
    }
}

