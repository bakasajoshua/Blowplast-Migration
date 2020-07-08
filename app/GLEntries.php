<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GLEntries extends BaseModel
{
    protected $table = 'GL Entries';

    // protected $primaryKey = 'GL_Entry_No';

    // protected $keyType = 'string';

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
        foreach ($chunks as $key => $data) {
            GLEntries::insert($data->toArray());
        }
        return true;
    }

    public static function fillTime()
    {
        $start_date = '2018-01-01';
        $final_date = '2020-07-30';
        $incremental = 1;
        while (strtotime($final_date) >= strtotime($start_date)) {
            echo "==> For Month " . date('Y-m', strtotime($start_date)) . "\n";
            $glentries = GLEntries::whereYear('Day', date('Y', strtotime($start_date)))->whereMonth('Day', date('m', strtotime($start_date)))->get();
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