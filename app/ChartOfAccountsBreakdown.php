<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccountsBreakdown extends Model
{
    protected $table = 'GL_Accounts_Level_3';

    protected $guarded = [];

    protected $primaryKey = 'Level_3_ID';

    public $timestamps = false;

<<<<<<< HEAD
    protected $keyType = 'string';

    protected $primaryKey = 'Level_3_ID';
=======
    public function level2()
    {
    	return $this->belongsTo(ChartOfAccounts::class, 'Level_2_ID', 'Level_2_ID');
    }
>>>>>>> 7915e832246f33f7717226463075d4e9e2f26171
}
