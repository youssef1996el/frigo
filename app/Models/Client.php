<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table ="clients";
    protected $fillable = ["firstname","lastname","cin","address","phone","iduser","idcompany","image_cin"];
}
