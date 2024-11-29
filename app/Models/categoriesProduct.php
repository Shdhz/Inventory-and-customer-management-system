<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class categoriesProduct extends Model
{
    protected $table = 'tb_categories_products';
    protected $guarded = [
        'id_kategori'
    ];

    public function stocks()
    {
        return $this->hasMany(productStock::class); // Satu kategori memiliki banyak stok
    }
}
