<?php

namespace App\Temps;

use Illuminate\Database\Eloquent\Model;
use App\GLAccounts;
use App\GLEntries;

class TempPrevKEGL extends Model
{
    protected $connection = 'mysql';

    protected $table = 'temp_ke_glentries';

    public function fillentries($years = [])
    {
    	ini_set("memory_limit", "-1");
    	foreach ($years as $key => $value) {
    		echo "==> Checking data existance for the year " . $value . " - " . date('Y-m-d H:i:s') . "\n";
    		$temp_data = $this->whereYear('Voucher Date', $value)->get();
    		echo "==> Data Records found for the year " . $value . " are {$temp_data->count()} - " . date('Y-m-d H:i:s') . "\n";
    		if ($temp_data->count() > 0) {
    			echo "==> Preparing data for the warehouse " . date('Y-m-d H:i:s') . "\n";
    			$keData = $temp_data->toArray();
    			$chunkKE = [];
    			foreach ($keData as $key => $entry) {
    				$glaccount = GLAccounts::where('GL_Account_Name', $entry['COA Name'])->where('Company_Code', 'BPL')->get();

	                if ($glaccount->isEmpty()) {
	                    $glaccount = GLAccounts::saveKEGLAccount($entry);
	                } else {
	                    $glaccount = $glaccount->first();
	                }
	                $chunkKE[] = [
	                    'GL_Entry_No' => round(microtime(true) * 1000),
	                    'GL_Account_No' => $glaccount->GL_Account_No,
	                    'Debit' => $entry['DEBIT'],
	                    'Credit' => $entry['CREDIT'],
	                    'Amounts' => ((float)$entry['DEBIT']-(float)$entry['CREDIT']),
	                    'Currency_Code' => $entry['CURRENCY'],
	                    'GL_Posting_Date' => date('Y-m-d', strtotime($entry['Voucher Date'])),
	                    'Day' => date('Y-m-d', strtotime($entry['Voucher Date'])),
	                    'GL_Document_No' => $entry['Doc No'],
	                    'GL_Document_Type' => NULL,
	                    'Description' => $entry['NARRATION'],
	                    'Company_Code' => 'BPL',
	                ];
    			}
    			echo "==> Getting and removing old Data in the warehouse " . date('Y-m-d H:i:s') . "\n"; 
    			$existing_data = GLEntries::where('year', $value)->get();
    			foreach ($existing_data as $key => $db_entries) {
    				$db_entries->delete();
    			}
    			echo "==> Finished and removing old Data in the warehouse " . date('Y-m-d H:i:s') . "\n"; 
    			$gl_entry_class = new GLEntries;
	            $collection = collect($chunkKE);
	            echo "==> Warehouse count " . $collection->count() . "\n";
	            $chunks = $collection->chunk($gl_entry_class->chunkQty);
	            $insert = $gl_entry_class->insertChunk($chunks);
		        // $updates = $gl_entry_class->updateDay();
		        // $updates = $gl_entry_class->updateOtherTimeDimensions();         
	            echo "==> Finished inserting KE Data into the warehouse " . date('Y-m-d H:i:s') . "\n"; 
    		} else {
    			echo "==> No Data records found for the year " . $value . " - " . date('Y-m-d H:i:s') . "\n";
    		}
    		echo "==> Completed tranformation for the year " . $value . " - " . date('Y-m-d H:i:s') . "\n\n\n";
    	}
    	return true;
    }
}
