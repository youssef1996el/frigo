<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TmpLigneMarchandiseSortie extends Model
{
    protected $table ="tmp_ligne_marchandise_sortie";
    protected $fillable = ["quantity","Etranger","idproduct","iduser","idclient","idcompany","idlivreur"];
}
