<?php

namespace App\Temps;

use App\BaseModel;
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
        }

    	if ($verbose)
        	echo "==> Finished inserting Data " . date('Y-m-d H:i:s') . "\n";

        if ($verbose)
            echo "==> Updating Data " . date('Y-m-d H:i:s') . "\n";

        DB::statement("
        UPDATE 
            [BLOWPLAST-MSTR-DEV].[dbo].[temps]
        SET 
            [BLOWPLAST-MSTR-DEV].[dbo].[temps].[type] = 'Credit Note'
        WHERE [inv_type_desc] IN ('RMA WITH CREDIT ONLY', 'RMA WITH RECEIPT AND CREDIT');
        ");

        DB::statement("
        UPDATE 
            [BLOWPLAST-MSTR-DEV].[dbo].[temps]
        SET 
            [BLOWPLAST-MSTR-DEV].[dbo].[temps].[type] = 'Invoice'
        WHERE [inv_type_desc] IN ('DIRECT INVOICE', 'RMA WITH RECEIPT AND NO CREDIT')");

        return true;
    }

    // public static function pullSourceData()
    // {
    //     $data = DB::connection('oracle')->select('select * from SLS$INVOICE$REG$DTL$VW');
    //     foreach ($data as $key => $value) {
    //         # code...
    //     }
    // }
}
