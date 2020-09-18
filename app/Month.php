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

    public static function update_no_of_days()
    {
        $months = Month::get();
        foreach ($months as $key => $month) {
            $month->num_of_days = cal_days_in_month(CAL_GREGORIAN,$month->month_of_year_id,$month->year);
            $month->save();
        }
    }
}
