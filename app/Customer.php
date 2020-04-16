<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'Customer Master';

    protected $guarded = [];

    public $timestamps = false;

    private $functionCall = "GetCustomers";

    public function getFromApi()
    {
        return SoapCli::call($this->functionCall);
    }

    public function getData()
    {
    	$z = new \XMLReader;
		$z->open(public_path('data/customer.xml'));
		$doc = new \DOMDocument;
		$data = [];
		$count = 0;
		// move to the first <product /> node
		while ($z->read())
		{
			while ($z->name == "Table") {
				$node = simplexml_import_dom($doc->importNode($z->expand(), true));
				$data[] = [
						'Customer_No' => collect((array)$node->Customer_x0020_No)->first(),
						'Customer_Name' => collect((array)$node->Customer_x0020_Name)->first() ?? '',
						'Company_Code' => collect((array)$node->Company_x0020_Code)->first() ?? 'BUL'
					];
		    	$z->next('Table');
			}
		}
		dd(collect($data));
    }
}
