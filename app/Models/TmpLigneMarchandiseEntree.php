<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TmpLigneMarchandiseEntree extends Model
{
    protected $table ="tmp_ligne_marchandis_entree";
    protected $fillable = ["quantity","Etranger","idproduct","iduser","idclient","idcompany","idlivreur"];
   
}
