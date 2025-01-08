<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class invoice extends Model
{
    protected $table = 'tb_invoice';
    protected $primaryKey = 'invoice_id';

    protected $guarded = ['invoice_id'];

    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id', 'invoice_id');
    }
    public function invoiceFormPo()
    {
        return $this->hasMany(invoiceFormPo::class, 'invoice_id', 'invoice_id');
    }
}
