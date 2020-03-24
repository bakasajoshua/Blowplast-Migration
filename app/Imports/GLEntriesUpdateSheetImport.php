<?php

namespace App\Imports;

use App\GLEntries;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Carbon\Carbon;

class GLEntriesUpdateSheetImport implements ToModel, WithHeadingRow, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
    	$posting_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['posting_date']))->format('Y-m-d');
    	$gl_entries = GLEntries::where('GL_Account_No', $row['gl_account_no'])->first();
    	if (null !== $gl_entries) {
    		$gl_entries->GL_Posting_Date = $posting_date;
	    	$gl_entries->save();
    	}
	    return $gl_entries;
    }

    public function chunkSize(): int
    {
        return 10000;
    }
}