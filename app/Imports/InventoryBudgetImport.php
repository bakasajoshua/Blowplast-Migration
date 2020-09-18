<?php

namespace App\Imports;

use App\Customer;
use App\Inventory;
use App\InventoryBudget;
use App\Temps\Temp;

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
            $itemCheckTemp = Temp::where('itm_desc', $row['prod'])->get();
            if ($itemCheckTemp->isEmpty()) {
                $item = Inventory::create([
                        'Item_No' => round(microtime(true) * 1000),
                        'Item_Description' => $row['prod'],
                        'Company_Code' => 'BPL',
                        'Dimension1' => $row['vs'],
                    ]);
            } else {
                $newitem = $itemCheckTemp->first();
                $item = Inventory::create([
                            'Item_No' => $newitem->itm_id,
                            'Item_Description' => $newitem->itm_desc,
                            'Company_Code' => 'BPL',
                            'Dimension1' => $row['vs'],
                        ]);
            }    		
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
			if(!in_array($key, ['vs', 'customer', 'wt_pc_g', 'price_kg', 'prod', 'price_pc', 'price', "wt_pc_repeat_g", "price_q1", "price_q2", "price_q3", "price_q4", "period", ""])) {
                
                $year = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($key))->format('Y');
                $month = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($key))->format('Y/m');
                $actualmonth = (int)Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($key))->format('m');
				
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
                        'Budget_Revenue' => $this->computePricing($row, $value, $actualmonth),
					]);
                
			}
		}
        
    	return $budgetItem;
    }

    private function computePricing($row, $value, $month)
    {
        $price_key = $this->getQuarterly($row, $month);
        return ((float)$row[$price_key] * (float)$value);      
    }

    private function getQuarterly($row, $month)
    {
        if ($row['period'] == "QUARTERLY") {
            $quarters = [
                'price_q1' => [1, 2, 3],
                'price_q2' => [4, 5, 6],
                'price_q3' => [7, 8, 9],
                'price_q4' => [10, 11, 12]
            ];
            foreach ($quarters as $key => $quarter) {
                if (in_array($month, $quarter))
                    return $key;
            }
        }
        return 'price';
    }
}