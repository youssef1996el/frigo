<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class marchandise_sortie extends Model
{
    protected $table ="marchandise_sortie";
    protected $fillable = ["number_box","cumul","etranger","type","clotuer","iduser","idclient","idlivreur","idcompany","idvente","idclient_tmp"];
}
