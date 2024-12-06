<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transaksiDetail extends Model
{
    protected $table = 'tb_transaksi_detail';
    protected $primaryKey = 'id_transaksi_detail';

    protected $guarded = ['id_transaksi_detail'];

    public function transaksi()
    {
        return $this->belongsTo(transaksi::class, 'transaksi_id', 'id_transaksi');
    }

    // Relasi ke tabel Stok
    public function stok()
    {
        return $this->belongsTo(productStock::class, 'stok_id', 'id_stok');
    }
}
