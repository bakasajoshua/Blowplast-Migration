<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccountsBreakdown extends Model
{
    protected $table = 'LU_GL_Accounts_Level_3';

    protected $guarded = [];

    public $timestamps = false;
}
