<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerBudget extends Model
{
    protected $table = 'Customer Budget';

    protected $guarded = [];

    public $timestamps = false;
}
