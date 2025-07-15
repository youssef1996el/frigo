<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class comptablitie extends Model
{
    protected $table = "comptabilite";
    protected $fillable = ["name","status","iduser"];
}
