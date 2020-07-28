<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Temp extends Model
{
    protected $connection = 'testdb';

    public static function pullData($verbose=false)
    {
    	Temp::truncate();
    	ini_set("memory_limit", "-1");
    	if ($verbose)
        	echo "==> Start pulling Data " . date('Y-m-d H:i:s') . "\n";
        $data = DB::connection('oracle')->select('select * from SLS$INVOICE$REG$DTL$VW');
    	if ($verbose){
    		echo "==> Finished pulling Data " . date('Y-m-d H:i:s') . "\n";
        	echo "==> Formatting the data " . date('Y-m-d H:i:s') . "\n";
    	}
        
        foreach ($data as $key => $value) {
            $value = (array) $value;
            Temp::insert($value);
    		if ($verbose)
            	echo ".";
        }
    	if ($verbose)
        	echo "\n";

    	if ($verbose)
        	echo "==> Finished inserting Data " . date('Y-m-d H:i:s') . "\n";
        return true;
    }
}
