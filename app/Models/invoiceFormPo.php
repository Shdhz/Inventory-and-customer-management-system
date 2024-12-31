<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class invoiceFormPo extends Model
{
    protected $table = 'tb_invoice_form_po';
    protected $primaryKey = 'id_invoice_form_po';

    // Kolom yang bisa diisi
    protected $guarded = ['id_invoice_form_po'];

    // Relasi ke model Invoice
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }

    // Relasi ke model formpo
    public function formPo()
    {
        return $this->belongsTo(formPo::class, 'form_po_id', 'id_form_po');
    }
}
