<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{
    protected $table = 'tb_customer_orders';
    protected $primaryKey = 'customer_order_id';

        // Prevent Id for being fill in
    protected $guarded = [
        'customer_order_id'
    ];

        // Relasi ke tb_draft_customer
    public function draftCustomer()
    {
        return $this->belongsTo(DraftCustomer::class, 'draft_customer_id', 'draft_customers_id');
    }
}
