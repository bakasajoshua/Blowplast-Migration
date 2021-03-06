<?php

namespace App;

class SoapCli
{
    public static function call($endpoint, $params = [])
    {
    	$soapClient = new \SoapClient(env('SOAP_URL'));
        $resultBody = $endpoint . "Result";
    	try {
            // $response = $soapClient->__call($endpoint, $params);
            $response = $soapClient->$endpoint($params);
        } catch (\SoapFault $fault) {
            return (object)[
                'error' => true,
                'code' => $fault->faultcode,
                'mesage' => $fault->faultstring,
            ];
        }
        return $response->$resultBody;
    }

    // public static function callFunction
}
