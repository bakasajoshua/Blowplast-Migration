<?php

namespace App\Temps;

use App\BaseModel;
use App\Customer;
use App\SalesInvoiceCreditMemoHeader;
use App\SalesInvoiceCreditMemoLine;
use Illuminate\Database\Eloquent\Model;
use DB;

class TempReceivable extends Model
{
	protected $connection = 'testdb';

    protected $guarded = [];

    private $no_prefixes = [
        'Credit Note' => 'CNC'
    ];
    
    public static function insertData($verbose=false)
    {
        ini_set("memory_limit", "-1");
        if ($verbose)
            echo "==> Start pulling KE Receivables Data " . date('Y-m-d H:i:s') . "\n";
        $data = DB::connection('oracle')->select('select * from fin.fin_ar_vw');
        if ($verbose){
            echo "==> Finished pulling KE Data " . date('Y-m-d H:i:s') . "\n";
        }

        if ($verbose)
            echo "==> Truncate the Receivables temp table " . date('Y-m-d H:i:s') . "\n";
        TempReceivable::truncate();

        if ($verbose)
            echo "==> Inserting Temp KE Data " . date('Y-m-d H:i:s') . "\n";
        $importData = [];
        foreach ($data as $key => $value) {
            $importData[] = (array)$value;
        }
        $chunks = collect($importData)->chunk(100);
        foreach ($chunks as $key => $chunk) {
            TempReceivable::insert($chunk->toArray());
        }

        if ($verbose)
            echo "==> Finished Inserting KE Receivables Data into the WH " . date('Y-m-d H:i:s') . "\n";

        self::manuals($verbose);
        
        return true;
    }

    public static function manuals($verbose=false)
    {
        $model = new TempReceivable;
        $headerdata = $lilnedata = [];
        if ($verbose)
            echo "==> Removing the existing credit notes from the WH " . date('Y-m-d H:i:s') . "\n";
        $credheaders = SalesInvoiceCreditMemoHeader::where('Type', 'Credit Note')->where('Company_Code', 'BPL')->get();
        foreach ($credheaders as $key => $value) {
            $value->delete();
        }
        $credlines = SalesInvoiceCreditMemoLine::where('Type', 'Credit Note')->where('Company_Code', 'BPL')->get();
        foreach ($credlines as $key => $value) {
            $value->delete();
        }
        if ($verbose)
            echo "==> Finished removing the existing credit notes from the WH " . date('Y-m-d H:i:s') . "\n";

        if ($verbose)
            echo "==> Getting the credit notes and formatting them " . date('Y-m-d H:i:s') . "\n";
        foreach ($model->no_prefixes as $prefixkey => $prefix) {
            $invoices = TempReceivable::where('voucher_number', 'like', '%'.$prefix.'%')->get();
            echo "==> Credit Notes found {$invoices->count()} \n";
            foreach ($invoices as $invoicekey => $invoice) {
                $customer = Customer::where('Customer_Name', $invoice->supplier_name)->get();
                if (!$customer->isEmpty()) {
                    $customer = $customer->first();
                    $headerdata[] = [
                        'Invoice_Credit_Memo_No' => $invoice->voucher_number,
                        'SI_Document_No' => $invoice->voucher_number,
                        'Sell-To-Customer-No' => $customer->Customer_No,
                        'Sell-To-Customer-Name' => $customer->Customer_Name,
                        'Bill-To-Customer-No' => $customer->Customer_No,
                        'Bill-To-Customer-Name' => $customer->Customer_Name,
                        'SI_Posting_Date' => date('Y-m-d', strtotime($invoice->voucher_date)),
                        'Company_Code' => 'BPL',
                        'Type' => $prefixkey,
                        'Total_Amount_Excluding_Tax' => ((float)$invoice->voucher_amt * -1),
                        'Total_Amount_Including_Tax' => ((float)$invoice->voucher_amt * -1),
                        'Currency_Code' => $invoice->currency
                    ];


                    $lilnedata[] = [
                        'SI_Li_Line_No' => $invoice->voucher_number,
                        'Invoice_Credit_Memo_No' => $invoice->voucher_number,
                        'SI_Li_Document_No' => $invoice->voucher_number,
                        'SI_Li_Posting_Date' => date('Y-m-d', strtotime($invoice->voucher_date)),
                        'Company_Code' => 'BPL',
                        'Type' => $prefixkey,
                        'Total_Amount_Excluding_Tax' => ((float)$invoice->voucher_amt * -1),
                        'Total_Amount_Including_Tax' => ((float)$invoice->voucher_amt * -1),
                        'Currency_Code' => $invoice->currency
                    ];
                }
            }
        }

        if ($verbose)
            echo "==> Inserting the credit notes headers " . date('Y-m-d H:i:s') . "\n";

        $headerchunks = collect($headerdata)->chunk(10);
        foreach ($headerchunks as $key => $chunk) {
            SalesInvoiceCreditMemoHeader::insert($chunk->toArray());
        }

        if ($verbose)
            echo "==> Inserting the credit notes lines " . date('Y-m-d H:i:s') . "\n";

        $linechunks = collect($lilnedata)->chunk(10);
        foreach ($linechunks as $key => $chunk) {
            SalesInvoiceCreditMemoLine::insert($chunk->toArray());
            SalesInvoiceCreditMemoLine::updateDay();
            SalesInvoiceCreditMemoLine::updateOtherTimeDimensions();
        }
    }
}