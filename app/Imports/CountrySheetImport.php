<?php

namespace App\Imports;

use App\Country;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CountrySheetImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Country([
            'Country_Code' => $row['country_code'],
            'Country_Name' => $row['country_name'],
        ]);
    }
}
