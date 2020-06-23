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
                    //'GL_Account_Level_3' => 'Chart_x0020_of_x0020_Account_x0020_Group_x0020_Name'
                ];

    private $chunkQty = 10;

    public static function insertData()
    {
        $gl = new GLAccounts;
        $gl->synchMasterAccounts();
        // $gl->synchAccounts();
        // $gl->synchNewAccounts();
        return true;
    }

    public function synchMasterAccounts()
    {
        AccountType::truncate();
        ChartOfAccounts::truncate();
        ChartOfAccountsBreakdown::truncate();
        GLAccountLevel4::truncate();
        GLAccounts::truncate();
        $reference = GLEntries::selectRaw("DISTINCT GL_Account_No")->get();
        $synchData = collect($this->synch($this->functionCall, $this->endpointColumns));
        $level_1_accounts = $synchData->where('Chart_of_Account_Group', NULL);
        foreach ($level_1_accounts as $level_1_Key => $level_1_value) {
            if ($reference->where("GL_Account_No", $level_1_value['GL_Account_No'])->isEmpty()) {
                // Insert the Level 1 Accounts
                $level_1_db = AccountType::insert([
                                'Level_1_ID' => $level_1_value['GL_Account_No'],
                                'Level_1_Description' => $level_1_value['GL_Account_Name'],
                            ]);
                // Get the Level 2 Accounts belonging to Level 1 Account
                $level_2_accounts = $synchData->where('Chart_of_Account_Group', $level_1_value['GL_Account_No']);
                foreach ($level_2_accounts as $level_2_key => $level_2_value) {
                    // if (!ChartOfAccounts::find($level_2_value['GL_Account_No']))
                    if ($reference->where("GL_Account_No", $level_2_value['GL_Account_No'])->isEmpty()) {
                        $level_2_db = ChartOfAccounts::create([
                            'Level_2_ID' => $level_2_value['GL_Account_No'],
                            'Level_2_Description' => $level_2_value['GL_Account_Name'],
                            'Level_1_ID' => $level_1_value['GL_Account_No'],
                        ]);
                        // Get the Level 3 Accounts belonging to Level 2 Account
                        $level_3_accounts = $synchData->where('Chart_of_Account_Group', $level_2_value['GL_Account_No']);
                        foreach ($level_3_accounts as $level_3_key => $level_3_value) {
                            // if (!ChartOfAccountsBreakdown::find($level_3_value['GL_Account_No']))
                            if ($reference->where("GL_Account_No", $level_3_value['GL_Account_No'])->isEmpty()) {
                                $level_3_db = ChartOfAccountsBreakdown::create([
                                            'Level_3_ID' => $level_3_value['GL_Account_No'],
                                            'Level_3_Description' => $level_3_value['GL_Account_Name'],
                                            'Level_2_ID' => $level_2_value['GL_Account_No'],
                                        ]);
                                // Get the Level 4 Accounts belonging to Level 3 Account
                                $level_4_accounts = $synchData->where('Chart_of_Account_Group', $level_3_value['GL_Account_No']);
                                foreach ($level_4_accounts as $level_4_key => $level_4_value) {
                                    if ($reference->where("GL_Account_No", $level_4_value['GL_Account_No'])->isEmpty()) {
                                        // if (!GLAccountLevel4::find($level_4_value['GL_Account_No']))
                                            $level_4_db = GLAccountLevel4::create([
                                                        'Level_4_ID' => $level_4_value['GL_Account_No'],
                                                        'Level_4_Description' => $level_4_value['GL_Account_Name'],
                                                        'Level_3_ID' => $level_3_value['GL_Account_No'],
                                                    ]);
                                            $gl_accounts = $synchData->where('Chart_of_Account_Group', $level_4_value['GL_Account_No']);
                                            foreach ($gl_accounts as $key => $gl_account) {
                                                $gl_account['level1'] = $level_2_value['Chart_of_Account_Group'];
                                                $gl_account['Level_1_ID'] = $level_1_value['GL_Account_No'];
                                                $gl_account['Level_1_Description'] = $level_1_value['GL_Account_Name'];
                                                $gl_account['level2'] = $level_3_value['Chart_of_Account_Group'];
                                                $gl_account['Level_2_ID'] = $level_2_value['GL_Account_No'];
                                                $gl_account['Level_2_Description'] = $level_2_value['GL_Account_Name'];
                                                $gl_account['level3'] = $level_4_value['Chart_of_Account_Group'];
                                                $gl_account['Level_3_ID'] = $level_3_value['GL_Account_No'];
                                                $gl_account['Level_3_Description'] = $level_3_value['GL_Account_Name'];
                                                $gl_account['level4'] = $gl_account['Chart_of_Account_Group'];
                                                $gl_account['Level_4_ID'] = $level_4_value['GL_Account_No'];
                                                $gl_account['Level_4_Description'] = $level_4_value['GL_Account_Name'];
                                                $this->createAccount($gl_account);
                                            }
                                    } else {
                                        $level_4_value['level1'] = $level_2_value['Chart_of_Account_Group'];
                                        $level_4_value['Level_1_ID'] = $level_1_value['GL_Account_No'];
                                        $level_4_value['Level_1_Description'] = $level_1_value['GL_Account_Name'];
                                        $level_4_value['level2'] = $level_3_value['Chart_of_Account_Group'];
                                        $level_4_value['Level_2_ID'] = $level_2_value['GL_Account_No'];
                                        $level_4_value['Level_2_Description'] = $level_2_value['GL_Account_Name'];
                                        $level_4_value['level3'] = $level_4_value['Chart_of_Account_Group'];
                                        $level_4_value['Level_3_ID'] = $level_3_value['GL_Account_No'];
                                        $level_4_value['Level_3_Description'] = $level_3_value['GL_Account_Name'];
                                        $this->createAccount($level_4_value);
                                    }
                                }
                            } else {
                                $level_3_value['level1'] = $level_2_value['Chart_of_Account_Group'];
                                $level_3_value['Level_1_ID'] = $level_1_value['GL_Account_No'];
                                $level_3_value['Level_1_Description'] = $level_1_value['GL_Account_Name'];
                                $level_3_value['level2'] = $level_3_value['Chart_of_Account_Group'];
                                $level_3_value['Level_2_ID'] = $level_2_value['GL_Account_No'];
                                $level_3_value['Level_2_Description'] = $level_2_value['GL_Account_Name'];
                                $this->createAccount($level_3_value);
                            }                            
                        }
                    } else {
                        $level_2_value['level1'] = $level_2_value['Chart_of_Account_Group'];
                        $level_2_value['Level_1_ID'] = $level_1_value['GL_Account_No'];
                        $level_2_value['Level_1_Description'] = $level_1_value['GL_Account_Name'];
                        $this->createAccount($level_2_value);
                    }
                }
            } else {
                $this->createAccount($level_1_value);
            }
        }
    }

    public function synchAccounts()
    {
        $level4 = GLAccountLevel4::with(['level3'])->get();
        $synchData = collect($this->synch($this->functionCall, $this->endpointColumns));
        foreach ($level4 as $key => $level) {
            // dd($level->level3->level2->['vel1)'] ?? NULL;
            $accounts = $synchData->where('Chart_of_Account_Group', $level->Level_4_ID);
            foreach ($accounts as $key => $account) {
                $blocked = 0;
                if ($account['Blocked'] !== 'N')
                    $blocked = 1;
                GLAccounts::create([
                           'GL_Account_No' => $account['GL_Account_No'],
                            'GL_Account_Name' => $account['GL_Account_Name'],
                            'Company_Code' => $account['Company_Code'],
                            'Blocked' => $blocked,
                            'GL_Account_Level_1' => $level->level3->level2->level1->Level_1_ID,
                            'GL_Account_Level_2' => $level->level3->level2->Level_2_ID,
                            'GL_Account_Level_3' => $level->level3->Level_3_ID,
                            'GL_Account_Level_4' => $level->Level_4_ID,
                        ]);
            }
        }
    }

    public function createAccount($data)
    {
        $blocked = 0;
        if ($data['Blocked'] !== 'N')
            $blocked = 1;
        GLAccounts::create([
                    'Level_1_ID' => $data['Level_1_ID'] ?? NULL,
                    'Level_1_Description' => $data['Level_1_Description'] ?? NULL,
                    'Level_2_ID' => $data['Level_2_ID'] ?? NULL,
                    'Level_2_Description' => $data['Level_2_Description'] ?? NULL,
                    'Level_3_ID' => $data['Level_3_ID'] ?? NULL,
                    'Level_3_Description' => $data['Level_3_Description'] ?? NULL,
                    'Level_4_ID' => $data['Level_4_ID'] ?? NULL,
                    'Level_4_Description' => $data['Level_4_Description'] ?? NULL,
                    'GL_Account_No' => $data['GL_Account_No'],
                    'GL_Account_Name' => $data['GL_Account_Name'],
                    'Company_Code' => $data['Company_Code'],
                    'Blocked' => $blocked,
                    'GL_Account_Level_1' => $data['level1'] ?? NULL,
                    'GL_Account_Level_2' => $data['level2'] ?? NULL,
                    'GL_Account_Level_3' => $data['level3'] ?? NULL,
                    'GL_Account_Level_4' => $data['level4'] ?? NULL,
                ]);
    }

    public function fetchData()
    {
        return $this->synch($this->functionCall, $this->endpointColumns);
    }

    public static function synchKEData()
    {
        // $data = self::dataSource();
        $data = DB::connection('oracle')->select('select * from fin.fin_gl_vw');
        foreach ($data as $key => $value) {
            $account = (array) $value;
            $account_levels = self::saveKEAccountLevels($account['chart of group']);
            // dd($account);
            // dd($accounts);
        }
        dd($account_levels);
    }

    private static function saveKEAccountLevels($account_string)
    {
        $accounts = explode('->', $account_string);
        $return_Level = [];
        if (array_key_exists(0,$accounts)) {
            // Save level 1 Account
            $level_1 = AccountType::where('Level_1_Description', $accounts[0])->first();
            if (!$level_1){
                $level_1 = AccountType::create([
                                        'Level_1_ID' => self::getGenericID(),
                                        'Level_1_Description' => $accounts[0]
                                    ]);
                
            }
            if (array_key_exists(1,$accounts)) {
                $level_2 = ChartOfAccounts::where('Level_2_Description', $accounts[1])->first();
                if (!$level_2){
                    // Save level 2 Account
                    $level_2 = ChartOfAccounts::create([
                                        'Level_2_ID' => self::getGenericID(),
                                        'Level_2_Description' => $accounts[1],
                                        'Level_1_ID' => $level_1->Level_1_ID
                                    ]);
                }
                if (array_key_exists(2,$accounts)) {
                    $level_3 = ChartOfAccountsBreakdown::where('Level_3_Description', $accounts[2])->first();
                    if (!$level_3){
                        // Save level 3 Account
                        $level_3 = ChartOfAccountsBreakdown::create([
                                            'Level_3_ID' => self::getGenericID(),
                                            'Level_3_Description' => $accounts[2],
                                            'Level_2_ID' => $level_2->Level_2_ID
                                        ]);
                    }
                    if (array_key_exists(3,$accounts)) {
                        $level_4 = GLAccountLevel4::where('Level_4_Description', $accounts[3])->first();
                        if (!$level_4){
                            // Save level 4 Account
                            $level_4 = GLAccountLevel4::create([
                                                'Level_4_ID' => self::getGenericID(),
                                                'Level_4_Description' => $accounts[3],
                                                'Level_3_ID' => $level_3->Level_3_ID
                                            ]);
                        }
                        return (object)['level1' => $level_1, 'level2' => $level_2, 'level3' => $level_3, 'level4' => $level_4];
                    }
                    return (object)['level1' => $level_1, 'level2' => $level_2, 'level3' => $level_3];
                }
                return (object)['level1' => $level_1, 'level2' => $level_2];
            }
            return (object)['level1' => $level_1];
        }
        return false;       
    }

    public static function getGenericID()
    {
        return round(microtime(true) * 1000);
        return date('YmdHisu');
    }

    // Delete this function once this works
    private static function dataSource()
    {
        return [[
                        "chart of group" => "EXPENSES->PURCHASE ACCOUNT->PUR. OF CONSUMABLE-R-M",
                        "coa name" => "PURCHASE OF PLASTIC GRANULES BLOW",
                        "opening bal" => "0",
                        "opening bal type" => "Dr",
                        "voucher no" => "FIN\PV\000440\2020",
                        "voucher date" => "2020-01-01 00:00:00",
                        "narration" => "19MBAIM000605914 - MARINE COVER",
                        "doc no" => null,
                        "doc date" => null,
                        "specific amount" => "19177",
                        "currency" => "KES",
                        "booking rate" => "1",
                        "debit" => "19177",
                        "credit" => "0",
                        "running balance" => "19177",
                    ],[
                        "chart of group" => "EXPENSES->PURCHASE ACCOUNT->PUR. OF CONSUMABLE-R-M",
                        "coa name" => "PURCHASE OF PLASTIC GRANULES CUP",
                        "opening bal" => "0",
                        "opening bal type" => "Dr",
                        "voucher no" => "FIN\PV\000441\2020",
                        "voucher date" => "2020-01-01 00:00:00",
                        "narration" => "19MBAIM000605915 - MARINE COVER",
                        "doc no" => null,
                        "doc date" => null,
                        "specific amount" => "13177",
                        "currency" => "KES",
                        "booking rate" => "1",
                        "debit" => "13177",
                        "credit" => "0",
                        "running balance" => "13177",
                    ],[
                        "chart of group" => "EXPENSES->PURCHASE ACCOUNT->PUR. OF Non-CONSUMABLE-R-M",
                        "coa name" => "PURCHASE OF PLASTIC CUDES BLOW",
                        "opening bal" => "0",
                        "opening bal type" => "Cr",
                        "voucher no" => "FIN\PV\000442\2020",
                        "voucher date" => "2020-01-01 00:00:00",
                        "narration" => "19MBAIM000605916 - MARINE COVER",
                        "doc no" => null,
                        "doc date" => null,
                        "specific amount" => "19177",
                        "currency" => "KES",
                        "booking rate" => "1",
                        "debit" => "0",
                        "credit" => "19177",
                        "running balance" => "19177",
                    ]
                ];
    }
}


