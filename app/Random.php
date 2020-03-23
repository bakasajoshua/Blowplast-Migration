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
    	// Excel::import(new BlowplastImport, public_path('import/blowplast.xlsx'));
    	// Excel::import(new GLEntriesSheetImport, public_path('import/glentries.csv'));
    	Excel::import(new GLEntriesUpdateSheetImport, public_path('import/postingdates2.xlsx'));
    }
}
