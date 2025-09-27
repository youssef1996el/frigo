<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class marchandise_entree extends Model
{
    protected $table ="marchandis_entree";
    protected $fillable = ["number_box","cumul","etranger","type","clotuer","iduser","idclient","idlivreur","idcompany","idvente","idclient_tmp"];

}
