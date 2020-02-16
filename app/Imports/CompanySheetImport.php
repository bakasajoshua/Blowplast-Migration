<?php

namespace App\Imports;

use App\Company;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CompanySheetImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Company([
            "Company_Code" => $row["company_code"],
            "Company_Name" => $row["company_name"],
            "Local_Currency_Code" => $row["local_currency_code"],
            "Country_Code" => $row["country_code"],
        ]);
    }
}
