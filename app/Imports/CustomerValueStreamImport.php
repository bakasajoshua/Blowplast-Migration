<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Customer;

class CustomerValueStreamImport implements ToCollection, WithHeadingRow, WithProgressBar 
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
    	$missing_customers = [];
        foreach ($collection as $key => $customer_vs) {
        	$customer = Customer::where('Customer_Name', $customer_vs['customer_name'])
        					->where('Company_Code', 'BPL')->get();
        	if ($customer->isEmpty()) {
        		$missing_customers[] = $customer;
        	} else {
        		$customer = $customer->first();
        		$customer->Value_Stream = strtoupper($customer_vs['value_stream']);
        		$customer->save();
        	}
        }
        return $collection;
    }
}
