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
	            "Chart_of_Account_Group" => $row['chart_of_account_group'],
	            "Chart_of_Account_Group_Name" => $row['chart_of_account_group_name'],
	            "LU_GL_Accounts_Level_1" => $row["lu_gl_accounts_level_1"],
	        ]);
    }
}
