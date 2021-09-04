<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CronLiveSync extends CI_Controller {
    public function __construct() 
	{
        parent::__construct();
		$this->load->model('Common_function_model', 'common');
    }
    public function start()
	{		
		while(1)
		{
			$this->index();
			sleep(delay_time);
		}
	}
 	public function index()
	{
		$result=$this->common->get_records('car_plates', 'live_sync', '0');
		if(!empty($result)){
			foreach ($result as $key => $row) {
				$handle = curl_init();
				// $headers[] = 'Authorization: Token '.$live_api_key;
				$headers =  array(
			        'Content-Type: application/json',
			        'token: '.API_TOKEN, 
			        'x-api-key:'.X_API_KEY
			    );
			    $request_data=(array)json_decode($row['request_data']);
			    if($row['request_type'] == 'entry'){
			    	$url=entry_URL;
			    	$request_data['vehicle_image']=$row['vehicle_image'];
			    	$request_data['driver_image']=$row['vehicle_image'];
			    }else{
			    	$url=exit_URL;
			    }

				curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($handle, CURLOPT_URL, $url);
				curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($handle, CURLOPT_POSTFIELDS,json_encode($request_data));
				sleep(1);
				$output = curl_exec($handle);
			      
				curl_close($handle);

				$data_output = json_decode($output);
				if(!empty($data_output) && $data_output->StatusCode == '200'){
					$update_data=array(
						'live_sync'=>1,
						'driver_image'=>'',
						'vehicle_image'=>'',
					);
					$this->common->update_record('id', $row['id'], 'car_plates', $update_data);
				}
			}
		}
	}
}

?>