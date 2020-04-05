<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GLAccounts extends Model
{
    protected $table = 'GL Accounts';

    protected $guarded = [];

    public $timestamps = false;

    private $functionCall = "GetGLAccount";

    public function getFromApi()
    {
    	return SoapCli::call($this->functionCall);
    }
}