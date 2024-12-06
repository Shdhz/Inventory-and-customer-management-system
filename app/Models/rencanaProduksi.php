<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rencanaProduksi extends Model
{
    protected $table = 'tb_rencana_produksi';
    protected $primaryKey = 'id_rencana_produksi';
    protected $guarded = ['id_rencana_produksi'];

    public function formPo()
    {
        return $this->belongsTo(FormPo::class, 'form_po_id', 'id_form_po');
    }
}
