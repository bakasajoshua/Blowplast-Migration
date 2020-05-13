<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesInvoiceCreditMemoLine extends Model
{
    protected $table = 'Sales Invoice Credit Memo Lines';

    protected $guarded = [];

    public $timestamps = false;

    private $functionCall = "GetInvoiceCreditLine";

    private $endpointColumns = [
        'SI_Li_Line_No' => 'LineNum',
		'SI_Li_Document_No' => 'Document_x0020_No',
		'Item_No' => 'ItemCode',
		'Item_Weight_kg' => 'Item_x0020_Weight_x0020_in_x0020_kg',
		'Item_Price_kg' => 'Item_x0020_Price_x0020_in_x0020_kg',
		'Item_Description' => 'Item_x0020_Description',
		'Quantity' => 'Quantity',
		'Unit_Price' => 'Unit_x0020_Price',
		'Unit_Cost' => 'Unit_x0020_Cost',
		'Company_Code' => 'Company_x0020_Code',
		'Currency_Code' => 'Currency_x0020_Code',
		'Type' => 'Type',
		'Total_Amount_Excluding_Tax' => 'Total_x0020_Amount_x0020_Excluding_x0020_Tax',
		'Total_Amount_Including_Tax' => 'Total_x0020_Amount_x0020_Including_x0020_Tax',
		'Sales_Unit_of_Measure' => 'Sales_x0020_Unit_x0020_of_x0020_Measure',
		'SI_Li_Posting_Date' => 'Posting_x0020_Date',
		'SI_Li_Due_Date' => 'Due_x0020_Date',
    ];
    private $chunkQty = 100;

    public function synchLines($params = [])
    {
        ini_set("memory_limit", "-1");
        $chunks = $this->synch($this->functionCall, $this->endpointColumns, $params)->chunk($this->chunkQty);
        foreach ($chunks as $key => $data) {
            SalesInvoiceCreditMemoLine::insert($data->toArray());
        }
        return true;
    }
}