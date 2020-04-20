<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quarter extends Model
{
    protected $table = 'LU_Quarter';

    protected $guarded = [];

    public $timestamps = false;
}
