<?php

namespace App;

use App\Logs\TimeEntry;
use App\Temps\TempUGSalesLine;
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

    public static function importData()
    {

    }

    public static function scheduledImportData()
    {
        $year = date('Y');
        $month = date('m');
        // Delete existing data
        echo "==> Deleting existing data \n";
        $existing_data = SalesInvoiceCreditMemoLine::whereYear('SI_Li_Posting_Date', $year)
                            ->whereMonth('SI_Li_Posting_Date', $month)->get();
        foreach ($existing_data as $key => $line) {
            $line->delete();
        }

        /** Pulling in the source data **/
        echo "==> Pulling Source data \n";
        $source_start = NULL;
        $source_start = date('Y-m-d H:i:s', strtotime("+3 Hours", strtotime(date('Y-m-d H:i:s'))));
        $start_date = $year . '-' . $month . '-01';
        $final_date = date('Y-m-d');
        TempUGSalesLine::insertData($start_date, $final_date);
        $source_data = TempUGSalesLine::whereYear('Posting_Date', $year)
                            ->whereMonth('Posting_Date', $month)->get();
        $source_end = date('Y-m-d H:i:s', strtotime("+3 Hours", strtotime(date('Y-m-d H:i:s'))));

        /*** Bring In the new Data ***/
        echo "==> Inserting data into the warehouse (Count: {$source_data->count()}) \n";
        $destination_start = date('Y-m-d H:i:s', strtotime("+3 Hours", strtotime(date('Y-m-d H:i:s'))));
        foreach ($source_data as $key => $entry) {
            SalesInvoiceCreditMemoLine::create([
                'SI_Li_Line_No' => $entry->Entry_No . '-' . $entry->LineNum,
                'Invoice_Credit_Memo_No' => $entry->Document_No,
                'SI_Li_Document_No' => $entry->Document_No,
                'Item_No' => $entry->ItemCode,
                'Item_Weight_kg' => $entry->Item_Weight_in_kg,
                'Item_Price_kg' => $entry->Item_Price_in_kg,
                'Item_Description' => $entry->Item_Description,
                'Quantity' => $entry->Quantity,
                'Unit_Price' => $entry->Unit_Price,
                'Unit_Cost' => $entry->Unit_Cost,
                'Company_Code' => $entry->Company_Code,
                'Currency_Code' => $entry->Currency_Code,
                'Type' => $entry->Type,
                'Total_Amount_Excluding_Tax' => $entry->Total_Amount_Excluding_Tax,
                'Total_Amount_Including_Tax' => $entry->Total_Amount_Including_Tax,
                'Sales_Unit_of_Measure' => $entry->Sales_Unit_of_Measure,
                'SI_Li_Posting_Date' => $entry->SI_Li_Posting_Date,
                'SI_Li_Due_Date' => $entry->SI_Li_Due_Date,
            ]);
        }
        $destination_end = date('Y-m-d H:i:s', strtotime("+3 Hours", strtotime(date('Y-m-d H:i:s'))));

        /** Record entry complete **/
        echo "==> Making time entry \n";
        TimeEntry::create([
            'source' => TempUGSalesLine::class,
            'destination' => SalesInvoiceCreditMemoLine::class,
            'Country' => 'UG',
            'source_start_time' => $source_start,
            'source_end_time' => $source_end,
            'destination_start_time' => $destination_start,
            'destination_end_time' => $destination_end,
        ]);
        return true;
    }
}