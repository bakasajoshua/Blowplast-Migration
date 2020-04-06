<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    protected $table = 'LU_GL_Accounts_Level_1';

    protected $guarded = [];

    public $timestamps = false;
}
