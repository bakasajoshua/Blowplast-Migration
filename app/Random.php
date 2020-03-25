<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Imports\BlowplastImport;
use App\Imports\GLEntriesSheetImport;
use App\Imports\GLEntriesUpdateSheetImport;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class Random extends Model
{
    public static function import()
    {
    	

    	// Open a try/catch block
		try {
		    // Begin a transaction
		    DB::beginTransaction();

		    // echo "==> Truncating tables\n";
	    	// SalesInvoiceCreditMemoHeader::truncate();
	    	// SalesInvoiceCreditMemoLine::truncate();
	    	// CustomerLedgerEntry::truncate();
	    	// GLAccounts::truncate();
	    	// GLEntries::truncate();

	    	echo "==> Inserting the general data\n";
	    	(new BlowplastImport)->import(public_path('import/blowplast.xlsx'));
	    	
	    	// ini_set("memory_limit", "-1");
	    	// echo "==> Inserting the GL entries data\n";
	    	// Excel::import(new GLEntriesSheetImport, public_path('import/glentries.csv'));
	    	// // Excel::import(new GLEntriesUpdateSheetImport, public_path('import/postingdates2.xlsx'));

	    	echo "==> Data import complete\n";

		    // Commit the transaction
		    DB::commit();
		} catch (\Exception $e) {
		    // An error occured; cancel the transaction...
		    DB::rollback();

		    // and throw the error again.
		    throw $e;
		}
    }

    public static function soap()
    {
    	$models = ['Customer','CustomerLedgerEntry','GLAccounts','GLEntries','Inventory','SalesInvoiceCreditMemoHeader','SalesInvoiceCreditMemoLine'];
    	foreach ($models as $key => $model) {
    		self::make_soap_call($model);
    	}
    }

    private static function make_soap_call($class)
    {
    	$class = "/App/" . $class;
    	echo "==> Get the {$class}\n";
    	$model = new $class;
    	print_r($inv->getFromApi());
    }
}
