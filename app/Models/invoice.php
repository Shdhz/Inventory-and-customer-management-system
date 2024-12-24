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

    // public function formPO()
    // {
    //     return $this->belongsTo(FormPO::class, 'form_po_id', 'id_form_po');
    // }

    // public function transaksiDetail()
    // {
    //     return $this->belongsTo(TransaksiDetail::class, 'transaksi_detail_id', 'id_transaksi_detail');
    // }
}
