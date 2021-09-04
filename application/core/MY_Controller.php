<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Rest_api extends REST_Controller {

    protected $data = array();

    function __construct() {
        parent::__construct();
        $request_headers=$this->input->request_headers();
        $this->data['request_headers']=$request_headers;
        $token = $request_headers['token'];
        $x_api_key = $request_headers['x-api-key'];
        $_POST = json_decode(file_get_contents("php://input"), true);       

        $api_token = API_TOKEN;
        $x_api_key_user =X_API_KEY;
        if($token != $api_token || $x_api_key != $x_api_key_user)
        {
             $this->commonApiController(null,null,REST_Controller::HTTP_UNAUTHORIZED,"Unauthorized",REST_Controller::HTTP_UNAUTHORIZED);
             die;
        }
    }
    
    //NOTE: If pagination required then need to pass totalRecords to check whether require to return 404 or 204
    //NOTE: If need to handle status code other than 404 and 204 (208, 409, 406) pass status code from master.php and also pass status message to display.

    public function commonApiController($commonRes, $totalRecords = null, $internalStatusCode = null,$statusMessage = '',$externalStatusCode=REST_Controller::HTTP_OK) {
    
        if($internalStatusCode == null)
        {
            if ($totalRecords == null) {
                if ($commonRes) {
                    $this->commonResponseJson($commonRes,REST_Controller::HTTP_OK, '');
                } else {
                    $this->commonResponseJson($commonRes,REST_Controller::HTTP_NOT_FOUND, 'No data found.');
                }
            } else {
                if($totalRecords == 0) {
                    $this->commonResponseJson($commonRes,REST_Controller::HTTP_NOT_FOUND, '');
                } else {
                    if ($commonRes) {
                        $this->commonResponseJson($commonRes,REST_Controller::HTTP_OK, '');
                    } else {
                        $this->commonResponseJson($commonRes,REST_Controller::HTTP_NO_CONTENT,'No more data found.');
                    }
                }
            }
        }
        else 
        {
            $this->commonResponseJson($commonRes, $internalStatusCode,$statusMessage,$externalStatusCode);
        }

    }

    public function commonResponseJson($commonRes, $internalStatusCode,$statusMessage,$externalStatusCode=REST_Controller::HTTP_OK) {
        $this->response([
            'StatusCode' => $internalStatusCode,
            'Message' => $statusMessage,
            'Success' => $commonRes
        ],$externalStatusCode);
    }

    public function getPostParams(){
        $requestData = array();
        $requestData = json_decode(file_get_contents('php://input'),false);
        return $requestData;
    }

    public function getJsonDict($data) {
        return $value = json_decode(json_encode($data), true);
    }

}

?>
