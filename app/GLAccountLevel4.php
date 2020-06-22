<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GLAccountLevel4 extends Model
{
    protected $table = 'GL_Accounts_Level_4';

    protected $guarded = [];

    protected $primaryKey = 'Level_4_ID';

    public $timestamps = false;

    public function level3()
    {
    	return $this->belongsTo(ChartOfAccountsBreakdown::class, 'Level_3_ID', 'Level_3_ID');
    }
}
