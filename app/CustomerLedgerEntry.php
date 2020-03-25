<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerLedgerEntry extends Model
{
    protected $table = 'Customer Ledger Entries';

    protected $primaryKey = 'CU_Leg_Entry_No';

    protected $keyType = 'string';

    protected $guarded = [];

    public $timestamps = false;

    private $functionCall = "GetCustomerLedgerEntries";

	public static function boot()
    {
        parent::boot();
        static::creating(function (Model $model) {
            $model->CU_Leg_Entry_No = $model->count() + 1;
        });
    }

    public function getFromApi()
    {
        return SoapCli::call($this->functionCall);
    }
}