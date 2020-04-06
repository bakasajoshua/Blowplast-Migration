<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccounts extends Model
{
    protected $table = 'LU_GL_Accounts_Level_2';

    protected $guarded = [];

    public $timestamps = false;
}
