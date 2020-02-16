<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Imports\BlowplastImport;
use Maatwebsite\Excel\Facades\Excel;

class Random extends Model
{
    public static function import()
    {
    	Excel::import(new BlowplastImport, public_path('import/blowplast.xlsx'));
    }
}
