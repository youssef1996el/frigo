<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
     protected $table ="ventes";
    protected $fillable = ["number_box", "achteur", "vendeur", "idproduct", "idcompany","iduser"];
}
