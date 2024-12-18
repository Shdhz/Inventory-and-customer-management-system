<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class invoice extends Model
{
    protected $table = 'tb_invoice';
    protected $primaryKey = 'invoice_id';

    protected $guarded = ['invoice_id'];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'ongkir' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function formPO()
    {
        return $this->belongsTo(FormPO::class, 'form_po_id', 'id_form_po');
    }

    public function transaksiDetail()
    {
        return $this->belongsTo(TransaksiDetail::class, 'transaksi_detail_id', 'id_transaksi_detail');
    }
}
