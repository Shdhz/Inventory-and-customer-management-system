<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class formPo extends Model
{
    protected $table = 'tb_form_po';
    protected $primaryKey = 'id_form_po';

    protected $guarded = ['id_form_po'];

    // Relasi ke tabel CustomerOrder
    public function customerOrder()
    {
        return $this->belongsTo(CustomerOrder::class, 'customer_order_id', 'customer_order_id');
    }

    public function category()
    {
        return $this->belongsTo(categoriesProduct::class, 'kategori_id', 'id_kategori');
    }

    public function rencanaProduksi()
    {
        return $this->hasMany(rencanaProduksi::class);
    }
}
