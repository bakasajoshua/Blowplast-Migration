<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    protected $table = 'LU_Year';

    protected $guarded = [];

    public $timestamps = false;
}
