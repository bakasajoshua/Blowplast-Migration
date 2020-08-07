<?php

namespace App;

use App\Mail\DailyScheduledTask;
use App\Temps\TempKEGL;
use App\Temps\TempUGGLEntry;
use App\Logs\TimeEntry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use DB;

class GLEntries extends BaseModel
{
    protected $table = 'GL Entries';

    protected $guarded = [];

    public $timestamps = false;

    private $functionCall = "GetGLEntries";

    private $endpointColumns = [
        'GL_Entry_No' => 'Entry_x0020_No',
        'GL_Account_No' => 'GL_x0020_Account_x0020_Number',
        'Balancing_GL_Account_No' => 'Balancing_x0020_GL_x0020_Account_x0020_No',
        'Debit' => 'Debit',
        'Credit' => 'Credit',
        'Amounts' => 'Amount',
        'GL_Posting_Date' => 'Posting_x0020_Date',
        'Day' => 'Posting_x0020_Date',
        'GL_Document_No' => 'Document_x0020_Number',
        'GL_Document_Type' => 'Document_x0020_Type',
        'Description' => 'Description',
        'Company_Code' => 'Company_x0020_Code',
    ];
    private $chunkQty = 100;

    public function synchEntries($params = [])
    {
        ini_set("memory_limit", "-1");
        $chunks = $this->synch($this->functionCall, $this->endpointColumns, $params)->chunk($this->chunkQty);
        $insert_chunk = $this->insertChunk($chunks);
        return true;
    }

    public function insertChunk($chunks)
    {
        foreach ($chunks as $key => $data) {
            $glentries = GLEntries::insert($data->toArray());
        }
        $day_dimensions = $this->updateDay();
        $time_dimensions = $this->updateOtherTimeDimensions();
        return true;
    }

