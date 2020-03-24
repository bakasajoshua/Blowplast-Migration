<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class BlowplastImport implements WithMultipleSheets, WithProgressBar 
{
    use Importable;
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
            'Inventory' => new InventorySheetImport(),
            'sales invoicecredit memo header' => new SalesInvoiceCreditMemoHeaderSheetImport(),
            'sales line' => new SalesInvoiceCreditMemoLineSheetImport(),
            'Customer Ledger Entries' => new CustomerLedgerEntriesSheetImport(),
            'GL Accounts' =>  new GLAccountsSheetImport(),
        ];
    }
}