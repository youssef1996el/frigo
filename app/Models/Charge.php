<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
     protected $table ="charges";
    protected $fillable = ["libelle","iduser","idcompany"];

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'idcompany');
    }
}
