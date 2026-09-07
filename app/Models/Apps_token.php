<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apps_token extends Model
{
    protected $table = "apps_token";
    protected $fillable = ["id", "name", "hashed_key", "is_active"];
}
