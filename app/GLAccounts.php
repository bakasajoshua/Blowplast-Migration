<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Tightenco\Collect\Support\Collection;
use Rodenastyle\StreamParser\StreamParser;
use Illuminate\Support\Facades\Redis;

use DB;
class GLAccounts extends BaseModel
{
    protected $table = 'GL Accounts';

    protected $guarded = [];

    public $timestamps = false;

    private $functionCall = "GetGLAccount";
    
    private $endpointColumns = [
                    'GL_Account_No' => 'GL_x0020_Account_x0020_No',
                    'GL_Account_Name' => 'GL_x0020_Account_x0020_Name',
                    'Blocked' => 'Status',
                    'Company_Code' => 'Company_x0020_Code',
                    'GL_Account_Type' => 'GL_x0020_Account_x0020_Type',
                    'Chart_of_Account_Group' => 'Chart_x0020_of_x0020_Account_x0020_Group',
                    'Chart_ofAccount_Group_Name' => 'Chart_x0020_of_x0020_Account_x0020_Group_x0020_Name'
                ];

    private $chunkQty = 10;

    public function synchAccounts()
    {
        $level_1_Data = AccountType::get();
        $level_2_Data = ChartOfAccounts::get();
        $synchData = $this->synch($this->functionCall, $this->endpointColumns);
        $accounts = [];
        foreach ($synchData as $key => $account) {
            if ($account['Blocked'] == 'N')
                $account['Blocked'] = 0;
            if ($account['Blocked'] == 'Y')
                $account['Blocked'] = 1;
            
            $level_1 = $level_1_Data->where('Level_1_Description', $account['GL_Account_Level_1']);
            if (!$level_1->isEmpty()){
                $account['GL_Account_Level_1'] = $level_1->first()->Level_1_ID;
                $account['Income_Balance'] = $level_1->first()->bs_is;
            } else {
                unset($account['GL_Account_Level_1']);
                unset($account['Income_Balance']);
            }

            $level_2 = $level_2_Data->where('Level_2_ID', $account['GL_Account_Level_2']);
            if (!$level_2->isEmpty()){
                $account['GL_Account_Level_2'] = $level_2->first()->Level_2_ID;
            } else {
                unset($account['GL_Account_Level_2']);
            }

            $accounts[] = $account;
        }

        $chunks = collect($accounts);
        foreach($chunks as $key => $chunk){
            GLAccounts::insert($chunk);
        }
        
        return true;
    }

    public function fetchData()
    {
        return $this->synch($this->functionCall, $this->endpointColumns);
    }

    public static function synchKEData()
    {

        $data = DB::connection('oracle')->select('select * from fin.fin_gl_vw');
        foreach ($data as $key => $value) {
            $account = (array) $value;
            $accounts = explode('->', $account['chart of group']);
            dd($accounts);
        }
    }

    

}


