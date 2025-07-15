<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Print_Caisse_Retour extends Model
{
    protected $table ="print_caisse_retour";
    protected $fillable = ["number_bon","idcaisseretour","idcompany"];
}
