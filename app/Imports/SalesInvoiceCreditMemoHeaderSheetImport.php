<?php

namespace App\Imports;

use App\SalesInvoiceCreditMemoHeader;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class SalesInvoiceCreditMemoHeaderSheetImport implements ToModel, WithHeadingRow
{

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);
        $date = '2019-01-13';
        return new SalesInvoiceCreditMemoHeader([
                "Invoice_Credit_Memo_No" => $row["invoice_credit_memo_no"],
                "Document_No" => $row["document_no"],
                "Sell-To-Customer-No" => $row["sell_to_cust_no"],
                "Sell-To-Customer-Name" => $row["sell_to_cust_name"],
                "Bill-To-Customer-No" => $row["bill_to_cust_no"],
                "Bill-To-Customer-Name" => $row["bill_to_cust_name"],
                // "Posting_Date" => $row["posting_date"],
                // "Due_Date" => $row["order_date"],
                // "Order_Date" => $row["due_date"],
                "Posting_Date" => $date,
                "Due_Date" => date("Y-m-d", strtotime(date("Y-m-d", strtotime($date)) . " + 1 year")),
                "Order_Date" => $date,
                "Company_Code" => $row["company_code"],
                "Type" => $row["type"],
                "Total_Amount_Excluding_Tax" => $row["total_amount_excluding_tax"],
                "Total_Amount_Including_Tax" => $row["total_amount_including_tax"],
                "Currency_Code" => $row["currency_code"],
        ]);
    }
}



