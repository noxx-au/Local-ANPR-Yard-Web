<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class TestXML extends CI_Controller {

	public function __construct() {
        parent::__construct();
   

   
    }

    public function index() {
    	
       // Set the url you're making an api call to
      $endpoint = 'http://10.72.50.111:1234/xml/requestAccess';
      $xml='<?xml version="1.0" encoding="utf-8"?>
            <RequestAccess>
            <Type>ENTRY</Type>
            <Unit_ID>Inbound</Unit_ID>
            <Lane_ID>L1</Lane_ID>
            <Status>GRANTED</Status>
            </RequestAccess>';
      $curl = curl_init();

      curl_setopt_array($curl, [
          CURLOPT_URL => $endpoint,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $xml,
          CURLOPT_HTTPHEADER => [
              "Content-Type: application/xml",
              'token: e2fc714c4727ee9395f324cd2e7f331f', 
              'x-api-key:0cc175b9c0f1b6a831c399e269772661'
          ]
      ]);

      $response = curl_exec($curl);
      $error = curl_error($curl);

      curl_close($curl);

      if ($error) {
        echo "cURL Error #:" . $error;
      } else {
        echo $response;
      }

     
    }


}

?>
