<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    protected $table = 'LU_Month';

    protected $guarded = [];

    public $timestamps = false;
}
