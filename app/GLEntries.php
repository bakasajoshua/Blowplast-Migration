<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GLEntries extends Model
{
    protected $table = 'GL Entries';

    protected $guarded = [];

    public $timestamps = false;
}
