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
            "Level_1_Description" => $row["gl_account_type"],
            "bs_is" => $row["bs_is"],
        ]);
    }
}
