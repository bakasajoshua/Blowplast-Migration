<?php

namespace App\Temps;

use App\Customer;
use Illuminate\Database\Eloquent\Model;
use DB;

class MasterCustomer extends Model
{
    protected $connection = 'mysql';

    protected $table = 'new_customer_master';

    // \App\Temps\MasterCustomer::mapCustomers();
    public static function mapCustomers()
    {
    	foreach (MasterCustomer::get() as $key => $line) {
    		$customer = Customer::where('Customer_Name', $line->CUSTOMER_NAME)->where('Company_Code', 'BPL')->get();
    		if ($customer->isEmpty()) {
    			Customer::create([
    				'Customer_No' => $line->CUSTOMER_NO,
    				'Customer_Name' => $line->CUSTOMER_NAME,
    				'Company_Code' => 'BPL',
    				'Value_Stream' => $line->VALUE_STREAM,
    			]);
    		} else {
    			$customer = $customer->first();
    			$customer->Value_Stream = $line->VALUE_STREAM;
    			$customer->save();
    		}
    	}

        DB::statement("UPDATE [Customer Master] SET Value_Stream = 'UnCategorized' WHERE [Value_Stream] IS NULL;");
    	return true;
    }
}