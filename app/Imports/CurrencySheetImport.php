<?php

namespace App\Imports;

use App\Currency;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CurrencySheetImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Currency([
            "Currency_Code" => $row["country_code"],
            "Country_Code" => $row["currency_code"],
            "Exchange_Rate" => $row["exchange_rate"],
        ]);
    }
}
