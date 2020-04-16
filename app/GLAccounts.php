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
                    'GL_Account_Level_3' => 'Chart_x0020_of_x0020_Account_x0020_Group_x0020_Name'
                ];
    private $chunkQty = 500;

    public function synchAccounts()
    {
        $synchData = $this->synch($this->functionCall, $this->endpointColumns);
        
        return $synchData;
        // foreach ($chunks as $key => $data) {
        //     GLAccounts::insert($data->toArray());
        // }
        // return true;
    }

}
