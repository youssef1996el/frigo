<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class List_origins extends Model
{
    
    protected $table = "list_origins";
    protected $fillable = ["name","iduser","idcompany"];
}
