<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Imports\RandomImport;
use App\Imports\GLEntriesSheetImport;
use App\Imports\GLEntriesUpdateSheetImport;
use App\Exports\GLAccountsExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class Random extends Model
{
    public static function import()
    {
    	// (new RandomImport)->import(public_path('import/vehicles.xlsx'));
    	(new RandomImport)->import(public_path('import/tyres_details.xlsx'));
  //   	// Open a try/catch block
		// try {
		//     // Begin a transaction
		//     DB::beginTransaction();

		//     // echo "==> Truncating tables\n";
	 //    	// SalesInvoiceCreditMemoHeader::truncate();
	 //    	// SalesInvoiceCreditMemoLine::truncate();
	 //    	// CustomerLedgerEntry::truncate();
	 //    	// GLAccounts::truncate();
	 //    	// GLEntries::truncate();

	 //    	echo "==> Inserting the vehicle data\n";
	 //    	(new RandomImport)->import(public_path('import/vehicles.xlsx'));
	    	
	 //    	// ini_set("memory_limit", "-1");
	 //    	// echo "==> Inserting the GL entries data\n";
	 //    	// Excel::import(new GLEntriesSheetImport, public_path('import/glentries.csv'));
	 //    	// // Excel::import(new GLEntriesUpdateSheetImport, public_path('import/postingdates2.xlsx'));

	 //    	echo "==> Data import complete\n";

		//     // Commit the transaction
		//     DB::commit();
		// } catch (\Exception $e) {
		//     // An error occured; cancel the transaction...
		//     DB::rollback();

		//     // and throw the error again.
		//     throw $e;
		// }
    }

    public static function test()
    {
    	$date = strtotime('2020-01-01');
    	$dto = new \DateTime();
    	return [
    			'Year' => date('o', $date),
    			'Week' => date('W', $date),
    			'start_date' => $dto->setISODate(date('o', $date), date('W', $date))->format('Y-m-d'),
    			'dis' => date('o/w', $date)
    		];
    }

    public static function testdb()
    {
    	$data = DB::connection('oracle')->select('select * from fin.fin_gl_vw');
    	var_dump($data);
    }

    public static function saveCompanyCode()
    {
    	foreach (AccountType::get() as $key => $account) {
            $account->Company_Code = 'BUL';
            $account->save();
        }
        foreach (ChartOfAccounts::get() as $key => $account) {
            $account->Company_Code = 'BUL';
            $account->save();
        }
        foreach (ChartOfAccountsBreakdown::get() as $key => $account) {
            $account->Company_Code = 'BUL';
            $account->save();
        }
        return true;
    }

    public static function exportGL()
    {
    	Excel::store(new GLAccountsExport, 'GLAccounts.xlsx');
    }

    public static function anything()
    {
        if(!is_dir(storage_path('app/sample/'))) mkdir(storage_path('app/sample/'), 0777);

        $file = fopen(storage_path('app/sample/logs.txt'), "a");
        $writeString = date('Y-m-d H:i:s') . 'This is the entry';
        fwrite($file, $writeString);
        fwrite($file, "\r\n");
        fclose($file);

        return true;
    }

    // public static function updateBalances()
    // {
    //     echo "==> Begin updating the balances " . date('Y-m-d H:i:s') . "\n";
    //     while (true) {
    //         echo "==> Getting the next 500 entries " . date('Y-m-d H:i:s') . "\n";
    //         $entries = GLEntries::whereNull('Opening_Balance')->limit(500)->get();
    //         if ($entries->isEmpty()) {
    //             continue;
    //         }
    //         echo "==> Warehouse entries to be updated " . $entries->count() . " " . date('Y-m-d H:i:s') . "\n";
    //         $entries_ids = $entries->pluck('GL_Entry_No');
    //         echo "==> Get the related temporary entries " . date('Y-m-d H:i:s') . "\n";
    //         $temp_entries = Temps\TempPrevKEGL::whereIn('Voucher No', $entries_ids->toArray())->get();
    //         echo "==> Temp entries found " . $temp_entries->count() . " " . date('Y-m-d H:i:s') . "\n";
    //         echo "==> Updating the data batch warehouse entries " . date('Y-m-d H:i:s') . "\n";
    //         foreach($entries as $entry) {
    //             $temp_entry = $temp_entries->where('Voucher No', $entry->GL_Entry_No);
    //             if (!$temp_entry->isEmpty()) {
    //                 $temp_entry = $temp_entry->first()->toArray();
    //                 $entry->Opening_Balance = $temp_entry['Opening Bal'];
    //                 $entry->Running_Balance = $temp_entry['Running Balance'];
    //                 $entry->save();
    //             }
    //         }
    //         echo "==> Finished Updating the data batch warehouse entries " . date('Y-m-d H:i:s') . "\n";
    //     }
    //     echo "==> Finished updating the balances " . date('Y-m-d H:i:s') . "\n";
    // }
}
