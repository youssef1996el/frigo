<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = "companys";
    protected $fillable = ["name","status","iduser"];
}
