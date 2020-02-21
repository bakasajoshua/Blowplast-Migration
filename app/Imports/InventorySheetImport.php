<?php

namespace App\Imports;

use App\Inventory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InventorySheetImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Inventory([
            "Item_No" => $row["item_no"],
            "Item_Description" => $row["item_description"],
            "Company_Code" => $row["company_code"],
            // "Customer_No" => $row["customer_code"],
            "Dimension1" => $row["dimention_1"],
            "Dimension2" => $row["dimension_2"],
        ]);
    }
}
