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
    	$reader = new \XMLReader();
		$reader->open(SoapCli::call($this->functionCall));
		while ($reader->read()) {
		  	if ($reader->nodeType == XMLReader::END_ELEMENT) {
		    	continue;
		  	}

		  	//do something with desired node type
		  	print_r($reader);
		}﻿
		// dd("ENd");
  //   	return SoapCli::call($this->functionCall);
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