<?php

namespace App\Imports;

use App\GLEntries;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class GLEntriesUpdateSheetImport implements ToModel, WithHeadingRow, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
    	$gl_entries = GLEntries::find($row['id']);
    	$gl_entries->Posting_Date = $row['posting_date'];

        return $gl_entries->save();
    }

    public function chunkSize(): int
    {
        return 10000;
    }
}