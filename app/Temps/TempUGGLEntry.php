<?php

namespace App\Temps;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class TempUGGLEntry extends BaseModel
{
    protected $connection = 'testdb';

    protected $guarded = [];

    private $functionCall = "GetGLEntries";

    private $endpointColumns = [
			'Entry_No' => 'Entry_x0020_No',
			'TransType' => 'TransType',
			'GroupMask' => 'GroupMask',
			'GL_Account_Number' => 'GL_x0020_Account_x0020_Number',
			'Balancing_GL_Account_No' => 'Balancing_x0020_GL_x0020_Account_x0020_No',
			'Debit' => 'Debit',
			'Credit' => 'Credit',
			'Amount' => 'Amount',
			'TransCurr' => 'TransCurr',
			'Posting_Date' => 'Posting_x0020_Date',
			'Document_Number' => 'Document_x0020_Number',
			'Document_Type' => 'Document_x0020_Type',
			'Description' => 'Description',
			'Company_Code' => 'Company_x0020_Code'
		];
    private $chunkQty = 100;

    public function synchEntries($params = [])
    {
        ini_set("memory_limit", "-1");
        $chunks = $this->synch($this->functionCall, $this->endpointColumns, $params)->chunk($this->chunkQty);
        foreach ($chunks as $key => $data) {
            TempUGGLEntry::insert($data->toArray());
        }
        return true;
    }


}
