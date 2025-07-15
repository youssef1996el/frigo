<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Print_Marchandise_Sortie extends Model
{
     protected $table ="print_marchandise_sortie";
    protected $fillable = ["number_bon","idmarchandise_sortie","idcompany"];
}