    private function updateDay()
    {
        DB::statement("
        UPDATE [dbo].[GL Entries] SET [dbo].[GL Entries].[Day] = [GL Entries].[GL_Posting_Date] WHERE [dbo].[GL Entries].[Day] IS NULL
        ");
    }

    private function updateOtherTimeDimensions()
    {
        DB::statement("
            UPDATE 
                [dbo].[GL Entries]
            SET 
                [dbo].[GL Entries].[week] = [LU_Day].[week]
                ,[dbo].[GL Entries].[month] = [LU_Day].[month]
                ,[dbo].[GL Entries].[quarter] = [LU_Month].[quarter_id]
                ,[dbo].[GL Entries].[year] = [LU_Month].[year]
            FROM 
                [dbo].[GL Entries]
                JOIN [dbo].[LU_Day] ON [LU_Day].day_id = [GL Entries].[Day]
                JOIN [dbo].[LU_Month] ON [LU_Month].[month_id] = [LU_Day].[month]
            WHERE
                [dbo].[GL Entries].[week] IS NULL;
        ");
    }

    public function scheduledImport()
    {
        ini_set("memory_limit", "-1");
        $year = date('Y');
        $month = date('m');
        $start_date = $year . '-' . $month . '-01';
        $final_date = date('Y-m-d');
        $incremental = 5;
        $message = '';

        /*** Delete all existing data for the period of insertion ***/
        $message .= ">> Deleting existing GL data " . date('Y-m-d H:i:s') . "\n";
        try {
            echo "==> Deleting data for the time period " . date('Y-m-d H:i:s') . "\n";
            $deletion_data = GLEntries::whereYear('GL_Posting_Date', $year)
                                ->whereMonth('GL_Posting_Date', $month)->get();
            foreach ($deletion_data as $key => $line) {
                $line->delete();
            }
            echo "==> GL Data deletion completed " . date('Y-m-d H:i:s') . "\n";
            $message .= ">> GL Data Deletion successful " . date('Y-m-d H:i:s') . "\n";
        } catch (\Exception $e) {
            $message .= ">> GL Data Deletion unsuccessful " . json_encode($e) . " "  . date('Y-m-d H:i:s') . "\n";
            echo "==> GL Data Deletion unsuccessful " . json_encode($e) . " "  . date('Y-m-d H:i:s') . "\n";
        } 
        /*** Delete all existing data for the period of insertion ***/

        /*** Working on KE Data ***/
        try {
            echo "==> Filling the KE GL Entries temp table " . date('Y-m-d H:i:s') . "\n";
            $message .= ">> Filling the KE GL Entries temp table " . date('Y-m-d H:i:s') . "\n";
            $source_start_ke = date('Y-m-d H:i:s', strtotime("+3 Hours", strtotime(date('Y-m-d H:i:s'))));
            TempKEGL::truncate();
            $model = new TempKEGL;
            $ke = $model->syncData();
            echo "==> Completed filling the KE GL Entries temp table " . date('Y-m-d H:i:s') . "\n";
            $source_end_ke = date('Y-m-d H:i:s', strtotime("+3 Hours", strtotime(date('Y-m-d H:i:s'))));
            /*** Finished working with the temp Data ***/
            echo "==> Filling warehouse with KE GL Entries " . date('Y-m-d H:i:s') . "\n";
            /*** Inserting the KE Data in the warehouse ***/
            echo "==> Inserting KE Data into the warehouse " . date('Y-m-d H:i:s') . "\n";
            $destination_start_ke = date('Y-m-d H:i:s', strtotime("+3 Hours", strtotime(date('Y-m-d H:i:s'))));
            $keData = TempKEGL::whereBetween('voucher date', [$start_date, $final_date])->get()->toArray();
            $chunkKE = [];
            foreach ($keData as $key => $entry) {
                $glaccount = GLAccounts::where('GL_Account_Name', $entry['coa name'])->get();
                if ($glaccount->isEmpty()) {
                    $glaccount = GLAccounts::create([
                        'GL_Account_No' => round(microtime(true) * 1000),
                        'GL_Account_Name' => $entry['coa name'],
                        'Company_Code' => 'BPL',
                    ]);
                } else {
                    $glaccount = $glaccount->first();
                }
                $chunkKE[] = [
                    'GL_Entry_No' => round(microtime(true) * 1000),
                    'GL_Account_No' => $glaccount->GL_Account_No,
                    'Debit' => $entry['debit'],
                    'Credit' => $entry['credit'],
                    'Amounts' => ((float)$entry['debit']-(float)$entry['credit']),
                    'Currency_Code' => $entry['currency'],
                    'GL_Posting_Date' => date('Y-m-d', strtotime($entry['voucher date'])),
                    'Day' => date('Y-m-d', strtotime($entry['voucher date'])),
                    'GL_Document_No' => $entry['doc no'],
                    'GL_Document_Type' => NULL,
                    'Description' => $entry['narration'],
                    'Company_Code' => $glaccount->Company_Code,
                ];
            }
            $chunks = collect($chunkKE)->chunk($this->chunkQty);
            $insert = $this->insertChunk($chunks);
            $destination_end_ke = date('Y-m-d H:i:s', strtotime("+3 Hours", strtotime(date('Y-m-d H:i:s'))));
            echo "==> Finished inserting KE Data into the warehouse " . date('Y-m-d H:i:s') . "\n";
            /** Record entry complete **/
            echo "==> Making time entry \n";
            TimeEntry::create([
                'source' => TempKEGL::class,
                'destination' => GLEntries::class,
                'Country' => 'KE',
                'source_start_time' => $source_start_ke,
                'source_end_time' => $source_end_ke,
                'destination_start_time' => $destination_start_ke,
                'destination_end_time' => $destination_end_ke,
            ]);
            $message .= ">> Completed filling the KE GL Entries temp table " . date('Y-m-d H:i:s') . "\n";
        } catch (\Exception $e) {
            $message .= ">> Filling KE GL Entries unsuccessful " . json_encode($e) . " "  . date('Y-m-d H:i:s') . "\n";
            echo "==> Filling KE GL Entries unsuccessful " . json_encode($e) . " "  . date('Y-m-d H:i:s') . "\n";
        }        
        /*** Working on KE Data ***/

        /*** Working on UG Data ***/
        try {
            $message .= ">> Filling the UG GL Entries temp table " . date('Y-m-d H:i:s') . "\n";
            echo "==> Filling the UG GL Entries temp table " . date('Y-m-d H:i:s') . "\n";
            $source_start_ug = date('Y-m-d H:i:s', strtotime("+3 Hours", strtotime(date('Y-m-d H:i:s'))));
            TempUGGLEntry::truncate();
            $this->processImportData(TempUGGLEntry::class, 'synchEntries',
                                $start_date, $final_date, $incremental);
            echo "==> Completed filling the UG GL Entries temp table " . date('Y-m-d H:i:s') . "\n";
            $source_end_ug = date('Y-m-d H:i:s', strtotime("+3 Hours", strtotime(date('Y-m-d H:i:s'))));
             /*** Inserting the UG Data in the warehouse ***/
            echo "==> Inserting UG Data into the warehouse " . date('Y-m-d H:i:s') . "\n";
            $destination_start_ug = date('Y-m-d H:i:s', strtotime("+3 Hours", strtotime(date('Y-m-d H:i:s'))));
            $UGData = $this->fillUGScheduledData($start_date, $final_date);
            $destination_end_ug = date('Y-m-d H:i:s', strtotime("+3 Hours", strtotime(date('Y-m-d H:i:s'))));
            echo "==> Finished inserting UG Data into the warehouse " . date('Y-m-d H:i:s') . "\n";
            /*** Inserting the UG Data in the warehouse ***/
            echo "==> Finished inserting GL Entries Data into the warehouse " . date('Y-m-d H:i:s') . "\n";

            TimeEntry::create([
                'source' => TempUGGLEntry::class,
                'destination' => GLEntries::class,
                'Country' => 'UG',
                'source_start_time' => $source_start_ug,
                'source_end_time' => $source_end_ug,
                'destination_start_time' => $destination_start_ug,
                'destination_end_time' => $destination_end_ug,
            ]);
            $message .= ">> Completed filling the UG GL Entries temp table " . date('Y-m-d H:i:s') . "\n";
        } catch (\Exception $e) {
            $message .= ">> Filling the UG GL Entries unsuccessful " . json_encode($e) . " "  . date('Y-m-d H:i:s') . "\n";
            echo "==> Filling the UG GL Entries unsuccessful " . json_encode($e) . " "  . date('Y-m-d H:i:s') . "\n";
        }
        
        /*** Working on UG Data ***/

        $updates = $this->updateDay();
        $updates = $this->updateOtherTimeDimensions();
        Mail::to([
            env('MAIL_TO_EMAIL'),
            'walter.orando@dataposit.co.ke',
            'kkinyanjui@dataposit.co.ke',
        ])->cc([
            'diana.adiema@dataposit.co.ke',
            'george.thiga@dataposit.co.ke',
            'aaron.mbowa@dataposit.co.ke',
        ])->send(new DailyScheduledTask($message));
        return true;
    }

    public function fillUGScheduledData($start_date=null, $final_date=null)
    {
        if (!isset($start_date)){
            $year = date('Y');
            $month = date('m');
            $start_date = $year . '-' . $month . '-01';
            $final_date = date('Y-m-d');
            $deletion_data = GLEntries::whereBetween('GL_Posting_Date', [$start_date, $final_date])->get();

            foreach ($deletion_data as $key => $line) {
                $line->delete();
            }
        }
        $data = [];
        $ugData = TempUGGLEntry::whereBetween('Posting_Date', [$start_date, $final_date])->get();
        
        foreach ($ugData as $key => $entry) {
            if (strtotime($entry->Posting_Date) >= strtotime($start_date))
                $data[] = [
                    'GL_Entry_No' => (string)$entry->Entry_No . '-' . $entry->GroupMask,
                    'GL_Account_No' => $entry->GL_Account_Number,
                    'Debit' => $entry->Debit,
                    'Credit' => $entry->Credit,
                    'Amounts' => ((float)$entry->Debit-(float)$entry->Credit),
                    'Currency_Code' => $entry->Currency,
                    'GL_Posting_Date' => date('Y-m-d', strtotime($entry->Posting_Date)),
                    'Day' => date('Y-m-d', strtotime($entry->Posting_Date)),
                    'GL_Document_No' => $entry->Document_Number,
                    'GL_Document_Type' => $entry->Document_Type,
                    'Description' => $entry->Description,
                    'Company_Code' => $entry->Company_Code,
                ];
        }
        $chunks = collect($data)->chunk($this->chunkQty);
        $insert = $this->insertChunk($chunks);
        return true;
    }


    /*
    *
    *   Quick Fix functions
    *
    */
    public static function fillTime()
    {
        $start_date = '2018-01-01';
        $final_date = '2020-07-30';
        $incremental = 1;
        ini_set("memory_limit", "-1");
        while (strtotime($final_date) >= strtotime($start_date)) {
            echo "==> For Month " . date('Y-m', strtotime($start_date)) . "\n";
            $glentries = GLEntries::whereYear('Day', date('Y', strtotime($start_date)))->whereMonth('Day', date('m', strtotime($start_date)))->whereNull('year')->get();
            if (!$glentries->isEmpty()){
                foreach ($glentries as $key => $entry) {
                    $day = Day::whereDate('day_id', date('Y-m-d', strtotime($entry->Day)))->first();
                    $week = $day->day_week;
                    $month = $day->day_month;
                    $quarter = $month->month_quarter;
                    $year = $month->month_year;
                    $entry->week = $week->week;
                    $entry->month = $month->month_id;
                    $entry->quarter = $quarter->quarter;
                    $entry->year = $year->year;
                    $entry->save();
                }
            }
            $start_date = date('Y-m-d', strtotime('+'.$incremental.' month', strtotime($start_date)));
        }
        return true;
    }
}