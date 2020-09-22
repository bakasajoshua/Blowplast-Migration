<?php

namespace App\Temps;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;
use DB;

class TempKEGL extends BaseModel
{
    protected $guarded = ['id'];

    protected $connection = 'testdb';

    public static function syncData($verbose=false)
    {
    	TempKEGL::truncate();
    	// $data = self::dataSource();
        if ($verbose)
            echo "==> Start pulling KE Data " . date('Y-m-d H:i:s') . "\n";
        $data = DB::connection('oracle')->select('select * from fin.fin_gl_vw');
        if ($verbose){
            echo "==> Finished pulling KE Data " . date('Y-m-d H:i:s') . "\n";
            echo "==> Inserting Temp KE Data " . date('Y-m-d H:i:s') . "\n";
        }
        foreach ($data as $key => $chunk) {
        	TempKEGL::insert((array)$chunk);
        }
        if ($verbose)
            echo "==> Finished Inserting KE Data into the WH " . date('Y-m-d H:i:s') . "\n";
    }
}
