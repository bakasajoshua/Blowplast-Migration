<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'Item Master';

    protected $guarded = [];

    public $timestamps = false;

    private $functionCall = "GetInventory";

    public function getFromApi()
    {
        return SoapCli::call($this->functionCall);
    }
}
