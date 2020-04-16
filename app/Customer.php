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
    	$chunks = $this->synch($this->functionCall, $this->endpointColumns)->chunk($this->chunkQty);
		foreach ($chunks as $key => $data) {
			Customer::insert($data->toArray());
		}
    	return true;
    }
}
