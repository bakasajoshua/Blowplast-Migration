<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'Countries';

    protected $guarded = [];

    public $timestamps = false;
}
