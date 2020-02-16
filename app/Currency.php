<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'Currencies';

    protected $guarded = [];

    public $timestamps = false;
}
