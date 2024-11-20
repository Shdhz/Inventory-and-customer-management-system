<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DraftCustomer extends Model
{
    protected $table = 'tb_draft_customers';

    // Prevent Id for being fill in
    protected $guarded = [
        'draft_customer_id',
        'user_id'
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}
