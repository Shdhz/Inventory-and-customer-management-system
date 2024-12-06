<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transaksi extends Model
{
    protected $table = 'tb_transaksi';
    protected $primaryKey = 'id_transaksi';
    protected $guarded = ['id_transaksi'];

    // Relasi ke tabel CustomerOrder
    public function customerOrder()
    {
        return $this->belongsTo(CustomerOrder::class, 'customer_order_id', 'customer_order_id');
    }

    // Relasi ke tabel TransaksiDetail
    public function transaksiDetails()
    {
        return $this->hasMany(transaksiDetail::class, 'transaksi_id', 'id_transaksi');
    }
}
