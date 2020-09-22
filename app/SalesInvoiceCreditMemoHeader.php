<?php

namespace App;

use App\Temps\Temp;
use Illuminate\Database\Eloquent\Model;
use DB;

class SalesInvoiceCreditMemoHeader extends BaseModel
{
    protected $table = 'Sales Invoice Credit Memo Headers';

    protected $guarded = [];

    public $timestamps = false;

    private $functionCall = "GetInvoiceCreditHeader";

    private $endpointColumns = [
			'Invoice_Credit_Memo_No' => 'Document_x0020_No',
			'SI_Document_No' => 'Document_x0020_No',
			'Sell-To-Customer-No' => 'Customer_x0020_No.',
			'Sell-To-Customer-Name' => 'Customer_x0020_Name',
			'Bill-To-Customer-No' => 'Customer_x0020_No.',
			'Bill-To-Customer-Name' => 'Customer_x0020_Name',
			'SI_Posting_Date' => 'Posting_x0020_Date',
			'SI_Due_Date' => 'Due_x0020_Date',
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
            SalesInvoiceCreditMemoHeader::insert($data->toArray());
        }
        return true;
    }

    public function synchHeadersKE()
    {
        ini_set("memory_limit", "-1");
        // echo "==> Start pulling Data " . date('Y-m-d H:i:s') . "\n";
        // $data = DB::connection('oracle')->select('select * from SLS$INVOICE$REG$DTL$VW');
        // echo "==> Finished pulling Data " . date('Y-m-d H:i:s') . "\n";
        // echo "==> Start  Data " . date('Y-m-d H:i:s') . "\n";
        // foreach ($data as $key => $value) {
        //     $value = (array) $value;
        //     Temp::insert($value);
        // }
        // echo "==> Finished inserting Data " . date('Y-m-d H:i:s') . "\n";
        echo "==> Inserting data in warehouse " . date('Y-m-d H:i:s') . "\n";
        echo "==> Inserting the headers " . date('Y-m-d H:i:s') . "\n";
        $this->insertKESales();
        echo "==> Finished inserting the headers " . date('Y-m-d H:i:s') . "\n";
        echo "==> Inserting the lines " . date('Y-m-d H:i:s') . "\n";
        $lines = new SalesInvoiceCreditMemoLine;
        $lines->insertKESalesLines();
        echo "==> Finished inserting the lines " . date('Y-m-d H:i:s') . "\n";
        echo "==> Finished inserting the warehouse data " . date('Y-m-d H:i:s') . "\n";
        return true;
    }

    public function insertKESales($empty=false, $verbose=false)
    {
        ini_set("memory_limit", "-1");
        if ($empty) {
            if ($verbose)
                echo "==> Deleting the existing data\n";
            SalesInvoiceCreditMemoHeader::truncate();
            if ($verbose)
                echo "==> Finished deleting the existing data\n";
        }
        if ($verbose)
            echo "==> Inserting data into the Warehouse\n";
        foreach (Temp::get() as $key => $sales) {
            if (SalesInvoiceCreditMemoHeader::where('Invoice_Credit_Memo_No', $sales->invoice_id)->get()->isEmpty()) {
                SalesInvoiceCreditMemoHeader::create([
                    'Invoice_Credit_Memo_No' => $sales->invoice_id,
                    'SI_Document_No' => $sales->invoice_doc_id,
                    'Sell-To-Customer-No' => $sales->eo_nm,
                    'Sell-To-Customer-Name' => $sales->eo_nm,
                    'Bill-To-Customer-No' => $sales->eo_nm,
                    'Bill-To-Customer-Name' => $sales->eo_nm,
                    'SI_Posting_Date' => date('Y-m-d', strtotime($sales->invoice_doc_dt)),
                    // 'SI_Due_Date' => 'Due_x0020_Date',
                    'Company_Code' => 'BPL',
                    'Type' => ucwords($sales->inv_type_desc),
                    'Total_Amount_Excluding_Tax' => $sales->itm_amt_gs,
                    'Total_Amount_Including_Tax' => $sales->net_amnt,
                    'Currency_Code' => $sales->curr_sp,
                ]);
            }
        }
        if ($verbose){
            echo "==> Finished inserting data into the Warehouse\n";
            echo "==> Inserting the lines data into the warehouse\n";
        }

        $lines = new SalesInvoiceCreditMemoLine;
        $lines->insertKESalesLines(true);
        if ($verbose)
            echo "==> Finished inserting line data into the Warehouse\n";

    }

    public function insertUGData()
    {
        echo "==> Deleting UG Sales header data " . date('Y-m-d H:i:s') . "\n";
        foreach(SalesInvoiceCreditMemoHeader::where('Company_Code', 'BUL')->get() as $header){
            $header->delete();
        }foreach(SalesInvoiceCreditMemoLine::where('Company_Code', 'BUL')->get() as $line){
            $line->delete();
        }
        echo "==> Finishing deleting the UG sales data " . date('Y-m-d H:i:s') . "\n";
        echo "==> Pulling temp data " . date('Y-m-d H:i:s') . "\n";
        $sales = TempUGSalesHeader::get();
        echo "==> Inserting headers data " . date('Y-m-d H:i:s') . "\n";
        foreach ($sales as $key => $sale) {
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
        echo "==> Finished inserting headers data " . date('Y-m-d H:i:s') . "\n";
        echo "==> Pulling temp lines data " . date('Y-m-d H:i:s') . "\n";
        $saleslines = TempUGSalesLine::get();
        echo "==> Inserting lines data " . date('Y-m-d H:i:s') . "\n";
        foreach ($saleslines as $key => $line) {
            SalesInvoiceCreditMemoLine::create([
                'SI_Li_Line_No' => $line->Entry_No . '-' . $sale->LineNum,
                'Invoice_Credit_Memo_No' => $line->Document_No,
                'SI_Li_Document_No' => $line->Document_No,
                'Item_No' => $line->ItemCode,
                'Item_Weight_kg' => $line->Item_Weight_in_kg,
                'Item_Price_kg' => $line->Item_Price_in_kg,
                'Item_Description' => $line->Item_Description,
                'Quantity' => $line->Quantity,
                'Unit_Price' => $line->Unit_Price,
                'Unit_Cost' => $line->Unit_Cost,
                'Company_Code' => $line->Company_Code,
                'Currency_Code' => $line->Currency_Code,
                'Type' => $line->Type,
                'Total_Amount_Excluding_Tax' => $line->Total_Amount_Excluding_Tax,
                'Total_Amount_Including_Tax' => $line->Total_Amount_Including_Tax,
                'Sales_Unit_of_Measure' => $line->Sales_Unit_of_Measure,
                'SI_Li_Posting_Date' => $line->Posting_Date,
                'SI_Li_Due_Date' => $line->Due_Date,
            ]);
        }
        echo "==> Finished inserting lines data " . date('Y-m-d H:i:s') . "\n";
        return true;
    }
}