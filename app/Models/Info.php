<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    protected $table ="infos";
    protected $fillable = ["name", "phone", "ice", "if", "capital", "cb", "companie", "iduser"];
}
