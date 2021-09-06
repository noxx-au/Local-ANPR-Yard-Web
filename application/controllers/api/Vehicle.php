<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vehicle extends Rest_api {

    public function __construct() {
        parent::__construct();
        // $this->load->model('Car_plates_model', 'car_plates');
        $this->load->model('Common_function_model', 'common');
    }
    public function index() {
        
    }
    function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    function exit_validation()
    {
        $cie_id = $this->post('cie_id');
        $vehicle_date_time = $this->post('vehicle_date_time');
        
        if ((!is_numeric($cie_id)) && $cie_id !=  '') 
        {
            $this->commonApiController(false,null,REST_Controller::HTTP_BAD_REQUEST,"cie_id must be allow integer value",REST_Controller::HTTP_BAD_REQUEST);
        }

        if(!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $vehicle_date_time)) {
            $this->commonApiController(false,null,REST_Controller::HTTP_BAD_REQUEST,"vehicle_date_time must allow this Y-m-d H:i:s",REST_Controller::HTTP_BAD_REQUEST);
            die;
        } else {
            $date_validate = $this->validateDate($vehicle_date_time);
            if ($date_validate) {
                $today_date=date('Y-m-d H:i:s');
                $current_date=strtotime($today_date);
                $vehicle_date_times=strtotime($vehicle_date_time);
                if($current_date<$vehicle_date_times)
                {
                    $this->commonApiController(false,null,REST_Controller::HTTP_BAD_REQUEST,"future vehicle_date_time not allowed",REST_Controller::HTTP_BAD_REQUEST);
                    die;
                } else {
                    return true;
                }
            } else{
                $this->commonApiController(false,null,REST_Controller::HTTP_BAD_REQUEST,"vehicle_date_time not proper.",REST_Controller::HTTP_BAD_REQUEST);
                die;
            }
        }
    }
    function entry_validation()
    {  
        $cie_id = $this->post('cie_id');
        $driver_name = $this->post('name');
        $company_name = $this->post('transport_company');
        $driver_mobile_no = $this->post('mobile');
        $rapid_id = $this->post('rapid_id');
        $vehicle_type = $this->post('vehicle_type');
        $rego = $this->post('vehicle_rego');
        $vehicle_image = $this->post('vehicle_image');
        $driver_image = $this->post('driver_image');
        $order_number = $this->post('order_number');
        $container_no = $this->post('container_no');
        $axel_group_weight = $this->post('axel_group_weight');
        $total_weight = $this->post('total_weight');
        $vehicle_date_time = $this->post('vehicle_date_time');
        $trailer_rego = $this->post('trailer_rego');
        if(!(isset($_FILES["vehicle_image"]) ||  !empty($vehicle_image)))
        {
            $this->commonApiController(false,null,REST_Controller::HTTP_BAD_REQUEST,"vehicle_image image missing",
                REST_Controller::HTTP_BAD_REQUEST);
        }
          if(!(isset($_FILES["driver_image"]) ||  !empty($driver_image)))
        {
            $this->commonApiController(false,null,REST_Controller::HTTP_BAD_REQUEST,"driver_image image missing",
                REST_Controller::HTTP_BAD_REQUEST);
        }

        if ((strlen($container_no) < 5 || strlen($container_no) > 16) && $container_no !=  '')
        {

             $this->commonApiController(false,null,REST_Controller::HTTP_BAD_REQUEST,"container_no must be allow max 16 char min 5",REST_Controller::HTTP_BAD_REQUEST);
        } 
        if (!(strtolower($vehicle_type) == 'car' || strtolower($vehicle_type) == 'truck'))
        {
             $this->commonApiController(false,null,REST_Controller::HTTP_BAD_REQUEST,"vehicle_type allow only car or truck",REST_Controller::HTTP_BAD_REQUEST);
        }
        if ((!is_numeric($axel_group_weight)) && $axel_group_weight !=  '')
        {

             $this->commonApiController(false,null,REST_Controller::HTTP_BAD_REQUEST,"axel_group_weight must be allow integer value",REST_Controller::HTTP_BAD_REQUEST);
        }

        if ((!is_numeric($rapid_id)) && $rapid_id !=  '')
        {

             $this->commonApiController(false,null,REST_Controller::HTTP_BAD_REQUEST,"rapid_id must be allow integer value",REST_Controller::HTTP_BAD_REQUEST);
        }
        if (!(strlen($rego) > 4 && strlen($rego) < 10)) 
        {
             $this->commonApiController(false,null,REST_Controller::HTTP_BAD_REQUEST,"vehicle_rego must be between 4 to 10 characters",REST_Controller::HTTP_BAD_REQUEST);
        }

        if ((!is_numeric($cie_id)) && $cie_id !=  '') 
        {

             $this->commonApiController(false,null,REST_Controller::HTTP_BAD_REQUEST,"cie_id must be allow integer value",REST_Controller::HTTP_BAD_REQUEST);
        }
        if ((!is_numeric($total_weight)) && $total_weight !=  '')
        {

            $this->commonApiController(false,null,REST_Controller::HTTP_BAD_REQUEST,"total_weight must be allow integer value",REST_Controller::HTTP_BAD_REQUEST);
        } 
       if (!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $vehicle_date_time)) {
            $this->commonApiController(false,null,REST_Controller::HTTP_BAD_REQUEST,"vehicle_date_time must allow this Y-m-d H:i:s",REST_Controller::HTTP_BAD_REQUEST);
            die;
        } else {

            $date_validate = $this->validateDate($vehicle_date_time);

            if ($date_validate) {
                $today_date=date('Y-m-d H:i:s');
                $current_date=strtotime($today_date);
                $vehicle_date_times=strtotime($vehicle_date_time);
                if($current_date<$vehicle_date_times)
                {
                    $this->commonApiController(false,null,REST_Controller::HTTP_BAD_REQUEST,"future vehicle_date_time not allowed",REST_Controller::HTTP_BAD_REQUEST);
                    die;
                } else {
                    return true;
                }
            } else{
                $this->commonApiController(false,null,REST_Controller::HTTP_BAD_REQUEST,"vehicle_date_time not proper.",REST_Controller::HTTP_BAD_REQUEST);
                die;
            }
        }
    }
    public function exit_post() 
    {
       
        $api_data = $this->post();
          $api_details = array(
            'api_name' => 'exit',
            'api_method' => 'POST',
            'api_data' => json_encode($api_data)
        );
        $this->common->insert_record('api_log', $api_details);


        $this->exit_validation();
        $insert_data=array(
            'request_data'=>json_encode($api_data),
            'request_type'=>'exit',
            'api_log_id'=>$this->db->insert_id()
        );
        $insert_id = $this->common->insert_record('car_plates', $insert_data);
      
        if ($insert_id) 
        {
             $this->commonApiController(true,null,REST_Controller::HTTP_OK,'vehicle exit confirmed.',REST_Controller::HTTP_OK);
        }
        else 
        {
            $this->commonApiController(false,null,REST_Controller::HTTP_BAD_REQUEST,"vehicle not available on site. ",REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function entry_post() {
        $vehicle_image = $this->post('vehicle_image');
        $driver_image = $this->post('driver_image');
     
        $api_data = $this->post();
        $api_details = array(
            'api_name' => 'entry',
            'api_method' => 'POST',
            'api_data' => json_encode($api_data)
        );
        $this->common->insert_record('api_log', $api_details);

        $this->entry_validation();      

        unset($api_data['vehicle_image']);
        unset($api_data['driver_image']);
        $insert_data=array(
            'request_data'=>json_encode($api_data),
            'request_type'=>'entry',
            'driver_image'=>$driver_image,
            'vehicle_image'=>$vehicle_image,
            'api_log_id'=>$this->db->insert_id()
        );
        $insert_id = $this->common->insert_record('car_plates', $insert_data);
        
      
        if ($insert_id > 0) 
        {
            $this->commonApiController(true,null,REST_Controller::HTTP_OK,'vehicle has been add successfully.',REST_Controller::HTTP_OK);
        }
    }
}

    

    
