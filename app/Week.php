<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    protected $table = 'LU_Week';

    protected $guarded = [];

    public $timestamps = false;

    protected $keyType = 'string';

    protected $primaryKey = 'week';
}
