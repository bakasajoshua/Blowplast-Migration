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
        if ($verbose)
            echo "==> Start pulling KE Receivables Data " . date('Y-m-d H:i:s') . "\n";
        $data = DB::connection('oracle')->select('select * from fin.fin_ar_vw');
        if ($verbose){
            echo "==> Finished pulling KE Data " . date('Y-m-d H:i:s') . "\n";
            echo "==> Inserting Temp KE Data " . date('Y-m-d H:i:s') . "\n";
        }
        foreach ($data as $key => $value) {
            $model = new TempReceivable;
            $model->fill((array)$value);
            $model->save();
        }
        if ($verbose)
            echo "==> Finished Inserting KE Receivables Data into the WH " . date('Y-m-d H:i:s') . "\n";    	
        
        return $model;
    }

    public static function manuals()
    {
        $model = new TempReceivable;
        $headerdata = $lilnedata = [];
        foreach ($model->no_prefixes as $prefixkey => $prefix) {
            $invoices = TempReceivable::where('voucher_number', 'like', '%'.$prefix.'%')->get();
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
                        'Total_Amount_Excluding_Tax' => $invoice->voucher_amt,
                        'Total_Amount_Including_Tax' => $invoice->voucher_amt,
                        'Currency_Code' => $invoice->currency
                    ];


                    $lilnedata[] = [
                        'SI_Li_Line_No' => $invoice->voucher_number,
                        'Invoice_Credit_Memo_No' => $invoice->voucher_number,
                        'SI_Li_Document_No' => $invoice->voucher_number,
                        'SI_Li_Posting_Date' => date('Y-m-d', strtotime($invoice->voucher_date)),
                        'Company_Code' => 'BPL',
                        'Type' => $prefixkey,
                        'Total_Amount_Excluding_Tax' => $invoice->voucher_amt,
                        'Total_Amount_Including_Tax' => $invoice->voucher_amt,
                        'Currency_Code' => $invoice->currency
                    ];
                }
            }
        }

        $headerchunks = collect($headerdata)->chunk(10);
        foreach ($headerchunks as $key => $chunk) {
            SalesInvoiceCreditMemoHeader::insert($chunk->toArray());
        }

        $linechunks = collect($lilnedata)->chunk(10);
        foreach ($linechunks as $key => $chunk) {
            SalesInvoiceCreditMemoLine::insert($chunk->toArray());
            SalesInvoiceCreditMemoLine::updateDay();
            SalesInvoiceCreditMemoLine::updateOtherTimeDimensions();
        }
    }
}