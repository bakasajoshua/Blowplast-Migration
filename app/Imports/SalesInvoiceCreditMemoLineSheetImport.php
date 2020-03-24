<?php

namespace App\Imports;

use App\SalesInvoiceCreditMemoLine;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class SalesInvoiceCreditMemoLineSheetImport implements ToModel, WithHeadingRow
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
        $order_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['order_date']))->format('Y-m-d');
        return new SalesInvoiceCreditMemoLine([
            "SI_Li_Line_No" => $row["line_no"],
            "Invoice_Credit_Memo_No" => $row["invoice_credit_memo_no"],
            "SI_Li_Document_No" => $row["document_no"],
            "Item_No" => $row["item_no"],
            "Item_Weight_kg" => $row["item_weight_in_kg"],
            "Item_Price_kg" => $row["item_price_in_kg"],
            "Item_Description" => $row["item_description"],
            "Quantity" => $row["quantity"],
            "Unit_Price" => $row["unit_price"],
            "Unit_Cost" => $row["unit_cost"],
            "Company_Code" => $row["company_code"],
            "Currency_Code" => $row["currency_code"],
            "Type" => $row["type"],
            "Total_Amount_Excluding_Tax" => $row["total_amount_excluding_tax"],
            "Total_Amount_Including_Tax" => $row["total_amount_including_tax"],
            "Sales_Unit_of_Measure" => $row["sales_unit_of_measure"],
            // "Posting_Date" => $row["posting_date"],
            // "Due_Date" => $row["order_date"],
            // "Order_Date" => $row["due_date"],
            "SI_Li_Posting_Date" => $posting_date ?? NULL,
            "SI_Li_Due_Date" => $due_date ?? NULL,
            "SI_Li_Order_Date" => $order_date ?? NULL,
        ]);
    }
}