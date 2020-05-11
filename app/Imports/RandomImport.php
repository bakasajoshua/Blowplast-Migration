<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;

class RandomImport implements ToCollection, WithHeadingRow
{
	use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
    	// $vehicles = [];
    	// foreach ($collection as $key => $vehicle) {
    	// 	unset($vehicle['age']);
    	// 	$vehicle['user_id'] = 1043;
    	// 	$vehicles[] = $vehicle->toArray();
    	// }
    	// DB::table('vehicle_details')->insert($vehicles);
    	
        // dd($vehicles);
        $tyres = [];
        foreach ($collection as $key => $tyre) {
            # code...
        }
    }
}

