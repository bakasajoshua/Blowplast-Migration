<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Tightenco\Collect\Support\Collection;
use Rodenastyle\StreamParser\StreamParser;
use Illuminate\Support\Facades\Redis;

class GLAccounts extends BaseModel
{
    protected $table = 'GL Accounts';

    protected $guarded = [];

    public $timestamps = false;

    private $functionCall = "GetGLAccount";
    // private $functionCall = "HelloWorld";
    private $endpointColumns = [
                    'GL_Account_No' => 'GL_x0020_Account_x0020_No',
                    'GL_Account_Name' => 'GL_x0020_Account_x0020_Name',
                    'Income_Balance' => 'Income_x002F__x0020_Balance',
                    'Blocked' => 'Status',
                    'Company_Code' => 'Company_x0020_Code',
                    'GL_Account_Level_1' => 'GL_x0020_Account_x0020_Type',
                    'GL_Account_Level_2' => 'Chart_x0020_of_x0020_Account_x0020_Group',
                    //'GL_Account_Level_3' => 'Chart_x0020_of_x0020_Account_x0020_Group_x0020_Name'
                ];
    private $chunkQty = 10;

    public function synchAccounts()
    {
        $level_1_Data = AccountType::get();
        $level_2_Data = ChartOfAccounts::get();
        $synchData = $this->synch($this->functionCall, $this->endpointColumns);
        $accounts = [];
        foreach ($synchData as $key => $account) {
            $level_1 = $level_1_Data->where('Level_1_Description', $account['GL_Account_Level_1']);
            if (!$level_1->isEmpty()){
                $account['GL_Account_Level_1'] = $level_1->first()->Level_1_ID;
            } else {
                unset($account['GL_Account_Level_1']);
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

}
