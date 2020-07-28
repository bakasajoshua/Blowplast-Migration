<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Imports\RandomImport;
use App\Imports\GLEntriesSheetImport;
use App\Imports\GLEntriesUpdateSheetImport;
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
}
