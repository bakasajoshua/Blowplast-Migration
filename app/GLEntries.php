<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GLEntries extends BaseModel
{
    protected $table = 'GL Entries';

    // protected $primaryKey = 'GL_Entry_No';

    // protected $keyType = 'string';

    protected $guarded = [];

    public $timestamps = false;

    private $functionCall = "GetGLEntries";

    private $endpointColumns = [
        'GL_Entry_No' => 'Entry_x0020_No',
        'GL_Account_No' => 'GL_x0020_Account_x0020_Number',
        'Balancing_GL_Account_No' => 'Balancing_x0020_GL_x0020_Account_x0020_No',
        'Amounts' => 'Amount',
        'GL_Posting_Date' => 'Posting_x0020_Date',
        'GL_Document_No' => 'Document_x0020_Number',
        'GL_Document_Type' => 'Document_x0020_Type',
        'Description' => 'Description',
        'Company_Code' => 'Company_x0020_Code',
    ];
    private $chunkQty = 100;

    public function synchEntries()
    {
        ini_set("memory_limit", "-1");
        $chunks = $this->synch($this->functionCall, $this->endpointColumns)->chunk($this->chunkQty);
        foreach ($chunks as $key => $data) {
            GLEntries::insert($data->toArray());
        }
        return true;
    }

    public function call()
    {
        // $soapClient = new \SoapClient(env('SOAP_URL'));
        // $resultBody = $endpoint . "Result";

        // try {
            if(!is_dir(storage_path('app/endpoints/'))) mkdir(storage_path('app/endpoints/'), 0777);
            $file = fopen(storage_path('app/endpoints/' . $this->functionCall .'.xml'), "a");
            fwrite($file, $this->xml_header());
            fwrite($file, "\r\n");
            fwrite($file, $this->xml_footer());
            // $response = $soapClient->__call($endpoint, $params);
        //     $response = $soapClient->$endpoint();
        // } catch (\SoapFault $fault) {
        //     return (object)[
        //         'error' => true,
        //         'code' => $fault->faultcode,
        //         'mesage' => $fault->faultstring,
        //     ];
        // }
        // return $response->$resultBody;
    }

    // public function getFromApi($functionCall)
    // {
    //     if(!is_dir(storage_path('app/endpoints/'))) mkdir(storage_path('app/endpoints/'), 0777);

    //     $file = fopen(storage_path('app/endpoints/' . $functionCall .'.xml'), "a");

    //     $writeString = (string)($this->xml_header($functionCall) .
    //                     SoapCli::call($functionCall)->any . 
    //                     $this->xml_footer($functionCall));

    //     if (fwrite($file, $writeString) === FALSE)
    //         fwrite("Error: no data written");

    //     fwrite($file, "\r\n");
    //     fclose($file);

    //     return true;
    // }

    private function xml_header()
    {
        return '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><' . $this->functionCall . 'Response xmlns="http://tempuri.org/"><' . $this->functionCall . 'Result>';
    }

    private function xml_footer()
    {
        return '</' . $this->functionCall . 'Result></' . $this->functionCall . 'Response></soap:Body></soap:Envelope>';
    }
}