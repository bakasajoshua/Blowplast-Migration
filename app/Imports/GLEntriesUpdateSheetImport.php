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
    	dd($posting_date);
    	$gl_entries = GLEntries::find($row['id']);
    	$gl_entries->Posting_Date = $row['posting_date'];
    	dd($gl_entries);
        return $gl_entries->save();
    }

    public function chunkSize(): int
    {
        return 10000;
    }
}