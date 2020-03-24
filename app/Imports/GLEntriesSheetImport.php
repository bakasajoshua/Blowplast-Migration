<?php

namespace App\Imports;

use App\GLEntries;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Carbon\Carbon;

class GLEntriesSheetImport implements ToModel, WithHeadingRow, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $posting_date = null;
        $csv_date = explode("/", $row['posting_date']);
        if (sizeof($csv_date) == 3)
            $posting_date = $csv_date[2] . '-' . $csv_date[1] . '-' . $csv_date[0];
        // dd($row);
        // $posting_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['posting_date']))->format('Y-m-d');
        dd($posting_date);
        return new GLEntries([
            // "Entry_No" => $row["entry_no"],
            "GL_Account_No" => $row["gl_account_no"],
            "Balancing_GL_Account_No" => $row["balancing_gl_account_no"],
            "Amounts" => $row["amounts"],
            "Currency_Code" => $row["currency_code"],
            "GL_Posting_Date" => $row['posting_date'],
            "GL_Document_No" => $row["document_no"],
            "GL_Document_Type" => $row["document_type"],
            "Description" => $row["description"],
            "Company_Code" => $row["company_code"],
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}