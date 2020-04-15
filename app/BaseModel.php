<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
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