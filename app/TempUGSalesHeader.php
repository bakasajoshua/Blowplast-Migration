<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempUGSalesHeader extends BaseModel
{
	protected $connection = 'testdb';

    protected $guarded = [];

    private $functionCall = "GetInvoiceCreditHeader";

    private $endpointColumns = [
    		'Entry_No' => 'Entry_x0020_No',
			'Customer_No' => 'Customer_x0020_No.',
			'Customer_Name' => 'Customer_x0020_Name',
			'Document_No' => 'Document_x0020_No',
			'Posting_Date' => 'Posting_x0020_Date',
			'Due_Date' => 'Due_x0020_Date',
			'Company_Code' => 'Company_x0020_Code',
			'Type' => 'Type',
			'Total_Amount_Excluding_Tax' => 'Total_x0020_Amount_x0020_Excluding_x0020_Tax',
			'Total_Amount_Including_Tax' => 'Total_x0020_Amount_x0020_Including_x0020_Tax',
			'Currency_Code' => 'Currency_x0020_Code',
		];

    private $chunkQty = 100;

    public function synchHeaders($params = [])
    {
        ini_set("memory_limit", "-1");
        $chunks = $this->synch($this->functionCall, $this->endpointColumns, $params)->chunk($this->chunkQty);
        foreach ($chunks as $key => $data) {
            TempUGSalesHeader::insert($data->toArray());
        }
        return true;
    }

    public function lines()
    {
    	return $this->hasMany(TempUGSalesLine::class, 'Document_No', 'Document_No');
    }
}