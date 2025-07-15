<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ligne_marchandise_sortie extends Model
{
    protected $table ="ligne_marchandise_sortie";
    protected $fillable = ["quantity","id_marchandise_sortie","Etranger","idproduct"];
}
