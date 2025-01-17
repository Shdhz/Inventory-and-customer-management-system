<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class instagramForAdmin extends Model
{
    protected $table = 'tb_instagram_info';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
