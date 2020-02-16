<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerLedgerEntry extends Model
{
    protected $table = 'Customer Ledger Entries';

    protected $guarded = [];

    public $timestamps = false;
}
