<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaisseRetour extends Model
{
    protected $table ="caisse_retour";
    protected $fillable = ["number_box","cumul","etranger","type","clotuer","idclient","idlivreur","iduser","idcompany","idvente","idclient_tmp"];
}
