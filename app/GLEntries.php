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
