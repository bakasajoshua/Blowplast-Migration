<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Customer;
use App\CustomerBudget;

class CustomerBudgetImport implements ToCollection, WithHeadingRow, WithProgressBar 
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $item) {
        	$customer = Customer::where('Customer_Name', $item['customer_name'])->first();
        	CustomerBudget::create([
        				'Customer_Budget_No' => round(microtime(true) * 1000),
        				'Customer_No' => $customer->Customer_No,
        				'Company_Code' => 'BPL',
        				'Budget_Year' => $item['year'],
        				'Budget_Month' => $item['month'],
        				'Budget_Qty_Pcs' => $item['target_pieces'] ?? 0,
        				'Budget_Qty_Weight' => $item['target_weight'] ?? 0,
        			]);
        }
        return $collection;
    }
}