<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesInvoiceCreditMemoLine extends BaseModel
{
    protected $table = 'Sales Invoice Credit Memo Lines';

    protected $guarded = [];

    public $timestamps = false;

    private $functionCall = "GetInvoiceCreditLine";

    private $endpointColumns = [
        'SI_Li_Line_No' => 'LineNum',
        'Invoice_Credit_Memo_No' => 'Document_x0020_No',
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

    public function insertKESalesLines($empty=false)
    {
        ini_set("memory_limit", "-1");
        if ($empty)
            SalesInvoiceCreditMemoLine::truncate();
        foreach (Temp::get() as $key => $sales) {
            // if (SalesInvoiceCreditMemoHeader::where('Invoice_Credit_Memo_No', $sales->invoice_id)->get()->isEmpty()) {
                SalesInvoiceCreditMemoLine::create([
                    'Invoice_Credit_Memo_No' => $sales->invoice_id,
                    'SI_Li_Document_No' => $sales->invoice_doc_id,
                    'SI_Li_Line_No' => $sales->shipmnt_id,
                    'Item_No' => $sales->itm_id,
                    'Item_Description' => $sales->itm_desc,
                    'Item_Weight_kg' => $sales->std_weight,
                    'Item_Price_kg' => $sales->price_per_kg,
                    'SI_Li_Posting_Date' => date('Y-m-d', strtotime($sales->invoice_doc_dt)),
                    'Company_Code' => 'BPL',
                    'Quantity' => $sales->itm_ship_qty,
                    'Unit_Price' => $sales->itm_cost,
                    'Unit_Cost' => $sales->itm_cost,
                    'Type' => ucwords($sales->inv_type_desc),
                    'Total_Amount_Excluding_Tax' => $sales->itm_amt_gs,
                    'Total_Amount_Including_Tax' => $sales->net_amnt,
                    'Sales_Unit_of_Measure' => $sales->uom_sls,
                    'Currency_Code' => $sales->curr_sp,
                ]);
            // }
        }
        return true;
    }

    public function insertMissingItems()
    {
        ini_set("memory_limit", "-1");
        $items = SalesInvoiceCreditMemoLine::get()->unique('Item_No');
        $newItems = [];
        foreach ($items as $key => $item) {
            $dbitem = Inventory::where('Item_No', $item->Item_No)->get();
            if ($dbitem->isEmpty()) {
                $newItems[] = [
                    'Item_No' => $item->Item_No,
                    'Item_Description' => $item->Item_Description,
                    'Company_Code' => $item->Company_Code
                ];
            }
        }
        $chunks = collect($newItems)->chunk($this->chunkQty);
        foreach ($chunks as $key => $chunk) {
            Inventory::insert($chunk->toArray());
        }
        return true;
    }
}