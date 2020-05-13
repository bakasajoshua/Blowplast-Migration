<?php
 namespace App;
 use SoapClient;

class SoapClientTimeout extends SoapClient
{    
    public function __construct ($wsdl, $options = null)
    {
        if (!$options) $options = [];
        if (!$options) {
            $this->_connectionTimeout = ini_get ('default_socket_timeout');
            $this->_socketTimeout = ini_get ('default_socket_timeout');
        } else {
            $this->_connectionTimeout = @$options['connection_timeout'] ?: ini_get ('default_socket_timeout');
            $this->_socketTimeout = @$options['socket_timeout'] ?: ini_get ('default_socket_timeout');
        }        
        unset ($options['socket_timeout']);

        parent::__construct($wsdl, $options);
    }

    /**
     * Override parent __doRequest and add "timeout" functionality.
     */
    public function __doRequest ($request, $location, $action, $version, $one_way = 0)
    {
        dd($request);
        // fetch host, port, and scheme from url.
        $url_parts = (object)parse_url($location);
        // dd($url_parts);
        $host = $url_parts->host;
        $port =  $url_parts->port ?? ($url_parts->scheme == 'https' ? 443 : 80);
        $length = strlen ($request);

        // create HTTP SOAP request.
        $http_req = "POST $location HTTP/1.0\r\n";
        $http_req .= "Host: $host\r\n";
        $http_req .= "SoapAction: $action\r\n";
        $http_req .= "Content-Type: text/xml; charset=utf-8\r\n";
        $http_req .= "Content-Length: $length\r\n";
        $http_req .= "\r\n";
        $http_req .= $request;

        // switch to SSL, when requested
        if ($url_parts->scheme == 'https') $host = 'ssl://'.$host;

        // connect
        $socket = @fsockopen($host, $port, $errno, $errstr, $this->_connectionTimeout);

        if (!$socket) {
            throw new SoapFault('Client',"Failed to connect to SOAP server ($location): $errstr");
        }

        // send request with socket timeout
        stream_set_timeout($socket, $this->_socketTimeout);
        fwrite ($socket, $http_req);

        // start reading the response.
        $http_response = stream_get_contents($socket);

        // close the socket and throw an exception if we timed out.
        $info = stream_get_meta_data($socket);
        fclose ($socket);
        if ($info['timed_out']) {
            throw new SoapFault ('Client', "HTTP timeout contacting $location");
        }

        // the stream contains XML data
        // lets extract the XML from the HTTP response and return it.
        $response = preg_replace (
            '/
                \A       # Start of string
                .*?      # Match any number of characters (as few as possible)
                ^        # Start of line
                \r       # Carriage Return
                $        # End of line
             /smx',
            '', $http_response
        );
        return $response;
    }

}