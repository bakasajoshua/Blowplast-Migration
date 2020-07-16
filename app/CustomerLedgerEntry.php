<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class CustomerLedgerEntry extends BaseModel
{
    protected $table = 'Customer Ledger Entries';

    // protected $primaryKey = 'CU_Leg_Entry_No';

    // protected $keyType = 'string';

    protected $guarded = [];

    public $timestamps = false;

    private $functionCall = "GetCustomerLedgerEntries";

    private $endpointColumns = [
                    'CL_Entry_No' => 'Entry_x0020_No',
                    'Document_No' => 'Document_x0020_No',
                    'Customer_No' => 'Customer_x0020_No',
                    'Sell-To-Customer-No' => 'Customer_x0020_No',
                    'Sell-To-Customer-Name' => 'Customer_x0020_Name',
                    'Bill-To-Customer-No' => 'Customer_x0020_No',
                    'Bill-To-Customer-Name' => 'Customer_x0020_Name',
                    'Posting_Date' => 'Posting_x0020_Date',
                    'Due_Date' => 'Due_x0020_Date',
                    'Original_Amount_LCY' => 'Original_x0020_Amount_x0020_in_x0020_LCY',
                    'Original_Amount' => 'Original_x0020_Amount',
                    'Currency_Code' => 'Currency_x0020_Code',
                    'Currency_Factor' => 'Currency_x0020_Factor',
                    'Remaining_Amount_LCY' => 'Remaining_x0020_Amount_x0020_in_x0020_LCY',
                    'Remaining_Amount' => 'Remaining_x0020_Amount'
                ];
    private $chunkQty = 10;


    // public static function boot()
    // {
    //     parent::boot();
    //     static::creating(function (Model $model) {
    //         $model->CU_Leg_Entry_No = $model->count() + 1;
    //     });
    // }



    public function synchEntries($params = [])
    {
        ini_set("memory_limit", "-1");
        $chunks = $this->synch($this->functionCall, $this->endpointColumns, $params)->chunk($this->chunkQty);
        foreach ($chunks as $key => $data) {
            CustomerLedgerEntry::insert($data->toArray());
        }
        foreach (CustomerLedgerEntry::get() as $key => $entry) {
            $entry->Company_Code = 'BUL';
            $entry->save();
        }
        $ke = $this->synchKEEntries();

        return true;
    }

    public function synchKEEntries()
    {
        $data = DB::connection('oracle')->select('select * from fin.fin_ar_vw');
        $dbInsert = [];
        foreach ($data as $key => $value) {
            $dbInsert = [
                'CL_Entry_No' => $value->voucher_number,
                'Document_No' => $value->voucher_number,
                'Customer_No' => $value->supplier_name,
                'Sell-To-Customer-No' => $value->supplier_name,
                'Sell-To-Customer-Name' => $value->supplier_name,
                'Bill-To-Customer-No' => $value->supplier_name,
                'Bill-To-Customer-Name' => $value->supplier_name,
                'Posting_Date' => $value->voucher_date,
                'Due_Date' => $value->due_date,
                'Original_Amount_LCY' => $value->voucher_amt,
                'Original_Amount' => 0,
                'Currency_Code' => $value->currency,
                'Currency_Factor' => 0,
                'Remaining_Amount_LCY' => $value->balance_ason,
                'Remaining_Amount' => 0,
                'Company_Code' => 'BPL'
            ];
            $insert = CustomerLedgerEntry::insert($dbInsert);
        }

        return true;
    }
}