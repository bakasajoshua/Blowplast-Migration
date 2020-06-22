<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccounts extends Model
{
    protected $table = 'GL_Accounts_Level_2';

    protected $guarded = [];

    public $timestamps = false;

    protected $keyType = 'string';

    protected $primaryKey = 'Level_2_ID';
}
