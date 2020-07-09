<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    protected $table = 'LU_Day';

    protected $guarded = [];

    public $timestamps = false;

    public function day_month()
    {
    	return $this->belongsTo(Month::class, 'month', 'month_id');
    }

    public function day_week()
    {
    	return $this->belongsTo(Week::class, 'week', 'week');
    }
}
