<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Print_Caisse_Vides extends Model
{
    protected $table ="print_caisse_vides";
    protected $fillable = ["number_bon","idcaissevide","idcompany"];
}
