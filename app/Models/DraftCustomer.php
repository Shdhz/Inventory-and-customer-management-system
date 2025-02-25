<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DraftCustomer extends Model
{
    protected $table = 'tb_draft_customers';
    protected $primaryKey = 'draft_customers_id';

    // Prevent Id for being fill in
    protected $guarded = [
        'draft_customers_id'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customerOrder()
    {
        return $this->hasOne(CustomerOrder::class, 'draft_customer_id', 'draft_customers_id');
    }
    
}
