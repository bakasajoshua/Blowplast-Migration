<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'Customer Master';

    protected $guarded = [];

    public $timestamps = false;

    private $functionCall = "GetCustomers";

    public function getFromApi()
    {
        return SoapCli::call($this->functionCall);
    }
}
