<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Imports\BlowplastImport;
use App\Imports\GLEntriesSheetImport;
use App\Imports\GLEntriesUpdateSheetImport;
use Maatwebsite\Excel\Facades\Excel;

class Random extends Model
{
    public static function import()
    {
    	echo "==> Truncating tables\n";
    	SalesInvoiceCreditMemoHeader::truncate();
    	SalesInvoiceCreditMemoLine::truncate();
    	CustomerLedgerEntry::truncate();
    	GLAccounts::truncate();
    	GLEntries::truncate();

    	echo "==> Inserting the general data\n";
    	Excel::import(new BlowplastImport, public_path('import/blowplast.xlsx'));

    	echo "==> Inserting the GL entries data\n";
    	Excel::import(new GLEntriesSheetImport, public_path('import/glentries.csv'));
    	// Excel::import(new GLEntriesUpdateSheetImport, public_path('import/postingdates2.xlsx'));

    	echo "==> Data import complete\n";
    }
}
