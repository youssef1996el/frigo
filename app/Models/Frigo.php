<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frigo extends Model
{
    protected $table = "frigo";
    protected $fillable = ["date", "charge_id", "dotation", "montant", "cumul_dotation", "cumul_charge","idcomptabilite"];


    public function charge()
    {
        return $this->belongsTo(Charge::class);
    }
}
