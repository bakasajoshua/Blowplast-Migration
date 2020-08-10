<?php

namespace App;

use App\Logs\TimeEntry;
use App\Temps\Temp;
use App\Temps\TempUGSalesLine;
use App\Temps\TempUGSalesHeader;
use App\Mail\DailyScheduledTask;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use DB;

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
    }
    
    public static function importData()
    {

    }

    public static function scheduledImportData()
    {
        ini_set("memory_limit", "-1");
        $year = date('Y');
        $month = date('m');
        $start_date = $year . '-' . $month . '-01';
        $final_date = date('Y-m-d');
        $message = '';

        /*** Delete existing data ***/
        $message .= ">> Deleting existing Sales data " . date('Y-m-d H:i:s') . "\n";
        try {
            echo "==> Deleting existing data " . date('Y-m-d H:i:s') . "\n";
            $existing_data = SalesInvoiceCreditMemoLine::whereBetween('SI_Li_Posting_Date', [$start_date, $final_date])->get();
            foreach ($existing_data as $key => $header) {
                $header->delete();
            }
            $existing_data = SalesInvoiceCreditMemoLine::whereYear('SI_Li_Posting_Date', $year)
                                ->whereMonth('SI_Li_Posting_Date', $month)
                                ->get();
            foreach ($existing_data as $key => $line) {
                $line->delete();
            }
            echo "==> Competed deleting existing data " . date('Y-m-d H:i:s') . "\n";
            $message .= ">> Deletion successful " . date('Y-m-d H:i:s') . "\n";
        } catch (\Exception $e) {
            $message .= ">> Deletion unsuccessful " . json_encode($e) . " "  . date('Y-m-d H:i:s') . "\n";
            echo "==> Deletion unsuccessful " . json_encode($e) . " "  . date('Y-m-d H:i:s') . "\n";
        }        
        /*** Delete existing data ***/

        /*** Work on UG data ***/
        $message .= ">> Pulling UG Source data " . date('Y-m-d H:i:s') . "\n";
        try {
            echo "==> Pulling UG Source data " . date('Y-m-d H:i:s') . "\n";
            $source_start_ug = date('Y-m-d H:i:s', strtotime("+3 Hours", strtotime(date('Y-m-d H:i:s'))));
            TempUGSalesHeader::truncate();
            TempUGSalesLine::truncate();
            TempUGSalesHeader::insertData($start_date, $final_date);
            TempUGSalesLine::insertData($start_date, $final_date);
            $source_data_headers = TempUGSalesHeader::whereBetween('Posting_Date', [$start_date, $final_date])->get();
            $source_data_lines = TempUGSalesLine::whereBetween('Posting_Date', [$start_date, $final_date])->get();
            $source_end_ug = date('Y-m-d H:i:s', strtotime("+3 Hours", strtotime(date('Y-m-d H:i:s'))));

            /*** Bring In the new Data ***/
            echo "==> Inserting UG data into the warehouse (Count: {$source_data_lines->count()}) \n";
            $destination_start_ug = date('Y-m-d H:i:s', strtotime("+3 Hours", strtotime(date('Y-m-d H:i:s'))));

            // Bringing in the headers
            foreach ($source_data_headers as $key => $sale) {
                SalesInvoiceCreditMemoHeader::create([
                    'Invoice_Credit_Memo_No' => $sale->Document_No,
                    'SI_Document_No' => $sale->Document_No,
                    'Sell-To-Customer-No' => $sale->Customer_No,
                    'Sell-To-Customer-Name' => $sale->Customer_Name,
                    'Bill-To-Customer-No' => $sale->Customer_No,
                    'Bill-To-Customer-Name' => $sale->Customer_Name,
                    'SI_Posting_Date' => $sale->Posting_Date,
                    'SI_Due_Date' => $sale->Due_Date,
                    'Company_Code' => $sale->Company_Code,
                    'Type' => $sale->Type,
                    'Total_Amount_Excluding_Tax' => $sale->Total_Amount_Excluding_Tax,
                    'Total_Amount_Including_Tax' => $sale->Total_Amount_Including_Tax,
                    'Currency_Code' => $sale->Currency_Code,
                ]);
            }

            // Bringing in the lines
            foreach ($source_data_lines as $key => $entry) {
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
            $destination_end_ug = date('Y-m-d H:i:s', strtotime("+3 Hours", strtotime(date('Y-m-d H:i:s'))));

            /** Record entry complete **/
            echo "==> Making time for UG entry \n";
            TimeEntry::create([
                'source' => TempUGSalesLine::class,
                'destination' => SalesInvoiceCreditMemoLine::class,
                'Country' => 'UG',
                'source_start_time' => $source_start_ug,
                'source_end_time' => $source_end_ug,
                'destination_start_time' => $destination_start_ug,
                'destination_end_time' => $destination_end_ug,
            ]);
            $message .= ">> Completed pulling UG Sales data successfully " . date('Y-m-d H:i:s') . "\n";
        } catch (\Exception $e) {
            $message .= ">> Failed Pulling UG Sales data " . json_encode($e) . " "  . date('Y-m-d H:i:s') . "\n";
            echo "==> Failed Pulling UG Sales data " . json_encode($e) . " "  . date('Y-m-d H:i:s') . "\n";
        }
        /*** Work on UG data ***/

        /*** Work on KE data ***/
        try {
            $message .= ">> Bringing in KE Sales data " . date('Y-m-d H:i:s') . "\n";
            echo "==> Pulling KE Source data " . date('Y-m-d H:i:s') . "\n";
            $source_start_ke = date('Y-m-d H:i:s', strtotime("+3 Hours", strtotime(date('Y-m-d H:i:s'))));
            Temp::truncate();
            Temp::pullData();
            echo "==> Completed filling KE source data " . date('Y-m-d H:i:s') . "\n";
            $source_end_ke = date('Y-m-d H:i:s', strtotime("+3 Hours", strtotime(date('Y-m-d H:i:s'))));

            $destination_start_ke = date('Y-m-d H:i:s', strtotime("+3 Hours", strtotime(date('Y-m-d H:i:s'))));
            echo "==> Inserting KE temp data into the warehouse " . date('Y-m-d H:i:s') . "\n";
            $source_data = Temp::whereBetween('invoice_doc_dt', [$start_date, $final_date])->get();
            foreach ($source_data as $key => $sales) {
                if (SalesInvoiceCreditMemoHeader::where('Invoice_Credit_Memo_No', $sales->invoice_id)->get()->isEmpty()) {
                    SalesInvoiceCreditMemoHeader::create([
                        'Invoice_Credit_Memo_No' => $sales->invoice_id,
                        'SI_Document_No' => $sales->invoice_doc_id,
                        'Sell-To-Customer-No' => $sales->eo_nm,
                        'Sell-To-Customer-Name' => $sales->eo_nm,
                        'Bill-To-Customer-No' => $sales->eo_nm,
                        'Bill-To-Customer-Name' => $sales->eo_nm,
                        'SI_Posting_Date' => date('Y-m-d', strtotime($sales->invoice_doc_dt)),
                        'Company_Code' => 'BPL',
                        'Type' => ucwords($sales->inv_type_desc),
                        'Total_Amount_Excluding_Tax' => $sales->itm_amt_gs,
                        'Total_Amount_Including_Tax' => $sales->net_amnt,
                        'Currency_Code' => $sales->curr_sp,
                    ]);
                }
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
            }
            echo "==> Completed inserting KE temp data into the warehouse " . date('Y-m-d H:i:s') . "\n";
            $destination_end_ke = date('Y-m-d H:i:s', strtotime("+3 Hours", strtotime(date('Y-m-d H:i:s'))));

            /** Record entry complete **/
            echo "==> Making time for KE entry \n";
            TimeEntry::create([
                'source' => Temp::class,
                'destination' => SalesInvoiceCreditMemoLine::class,
                'Country' => 'KE',
                'source_start_time' => $source_start_ke,
                'source_end_time' => $source_end_ke,
                'destination_start_time' => $destination_start_ke,
                'destination_end_time' => $destination_end_ke,
            ]);
            echo "==> Competed Pulling KE Source data " . date('Y-m-d H:i:s') . "\n";
            $message .= ">> Competed Processing KE Sales data " . date('Y-m-d H:i:s') . "\n";
        } catch (\Exception $e) {
            $message .= ">> Failed pulling KE sales data " . json_encode($e) . " " . date('Y-m-d H:i:s') . "\n";
            echo "==> Failed pulling KE sales data " . json_encode($e) . " " . date('Y-m-d H:i:s') . "\n";
        }
        /*** Work on KE data ***/

        self::updateDay();
        self::updateOtherTimeDimensions();
        Mail::to([
            env('MAIL_TO_EMAIL'),
            'walter.orando@dataposit.co.ke',
            // 'kkinyanjui@dataposit.co.ke',
        ])/*->cc([
            'diana.adiema@dataposit.co.ke',
            'george.thiga@dataposit.co.ke',
            'aaron.mbowa@dataposit.co.ke',
        ])*/->send(new DailyScheduledTask($message));
        return true;
    }

    private static function updateDay()
    {
        DB::statement("
        UPDATE 
            [dbo].[Sales Invoice Credit Memo Lines]
        SET 
            [dbo].[Sales Invoice Credit Memo Lines].[Day] = [Sales Invoice Credit Memo Lines].[SI_Li_Posting_Date];
        ");
    }

    private static function updateOtherTimeDimensions()
    {
        DB::statement("
            UPDATE 
                [dbo].[Sales Invoice Credit Memo Lines]
            SET 
                [dbo].[Sales Invoice Credit Memo Lines].[week] = [LU_Day].[week]
                ,[dbo].[Sales Invoice Credit Memo Lines].[month] = [LU_Day].[month]
                ,[dbo].[Sales Invoice Credit Memo Lines].[quarter] = [LU_Month].[quarter_id]
                ,[dbo].[Sales Invoice Credit Memo Lines].[year] = [LU_Month].[year]
            FROM 
                [dbo].[Sales Invoice Credit Memo Lines]
                JOIN [dbo].[LU_Day] ON [LU_Day].day_id = [Sales Invoice Credit Memo Lines].[Day]
                JOIN [dbo].[LU_Month] ON [LU_Month].[month_id] = [LU_Day].[month];
        ");
    }
}