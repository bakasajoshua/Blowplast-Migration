<?php

namespace App;

class SoapCli
{
    public static function call($endpoint, $params = [])
    {
    	$soapClient = new \SoapClient(env('SOAP_URL'));

    	$error = 0;
        try {
            return $soapClient->__call($endpoint, $params);
        } catch (\SoapFault $fault) {
            $error = 1;
            print("
            alert('Sorry, blah returned the following ERROR: ".$fault->faultcode."-".$fault->faultstring.". We will now take you back to our home page.');
            window.location = 'main.php';
            ");
        }
    }
}
