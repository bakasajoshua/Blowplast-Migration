<?php

namespace App;

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
        echo "==> Start pulling Data " . date('Y-m-d H:i:s') . "\n";
        $data = DB::connection('oracle')->select('select * from SLS$INVOICE$REG$DTL$VW');
        echo "==> Finished pulling Data " . date('Y-m-d H:i:s') . "\n";
        echo "==> Start  Data " . date('Y-m-d H:i:s') . "\n";
        foreach ($data as $key => $value) {
            $value = (array) $value;
            Temp::insert($value);
        }
        echo "==> Finished inserting Data " . date('Y-m-d H:i:s') . "\n";
        dd(Temp::first());
    }
}