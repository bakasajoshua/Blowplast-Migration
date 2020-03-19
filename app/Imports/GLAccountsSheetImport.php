<?php

namespace App\Imports;

use App\GLAccounts;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class GLAccountsSheetImport implements ToModel, WithHeadingRow, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new GLAccounts([
            "GL_Account_No" => $row["gl_account_no"],
            "GL_Account_Name" => $row["gl_account_name"],
            "GL_Account_Type" => $row["gl_account_type"],
            "Income_Balance" => $row["income_balance"],
            "COA_Group" => $row["chart_of_account_group"],
            "COA_Group_Name" => $row["chart_of_account_group_name"],
            "Blocked" => $row["blocked"],
            "Company_Code" => $row["company_code"],
        ]);
    }

    public function chunkSize(): int
    {
        return 10000;
    }
}