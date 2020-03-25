<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesInvoiceCreditMemoHeader extends Model
{
    protected $table = 'Sales Invoice Credit Memo Headers';

    protected $guarded = [];

    public $timestamps = false;

    private $functionCall = "GetInvoiceCreditHeader";


    public function getFromApi()
    {
        return SoapCli::call($this->functionCall);
    }
}
