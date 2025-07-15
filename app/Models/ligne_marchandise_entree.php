<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ligne_marchandise_entree extends Model
{
    protected $table ="ligne_marchandis";
    protected $fillable = ["quantity","id_marchandis_entree","Etranger","idproduct"];
}
