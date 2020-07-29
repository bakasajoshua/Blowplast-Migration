<?php

namespace App\Temps;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class TempUGSalesLine extends BaseModel
{
	protected $connection = 'testdb';

    protected $guarded = [];

    private $functionCall = "GetInvoiceCreditLine";

    private $endpointColumns = [
    		'Entry_No' => 'Entry_x0020_No',
			'LineNum' => 'LineNum',
			'Document_No' => 'Document_x0020_No',
			'ItemCode' => 'ItemCode',
			'Item_Weight_in_kg' => 'Item_x0020_Weight_x0020_in_x0020_kg',
			'Item_Price_in_kg' => 'Item_x0020_Price_x0020_in_x0020_kg',
			'Item_Description' => 'Item_x0020_Description',
			'Quantity' => 'Quantity',
			'Unit_Price' => 'Unit_x0020_Price',
			'Unit_Cost' => 'Unit_x0020_Cost',
			'Total_Amount_Excluding_Tax' => 'Total_x0020_Amount_x0020_Excluding_x0020_Tax',
			'Total_Amount_Including_Tax' => 'Total_x0020_Amount_x0020_Including_x0020_Tax',
			'Sales_Unit_of_Measure' => 'Sales_x0020_Unit_x0020_of_x0020_Measure',
			'Type' => 'Type',
			'Company_Code' => 'Company_x0020_Code',
			'Currency_Code' => 'Currency_x0020_Code',
			'Posting_Date' => 'Posting_x0020_Date',
			'Due_Date' => 'Due_x0020_Date',
		];

    private $chunkQty = 100;

    public function synchLines($params = [])
    {
        ini_set("memory_limit", "-1");
        $chunks = $this->synch($this->functionCall, $this->endpointColumns, $params)->chunk($this->chunkQty);
        foreach ($chunks as $key => $data) {
            TempUGSalesLine::insert($data->toArray());
        }
        return true;
    }

    public static function insertData($start_date, $final_date)
    {
    	$sl = new TempUGSalesLine;
    	$sl->processImportData(TempUGSalesLine::class, 'synchLines', $start_date, $final_date, 10);
    	return true;
    }
}