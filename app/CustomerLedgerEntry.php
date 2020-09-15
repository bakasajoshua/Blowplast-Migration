<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Logs\TimeEntry;
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
        return true;
    }

    public function synchKEEntries()
    {
        $data = DB::connection('oracle')->select('select * from fin.fin_ar_vw');
        $dbInsert = [];
        foreach ($data as $key => $value) {
            $dbInsert[] = [
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
            
        }
        $chunks = collect($dbInsert)->chunk($this->chunkQty);
        foreach ($chunks as $key => $chunk) {
            $insert = CustomerLedgerEntry::insert($chunk->toArray());
        }

        $customers = CustomerLedgerEntry::where('Company_Code', 'BPL')->get()->unique('Customer_No');
        foreach ($customers as $key => $customer) {
            $existing = Customer::where('Customer_Name', $customer->Customer_No)->get();
            if ($existing->isEmpty()){
                $newCustomer = Customer::create([
                        "Customer_No" => round(microtime(true) * 1000),
                        "Customer_Name" => $customer->Customer_No,
                        "Company_Code" => 'BPL'
                    ]);
            } else {
                $newCustomer = $existing->first();
            }
            $entries = CustomerLedgerEntry::where('Company_Code', 'BPL')->where('Customer_No', $customer->Customer_No)->get();
            foreach ($entries as $key => $entry) {
                $entry->fill([
                        'Customer_No' => $newCustomer->Customer_No,
                        'Sell-To-Customer-No' => $newCustomer->Customer_No,
                        'Bill-To-Customer-No' => $newCustomer->Customer_No,
                    ]);
                $entry->save();
            }
        }
        return true;
    }

    public static function scheduledImport()
    {
        $start_date = "2019-01-01";
        $final_date = date('Y-m-d', strtotime("-1 Day", strtotime(date('Y-m-d'))));
        $model = new CustomerLedgerEntry;
        
        echo "==> Pulling and Insert UG Customer Ledger Entries " . date('Y-m-d H:i:s') . "\n";
        $destination_start_ug = date('Y-m-d');
        $model->processImportData(CustomerLedgerEntry::class,
                        'synchEntries', $start_date, 
                        $final_date, 30);
        $destination_end_ug = date('Y-m-d');
        echo "==> Pulling and Insert UG Customer Ledger Entries completed. " . date('Y-m-d H:i:s') . "\n";

        echo "==> Pulling and Insert KE Customer Ledger Entries\n";        
        $destination_start_ke = date('Y-m-d');
        $model->synchKEEntries();
        $destination_end_ke = date('Y-m-d');
        echo "==> Pulling and Insert KE Customer Ledger Entries completed. " . date('Y-m-d H:i:s') . "\n";
        
        /** Record entry complete **/
        echo "==> Making time entries " . date('Y-m-d H:i:s') . "\n";

        TimeEntry::create([
            // 'source' => Temp::class,
            'destination' => CustomerLedgerEntry::class,
            'Country' => 'UG',
            'destination_start_time' => $destination_start_ug,
            'destination_end_time' => $destination_end_ug,
        ]);
        TimeEntry::create([
            // 'source' => Temp::class,
            'destination' => CustomerLedgerEntry::class,
            'Country' => 'KE',
            'destination_start_time' => $destination_start_ke,
            'destination_end_time' => $destination_end_ke,
        ]);

        echo "==> Updating Time dimensions.\n";
            self::updateDay();
            self::updateOtherTimeDimensions();
        echo "==> Updating Time dimensions completed. " . date('Y-m-d H:i:s') . "\n";

    }


    private static function updateDay()
    {
        DB::statement("
        UPDATE 
            [dbo].[Customer Ledger Entries]
        SET 
            [dbo].[Customer Ledger Entries].[Day] = [Customer Ledger Entries].[Posting_Date];
        ");
    }

    private static function updateOtherTimeDimensions()
    {
        DB::statement("
            UPDATE 
                [dbo].[Customer Ledger Entries]
            SET 
                [dbo].[Customer Ledger Entries].[week] = [LU_Day].[week]
                ,[dbo].[Customer Ledger Entries].[month] = [LU_Day].[month]
                ,[dbo].[Customer Ledger Entries].[quarter] = [LU_Month].[quarter_id]
                ,[dbo].[Customer Ledger Entries].[year] = [LU_Month].[year]
            FROM 
                [dbo].[Customer Ledger Entries]
                JOIN [dbo].[LU_Day] ON [LU_Day].day_id = [Customer Ledger Entries].[Day]
                JOIN [dbo].[LU_Month] ON [LU_Month].[month_id] = [LU_Day].[month];
        ");
    }
}