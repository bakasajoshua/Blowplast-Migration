<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GLEntries extends Model
{
    protected $table = 'GL Entries';

    protected $primaryKey = 'GL_Entry_No';

    protected $keyType = 'string';

    protected $guarded = [];

    public $timestamps = false;

    private $functionCall = "GetGLEntries";

    private $endpointColumns = [
        'GL_Entry_No' => 'Entry_x0020_No'
        'GL_Account_No' => 'GL_x0020_Account_x0020_Number'
        'Balancing_GL_Account_No' => 'Balancing_x0020_GL_x0020_Account_x0020_No'
        'Amounts' => 'Amount'
        'GL_Posting_Date' => 'Posting_x0020_Date'
        'GL_Document_No' => 'Document_x0020_Number'
        'GL_Document_Type' => 'Document_x0020_Type'
        'Description' => 'Description'
        'Company_Code' => 'Company_x0020_Code'
    ]

	public static function boot()
    {
        parent::boot();
        static::creating(function (Model $model) {
            $model->GL_Entry_No = $model->count() + 1;
        });
    }

    public function getFromApi()
    {
        return SoapCli::call($this->functionCall);
    }
}