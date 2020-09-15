<?php

namespace App\Temps;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;
use DB;

class TempPayables extends Model
{
    protected $connection = 'testdb';

    protected $guarded = [];
    
    public static function insertData()
    {
    	$data = DB::connection('oracle')->select('select * from fin.fin_ap_vw');
        foreach ($data as $key => $value) {
            $model = new TempPayables;
            $model->fill((array)$value);
            $model->save();
        }
        return $model;
    }
}


