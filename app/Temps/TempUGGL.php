<?php

namespace App\Temps;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class TempUGGL extends BaseModel
{
    protected $guarded = [];

    public $timestamps = false;

    private $functionCall = "GetGLAccount";
    
    private $endpointColumns = [
                    'GL_Account_No' => 'GL_x0020_Account_x0020_No',
                    'GL_Account_Name' => 'GL_x0020_Account_x0020_Name',
                    'Income_Balance' => 'Income_x002F__x0020_Balance',
                    'Status' => 'Status',
                    'Company_Code' => 'Company_x0020_Code',
                    'GL_Account_Type' => 'GL_x0020_Account_x0020_Type',
                    'Chart_of_Account_Group' => 'Chart_x0020_of_x0020_Account_x0020_Group',
                    'Chart_ofAccount_Group_Name' => 'Chart_x0020_of_x0020_Account_x0020_Group_x0020_Name'
                ];

    private $chunkQty = 10;
    

    public function synchEntries($params = [])
    {
        ini_set("memory_limit", "-1");
        $chunks = $this->synch($this->functionCall, $this->endpointColumns, $params)->chunk($this->chunkQty);
        foreach ($chunks as $key => $data) {
            TempUGGL::insert($data->toArray());
        }
        return true;
    }

}
