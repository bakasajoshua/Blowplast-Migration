<?php

namespace App\Imports;

use App\AccountType;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AccountTypesSheetImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new AccountType([
            "Account_Type" => $row["gl_account_type"],
        ]);
    }
}
