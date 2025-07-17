<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaisseVide extends Model
{
    //
    protected $table ="caissevides";
    protected $fillable = ["number_box","cumul","etranger","type","clotuer","idclient","idlivreur","iduser","idcompany","idvente","idclient_tmp"];

}
