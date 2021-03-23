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

class GLCategoryImport implements ToCollection, WithHeadingRow, WithProgressBar 
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
    	
        dd($collection);
    }
}
