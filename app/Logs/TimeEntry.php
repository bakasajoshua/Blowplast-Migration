<?php

namespace App\Logs;

use Illuminate\Database\Eloquent\Model;

class TimeEntry extends Model
{
    protected $connection = 'testdb';

    protected $guarded = ['id'];
}
