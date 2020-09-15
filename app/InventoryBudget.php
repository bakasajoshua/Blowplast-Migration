<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryBudget extends Model
{
    protected $table = 'Item Budget';

    protected $guarded = [];

    public $timestamps = false;

    protected $primaryKey = 'Inventory_Budget_No';

    public static function harmonizeItemNo()
    {
    	// $budgetItems = InventoryBudget::
    	return true;
    }

    public static function harmonizeItemNoKE()
    {
    	$budgetItems = InventoryBudget::where('Company_Code', 'BPL')->get()->unique('Item_Description');
        foreach ($budgetItems as $key => $budgetItem) {
            $item = Inventory::where('Item_Description', $budgetItem->Item_Description)
                        ->where('Company_Code', $budgetItem->Company_Code)->get();
            if (!$item->isEmpty()){
                $budgetItem->Item_No = $item->first()->Item_No;
                $budgetItem->save();
            }
        }
        return true;
    }
}
