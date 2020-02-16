<?php

namespace App\Imports;

use App\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerSheetImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Customer([
            "Customer_No" => $row["customer_no"],
            "Customer_Name" => $row["customer"],
            "Customer_Email" => $row["customer_email"],
            "Company_Code" => $row["company_code"],
        ]);
    }
}
