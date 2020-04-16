<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function synch($functionCall, $endpointColumns)
    {
    	$write_to_disk = $this->getFromApi($functionCall);
    	
    	if ($write_to_disk)
    		return $this->getData($functionCall, $endpointColumns);

    	return false;
    }

    public function getFromApi($functionCall)
    {
        if(!is_dir(storage_path('app/endpoints/'))) mkdir(storage_path('app/endpoints/'), 0777);

        $file = fopen(storage_path('app/endpoints/' . $functionCall .'.xml'), "a");

        $writeString = (string)($this->xml_header($functionCall) .
        				SoapCli::call($functionCall)->any . 
        				$this->xml_footer($functionCall));

		if (fwrite($file, $writeString) === FALSE)
			fwrite("Error: no data written");

		fwrite($file, "\r\n");
		fclose($file);

		return true;
    }

    public function getData($functionCall, $endpointColumns)
    {
    	$file_path = storage_path('app/endpoints/' . $functionCall .'.xml');
    	$z = new \XMLReader;
		$z->open(storage_path('app/endpoints/' . $functionCall .'.xml'));

		$doc = new \DOMDocument;
		$data = [];
		$count = 0;
		
		while ($z->read())
		{
			while ($z->name == "Table") {
				$node = simplexml_import_dom($doc->importNode($z->expand(), true));
				foreach ($endpointColumns as $key => $value) {
					$data[$count][$key] = ($key == 'Company_Code' && 
												NULL === collect((array)$node->$value)->first()) ?
											'BUL' :
											collect((array)$node->$value)->first() ?? '';
				}
		    	$z->next('Table');
		    	$count++;
			}
		}
		if (!unlink($file_path)) {  
		    echo ("$file_path cannot be deleted due to an error");  
		}

		return collect($data);
    }

    private function xml_header($functionCall)
    {
    	return '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><' . $functionCall . 'Response xmlns="http://tempuri.org/"><' . $functionCall . 'Result>';
    }

    private function xml_footer($functionCall)
    {
    	return '</' . $functionCall . 'Result></' . $functionCall . 'Response></soap:Body></soap:Envelope>';
    }











    public function dump_log($name, $writedata=null)
    {
    	if(!is_dir(storage_path('endpoints/logs/'))) mkdir(storage_path('endpoints/logs/'), 0777);

		$postData = file_get_contents('php://input');
    	if (isset($writedata))
    		$postData = json_encode($writedata);
		
		$file = fopen(storage_path('app/logs/' . $name .'.txt'), "a");
		if(fwrite($file, $postData) === FALSE) fwrite("Error: no data written");
		fwrite($file, "\r\n");
		fclose($file);


		try {
			$postData = json_decode($postData);
			return $postData;
		} catch (Exception $e) {
			print_r($e);
		}
		return $postData;
    }

    protected function parseMyXML ($xml) { //pass in an XML string
	    $myXML = new \XMLReader();
	    $myXML->xml($xml);

	    while ($myXML->read()) { //start reading.
	    	print_r($myXML->name);
	        /*if ($myXML->nodeType == \XMLReader::ELEMENT) { //only opening tags.
	            $tag = $myXML->name; //make $tag contain the name of the tag
	            // $myXML->readInnerXML();
	            return $myXML->readInnerXML();
	            switch ($tag) {
	                case 'Tag1': //this tag contains no child elements, only the content we need. And it's unique.
	                    $variable = $myXML->readInnerXML(); //now variable contains the contents of tag1
	                    break;
	                case 'Tag2': //this tag contains child elements, of which we only want one.
	                    while($myXML->read()) { //so we tell it to keep reading
	                        if ($myXML->nodeType == XMLReader::ELEMENT && $myXML->name === 'Amount') { // and when it finds the amount tag...
	                            $variable2 = $myXML->readInnerXML(); //...put it in $variable2. 
	                            break;
	                        }
	                    }
	                    break;
	                case 'Tag3': //tag3 also has children, which are not unique, but we need two of the children this time.
	                    while($myXML->read()) {
	                        if ($myXML->nodeType == XMLReader::ELEMENT && $myXML->name === 'Amount') {
	                            $variable3 = $myXML->readInnerXML();
	                            break;
	                        } else if ($myXML->nodeType == XMLReader::ELEMENT && $myXML->name === 'Currency') {
	                            $variable4 = $myXML->readInnerXML();
	                            break;
	                        }
	                    }
	                    break;
	            }
	        }*/
	    }
		$myXML->close();
	}
}
/*switch ($tag) {
	                case 'Tag1': //this tag contains no child elements, only the content we need. And it's unique.
	                    $variable = $myXML->readInnerXML(); //now variable contains the contents of tag1
	                    break;
	                case 'Tag2': //this tag contains child elements, of which we only want one.
	                    while($myXML->read()) { //so we tell it to keep reading
	                        if ($myXML->nodeType == XMLReader::ELEMENT && $myXML->name === 'Amount') { // and when it finds the amount tag...
	                            $variable2 = $myXML->readInnerXML(); //...put it in $variable2. 
	                            break;
	                        }
	                    }
	                    break;
	                case 'Tag3': //tag3 also has children, which are not unique, but we need two of the children this time.
	                    while($myXML->read()) {
	                        if ($myXML->nodeType == XMLReader::ELEMENT && $myXML->name === 'Amount') {
	                            $variable3 = $myXML->readInnerXML();
	                            break;
	                        } else if ($myXML->nodeType == XMLReader::ELEMENT && $myXML->name === 'Currency') {
	                            $variable4 = $myXML->readInnerXML();
	                            break;
	                        }
	                    }
	                    break;
	            }*/