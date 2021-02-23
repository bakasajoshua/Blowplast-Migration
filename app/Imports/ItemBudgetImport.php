<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Inventory;
use App\Customer;
use App\InventoryBudget;

class ItemBudgetImport implements ToCollection, WithHeadingRow, WithProgressBar 
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
    	$months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
    	$missing = [];
		foreach ($collection as $key => $item) {
        	$item_master = Inventory::where('Item_Description', $item['description'])->get();
        	if (!$item_master->isEmpty()) {
        		$item_master = $item_master->first();
                $item_no = (string)$item['customer_no'];
        		$customer = Customer::where('Customer_No', $item_no)->first();
                foreach ($months as $key => $month) {
        			$inventory = InventoryBudget::create([
			        				'Value_Stream' => $customer->Value_Stream ?? NULL,
									'Item_Description' => $item_master->Item_Description,
									'Item_No' => $item_master->Item_No,
									'Customer_No' => $customer->Customer_No ?? NULL,
									'Customer_Name' => $customer->Customer_Name ?? NULL,
									'Company_Code' => 'BPL',
									'Budget_Year' => '2021',
									'Budget_Month' => '2021/' . $month,
									'Budget_Qty_Pcs' => $item['monthly_target_in_pcs'],
								]);
			    }
        	} else {
        		$missing[] = $item;
        	}
        }

        dd($missing);
    }
}
