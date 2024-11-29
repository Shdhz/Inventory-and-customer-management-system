<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class productStock extends Model
{
    protected $table = 'tb_products';

    protected $guarded = [
        'id_stok',
        'kategori_id'
    ];

    // Relasi ke kategori
    public function category()
    {
        return $this->belongsTo(categoriesProduct::class, 'kategori_id', 'id_kategori');
    }
}
