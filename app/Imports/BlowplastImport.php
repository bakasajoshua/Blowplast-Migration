<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BlowplastImport implements WithMultipleSheets 
{
    /**
    * 
    */
    public function sheets(): array
    {
        return [
            'Country' => new CountrySheetImport(),
            'Company' => new CompanySheetImport(),
            'Currency' => new CurrencySheetImport(),
            'Customers' => new CustomerSheetImport(),
            'Customer Ledger Entries' => new CustomerLedgerEntriesSheetImport(),
            // 'Inventory' => new InventorySheetImport(),
            // 'GL Accounts' =>  new GLAccountsSheetImport(),
            // 'GL Entries' => new GLEntriesSheetImport(),
            'sales invoicecredit memo header' => new SalesInvoiceCreditMemoHeaderSheetImport(),
            'sales line' => new SalesInvoiceCreditMemoLineSheetImport(),
        ];
    }
}