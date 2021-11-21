<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class TestXML extends CI_Controller {

    public function __construct() {
        parent::__construct();
   

   
    }

public function index() {
 
//The XML string that you want to send.
$xml = '<?xml version="1.0" encoding="utf-8"?>
<TruckWeighingRequestData>
<messageID>f81d4fae-7dec-11d0-a765-00a0c91e6bf6</messageID>
<DateTime>'.date("Y-m-d H:i:s").'</DateTime>
<TruckRegoNo>ABC123</TruckRegoNo>
<Limits>
<Group1>6000</Group1>
<Group2>10000</Group2>
<Group3>15000</Group3>
<Group4>15000</Group4>
<Group5>15000</Group5>
</Limits>
<AdditionalInfo>"936783;T3169U647,21610kg;"</AdditionalInfo>
</TruckWeighingRequestData>';


//The URL that you want to send your XML to.
$url = 'http://10.3.18.63:1234/xml/TruckWeighingRequestData';
// $url="https://api.noxx.com.au/api/xml/requestAccess";

//Initiate cURL
$curl = curl_init($url);

//Set the Content-Type to text/xml.
curl_setopt ($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));

//Set CURLOPT_POST to true to send a POST request.
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_HEADER, 1);

//Attach the XML string to the body of our request.
curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);

//Tell cURL that we want the response to be returned as
//a string instead of being dumped to the output.
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


//Execute the POST request and send our XML.
$result = curl_exec($curl);
$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

//echo   "<br/><br/><br/>#HEADER_CODE:".$httpcode;
// Then, after your curl_exec call:
$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
$header = substr($result, 0, $header_size);
$body = substr($result, $header_size);
if($header!="503")
{
    log_message('debug',$body.'\r\n');
}
else
{
    
}
//echo  "<br/><br/><br/>#HEADER:".$header;
//
//echo "<br/><br/><br/>#BODY:".$body;
//Do some basic error checking.
if(curl_errno($curl)){
    throw new Exception(curl_error($curl));
}

//Close the cURL handle.
curl_close($curl);
          $this->load->view('vwTextXML');
//Print out the response output.
//echo $result;

     
    }
}

?>