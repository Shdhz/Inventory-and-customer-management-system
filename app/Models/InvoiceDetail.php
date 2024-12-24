<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $table = 'tb_invoice_detail';
    protected $primaryKey = 'invoice_detail_id';

    // Kolom yang bisa diisi
    protected $guarded = ['invoice_detail_id'];

    // Relasi ke model Invoice
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }

    // Relasi ke model TransaksiDetail
    public function transaksiDetail()
    {
        return $this->belongsTo(TransaksiDetail::class, 'transaksi_detail_id', 'id_transaksi_detail');
    }
}
