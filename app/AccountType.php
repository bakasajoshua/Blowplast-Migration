<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class AccountType extends Model
{
    protected $table = 'GL_Accounts_Level_1';

    protected $guarded = [];

    protected $primaryKey = 'Level_1_ID';

    public $timestamps = false;

    protected $primaryKey = 'Level_1_ID';

    public static function syncKELevel1()
    {
    	echo "==> Pulling in the DB data " . date('Y-m-d H:i:s') . "\n";
    	$data = DB::connection('oracle')->select("SELECT * FROM fin.fin_gl_vw");
    	$accounttypes = [];
    	echo "==> Checking for duplicates and formatting the data " . date('Y-m-d H:i:s') . "\n";
        foreach ($data as $key => $value) {
            $account = (array) $value;
            $accounts = explode('->', $account['chart of group']);
            if (sizeof($accounts) > 0){
            	$level1 = $accounts[0];
            	if (AccountType::where('Level_1_Description', $level1)->get()->isEmpty()){
            		$accounttypes[] = [
            				'Level_1_Description' => $level1,
            				'Company_Code' => 'BPL'
            			];
            	}
            }
        }
        
    	echo "==> Inserting the data " . date('Y-m-d H:i:s') . "\n";
    	foreach ($accounttypes as $key => $accountype) {
    		if (AccountType::where('Level_1_Description', $accountype['Level_1_Description'])->get()->isEmpty())
    			AccountType::create($accountype);
    	}
    	// $chunks = collect($accounttypes)->chunk(20);
    	// foreach ($chunks as $key => $data) {
    	// 	if (AccountType::where('Level_1_Description', $level1)->get()->isEmpty()){
     //        	AccountType::insert($data->toArray());
     //    }
        return true;
    }
}
