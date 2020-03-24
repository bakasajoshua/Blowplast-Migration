<?php

namespace App\Imports;

use App\CustomerLedgerEntry;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerLedgerEntriesSheetImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $posting_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['posting_date']))->format('Y-m-d');
        $due_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['due_date']))->format('Y-m-d');
        return new CustomerLedgerEntry([
            // "Entry_No" => $row["entry_no"],
            "Document_No" => $row["document_no"],
            "Customer_No" => $row["customer_no"],
            "Posting_Date" => $posting_date,
            "Due_Date" => $due_date,
            "Sell-To-Customer-No" => $row["sell_to_customer_no"],
            "Sell-To-Customer-Name" => $row["sell_to_customer_name"],
            "Bill-To-Customer-No" => $row["bill_to_customer_no"],
            "Bill-To-Customer-Name" => $row["bill_to_customer_name"],
            "Original_Amount_LCY" => $row["original_amount_lcy"],
            "Original_Amount" => $row["original_amount"],
            "Currency_Code" => $row["currency_code"],
            "Currency_Factor" => $row["currency_factor"],
            "Remaining_Amount_LCY" => $row["remaining_amount_lcy"],
            "Remaining_Amount" => $row["remaining_amount"],
            "Open" => $row["open"],
        ]);
    }
}