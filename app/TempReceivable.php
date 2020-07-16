<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class TempReceivable extends Model
{
	protected $guarded = [];
    public static function insertData()
    {
    	$data = DB::connection('oracle')->select('select * from fin.fin_ar_vw');
        foreach ($data as $key => $value) {
            $model = new TempReceivable;
            $model->fill((array)$value);
            $model->save();
        }
        return $model;
    }
}