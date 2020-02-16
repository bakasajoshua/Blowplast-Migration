<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'Inventories';

    protected $guarded = [];

    public $timestamps = false;
}
