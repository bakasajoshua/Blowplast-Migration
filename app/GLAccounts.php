<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GLAccounts extends Model
{
    protected $table = 'GL Accounts';

    protected $guarded = [];

    public $timestamps = false;

    private $functionCall = "GetGLAccount";

    public function getFromApi()
    {
    	return $this->parse_xml(SoapCli::call($this->functionCall));
    }

    public function parse_xml($xml)
    {
    	$oXml = new \XMLReader();
        try {
            return $this->parseXml($xml);
        } catch (Exception $e) {
            echo $e->getMessage(). ' | Try open file: '.$sXmlFilePath;
        }
    }
}