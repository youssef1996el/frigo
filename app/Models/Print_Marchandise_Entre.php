<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Print_Marchandise_Entre extends Model
{
    protected $table ="print_marchandise_entree";
    protected $fillable = ["number_bon","idmarchandise_entree","idcompany"];
}
