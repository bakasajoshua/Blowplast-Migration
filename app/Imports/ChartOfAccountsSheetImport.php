<?php

namespace App\Imports;

use App\ChartOfAccounts;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ChartOfAccountsSheetImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
    	// dd($row);
    	if (null !== $row["lu_gl_accounts_level_1"])
	        return new ChartOfAccounts([
	            "Level_2_ID" => $row['chart_of_account_group'],
	            "Level_2_Description" => $row['chart_of_account_group_name'],
	            "Level_1_ID" => $row["lu_gl_accounts_level_1"],
	        ]);
    }
}
