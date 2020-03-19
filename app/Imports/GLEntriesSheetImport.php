<?php

namespace App\Imports;

use App\GLEntries;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class GLEntriesSheetImport implements ToModel, WithHeadingRow, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new GLEntries([
            // "Entry_No" => $row["entry_no"],
            "GL_Account_No" => $row["gl_account_no"],
            "Balancing_GL_Account_No" => $row["balancing_gl_account_no"],
            "Amounts" => $row["amounts"],
            "Currency_Code" => $row["currency_code"],
            "Posting_Date" => $row["posting_date"],
            "Document_No" => $row["document_no"],
            "Document_Type" => $row["document_type"],
            "Description" => $row["description"],
            "Company_Code" => $row["company_code"],
        ]);
    }

    public function chunkSize(): int
    {
        return 10000;
    }
}