<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends BaseModel
{
    protected $table = 'Customer Master';

    protected $guarded = [];

    public $timestamps = false;

    private $functionCall = "GetCustomers";

    private $endpointColumns = [
    				'Customer_No' => 'Customer_x0020_No',
    				'Customer_Name' => 'Customer_x0020_Name',
    				'Company_Code' => 'Company_x0020_Code'
    			];
    private $chunkQty = 100;

    public function synchCustomer()
    {
    	$synchData = $this->synch($this->functionCall, $this->endpointColumns);
        $customers = [];

        foreach ($synchData as $key => $customer) {
            $customer['Company_Code'] = 'BUL';
            $customers[] = $customer;
        }

        $chunks = collect($customers);
        foreach($chunks as $key => $chunk){
            Customer::insert($chunk);
        }
    	return true;
    }
}
