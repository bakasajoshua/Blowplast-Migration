<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryBudget extends Model
{
    protected $table = 'Item Budget';

    protected $guarded = [];

    public $timestamps = false;

    protected $primaryKey = 'Inventory_Budget_No';
}
