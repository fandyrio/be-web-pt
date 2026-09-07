<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bagian extends Model
{
    protected $table = "bagian";
    protected $fillable = ["id", "id_bagian_induk", "is_induk_bagian", "pan_sek", "bagian", "status"];
}
