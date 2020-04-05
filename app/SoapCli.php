<?php

namespace App;

class SoapCli
{
    public static function call($endpoint, $params = [])
    {
    	$soapClient = new \SoapClient(env('SOAP_URL'));
        $resultBody = $endpoint . "Result";

    	try {
            return $soapClient->__call($endpoint, $params)->resultBody;
        } catch (\SoapFault $fault) {
            return (object)[
                'error' => true,
                'code' => $fault->faultcode,
                'mesage' => $fault->faultstring,
            ];
        }
    }
}
