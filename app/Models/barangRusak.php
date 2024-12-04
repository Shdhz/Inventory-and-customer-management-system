<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class barangRusak extends Model
{
    protected $table = 'tb_barang_rusak';
    protected $primaryKey = 'barang_rusak_id';

    protected $guarded = ['barang_rusak_id'];

    public function product()
    {
        return $this->belongsTo(productStock::class, 'stok_id ', 'id_stok');
    }
}
