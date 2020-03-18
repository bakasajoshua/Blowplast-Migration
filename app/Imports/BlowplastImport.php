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
            'Inventory' => new InventorySheetImport(),
            // 'sales invoicecredit memo header' => new SalesInvoiceCreditMemoHeaderSheetImport(),
            // 'sales line' => new SalesInvoiceCreditMemoLineSheetImport(),
            // 'Gl Accounts' => new GLAccountsSheetImport(),
        ];
    }
}