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
        // dd($collection);
        
        $tyres = [];
        foreach ($collection as $key => $tyre) {
            // DB::table('tyre_details')->create($tyre->toArray());
            $tyres[] = $tyre->toArray();
        }
        // dd($tyres);
        DB::table('tyre_details')->insert($tyres);
        return $tyres;
    }
}

//https://blogs.sap.com/2016/01/14/sap-business-one-tables/