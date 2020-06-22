<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccounts extends Model
{
    protected $table = 'GL_Accounts_Level_2';

    protected $guarded = [];

    protected $primaryKey = 'Level_2_ID';

    public $timestamps = false;

<<<<<<< HEAD
    protected $keyType = 'string';

    protected $primaryKey = 'Level_2_ID';
=======
    public function level1()
    {
    	return $this->belongsTo(AccountType::class, 'Level_1_ID', 'Level_1_ID');
    }
>>>>>>> 7915e832246f33f7717226463075d4e9e2f26171
}
