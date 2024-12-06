<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class productStock extends Model
{
    protected $table = 'tb_products';
    protected $primaryKey = 'id_stok';

    protected $guarded = [
        'id_stok',
    ];

    // Relasi ke kategori
    public function category()
    {
        return $this->belongsTo(categoriesProduct::class, 'kategori_id', 'id_kategori');
    }

    public function barangRusak()
    {
        return $this->hasMany(barangRusak::class);
    }

    public function barangMasuk()
    {
        return $this->hasMany(barangMasuk::class);
    }
}
