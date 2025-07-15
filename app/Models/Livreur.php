<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livreur extends Model
{
    //
    protected $table ="livreurs";
    protected $fillable = ["name","cin","matricule","phone","image_cin","iduser","idcompany"];
}
