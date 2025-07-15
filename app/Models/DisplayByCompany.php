<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisplayByCompany extends Model
{
     protected $table = "display_with_company";
    protected $fillable = [ "idcompany", "idpermission", "role"];
}
