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
        $posting_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['posting_date']))->format('Y-m-d');
        $due_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['due_date']))->format('Y-m-d');
        $order_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['order_date']))->format('Y-m-d');
        return new SalesInvoiceCreditMemoHeader([
                "Invoice_Credit_Memo_No" => $row["invoice_credit_memo_no"],
                "SI_Document_No" => $row["document_no"],
                "Sell-To-Customer-No" => $row["sell_to_cust_no"],
                "Sell-To-Customer-Name" => $row["sell_to_cust_name"],
                "Bill-To-Customer-No" => $row["bill_to_cust_no"],
                "Bill-To-Customer-Name" => $row["bill_to_cust_name"],
                // "Posting_Date" => $row["posting_date"],
                // "Due_Date" => $row["order_date"],
                // "Order_Date" => $row["due_date"],
                "SI_Posting_Date" => $posting_date,
                "SI_Due_Date" => $due_date,
                "SI_Order_Date" => $order_date ?? NULL,
                "Company_Code" => $row["company_code"],
                "Type" => $row["type"],
                "Total_Amount_Excluding_Tax" => $row["total_amount_excluding_tax"],
                "Total_Amount_Including_Tax" => $row["total_amount_including_tax"],
                "Currency_Code" => $row["currency_code"],
        ]);
    }
}



