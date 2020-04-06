<?php

namespace App\Imports;

use App\AccountType;
use App\ChartOfAccounts;
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
        $row['gl_account_level_1'] = NULL;
        $row['gl_account_level_2'] = NULL;
        $row['gl_account_level_3'] = NULL;

        // Adding the level 1
        $level_1 = AccountType::where('Account_Type', $row["gl_account_type"])->get();
        if (!$level_1->isEmpty())
            $row['gl_account_level_1'] = $level_1->first()->Account_Type_No;

        // Adding the level 2
        $level_2 = ChartOfAccounts::where('Chart_of_Account_Group', $row["chart_of_account_group"])->get();
        if (!$level_2->isEmpty())
            $row['gl_account_level_2'] = $level_2->first()->Chart_of_Account_Group;

        // dd($row);
        return new GLAccounts([
            "GL_Account_No" => $row["gl_account_no"],
            "GL_Account_Name" => $row["gl_account_name"],
            // "GL_Account_Type" => $row["gl_account_type"],
            "GL_Account_Level_1" => $row['gl_account_level_1'],
            "GL_Account_Level_2" => $row['gl_account_level_2'],
            "GL_Account_Level_3" => $row['gl_account_level_3'],
            "Income_Balance" => $row["income_balance"],
            // "COA_Group" => $row["chart_of_account_group"],
            // "COA_Group_Name" => $row["chart_of_account_group_name"],
            "Blocked" => $row["blocked"],
            "Company_Code" => $row["company_code"],
        ]);
    }

    public function chunkSize(): int
    {
        return 10000;
    }
}