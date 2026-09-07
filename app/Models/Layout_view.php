<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layout_view extends Model
{
    protected $table="layout_view";
    protected $fillable = ["id", "kode", 'judul'];
    protected $casts = [
        'config'=>'array',
    ];
}
