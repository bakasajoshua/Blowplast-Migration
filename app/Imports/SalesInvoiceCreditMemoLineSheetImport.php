<?php

namespace App\Imports;

use App\SalesInvoiceCreditMemoLine;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SalesInvoiceCreditMemoLineSheetImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $date = '2019-01-13';
        return new SalesInvoiceCreditMemoLine([
            "Line_No" => $row["line_no"],
            "Invoice_Credit_Memo_No" => $row["invoice_credit_memo_no"],
            "Document_No" => $row["document_no"],
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
            "Posting_Date" => $date,
            "Due_Date" => date("Y-m-d", strtotime(date("Y-m-d", strtotime($date)) . " + 1 year")),
            "Order_Date" => $date,
        ]);
    }
}