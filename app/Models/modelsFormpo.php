<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class modelsFormpo extends Model
{
    protected $table = 'tb_model_form_po';
    protected $primaryKey = 'id_model';

    protected $guarded = ['id_model'];

    public function formPo()
    {
        return $this->belongsTo(formPo::class, 'id_form_po', 'id_form_po');
    }
}
