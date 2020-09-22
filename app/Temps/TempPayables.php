<?php

namespace App\Temps;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;
use DB;

class TempPayables extends Model
{
    protected $connection = 'testdb';

    protected $guarded = [];
    
    public static function insertData($verbose=false)
    {
    	if ($verbose)
            echo "==> Start pulling KE Payables Data " . date('Y-m-d H:i:s') . "\n";
        $data = DB::connection('oracle')->select('select * from fin.fin_ap_vw');
        if ($verbose){
            echo "==> Finished pulling KE Data " . date('Y-m-d H:i:s') . "\n";
            echo "==> Inserting Temp KE Payables Data " . date('Y-m-d H:i:s') . "\n";
        }
        foreach ($data as $key => $value) {
            $model = new TempPayables;
            $model->fill((array)$value);
            $model->save();
        }
        if ($verbose)
            echo "==> Finished Inserting KE Payables Data into the WH " . date('Y-m-d H:i:s') . "\n";

    	
        
        return $model;
    }
}


