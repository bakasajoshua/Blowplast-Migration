<?php

namespace App\Imports;

use App\Customer;
use App\Inventory;
use App\InventoryBudget;

use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Maatwebsite\Excel\Concerns\WithChunkReading;

class InventoryBudgetImport implements  ToModel, WithHeadingRow, WithProgressBar
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
    	// Check or create inventory
    	$itemCheck = Inventory::where('Item_Description', $row['prod'])->where('Company_Code', 'BPL')->get();
    	if ($itemCheck->isEmpty()){
    		$item = Inventory::create([
    					'Item_No' => round(microtime(true) * 1000),
    					'Item_Description' => $row['prod'],
    					'Company_Code' => 'BPL',
    					'Dimension1' => $row['vs'],
    				]);
    	} else {
    		$item = $itemCheck->first();
    	}

    	// Check or create customer
    	$customerCheck = Customer::where('Customer_Name', $row['customer'])->where('Company_Code', 'BPL')->get();
    	if ($customerCheck->isEmpty()){
    		$customer = Customer::create([
    						'Customer_No' => round(microtime(true) * 1000),
    						'Customer_Name' => $row['customer'],
    						'Company_Code' => 'BPL'
    					]);
    	} else {
    		$customer = $customerCheck->first();
    	}

    	// Build budget lines
        
		$data = [];
        $budgetItem = [];
		foreach ($row as $key => $value) {
			if(!in_array($key, ['vs', 'customer', 'wt_pc_g', 'price_kg', 'prod', 'price_pc', 'price'])) {
                $year = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($key))->format('Y');
                $month = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($key))->format('Y/m');
				
				$budgetItem = InventoryBudget::create([
			            'Value_Stream' => $row['vs'],
			            'Item_Description' => $item->Item_Description,
			            'Item_No' => $item->Item_No,
			            'Customer_Name' => $customer->Customer_Name,
			            'Customer_No' => $customer->Customer_No,
			            'Company_Code' => $item->Company_Code,
						'Budget_Year' => $year,
						'Budget_Month' => $month,
						'Budget_Qty_Pcs' => $value,
						'Budget_Qty_Weight' => ((float)$value * (float)$row['wt_pc_g']/1000),
                        'Budget_Revenue' => ((float)$row['price'] * (float)$value),
					]);
                
			}
		}
        
    	return $budgetItem;
    }
}
