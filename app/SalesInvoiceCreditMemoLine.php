<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesInvoiceCreditMemoLine extends Model
{
    protected $table = 'Sales Invoice Credit Memo Lines';

    protected $guarded = [];

    public $timestamps = false;
}
