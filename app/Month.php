<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    protected $table = 'LU_Month';

    protected $guarded = [];

    public $timestamps = false;

    public function month_year()
    {
    	return $this->belongsTo(Year::class, 'year', 'year');
    }

    public function month_quarter()
    {
    	return $this->belongsTo(Quarter::class, 'quarter_id', 'quarter');
    }
}
