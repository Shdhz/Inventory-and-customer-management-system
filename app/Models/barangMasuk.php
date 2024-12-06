<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class barangMasuk extends Model
{
    protected $table = 'tb_barang_masuk';
    protected $primaryKey = 'id_barang_masuk';
    protected $guarded = ['id_barang_masuk'];

    public function product(){
        return $this->belongsTo(productStock::class, 'stok_id', 'id_stok');
    }
}
