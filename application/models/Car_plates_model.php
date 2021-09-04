<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Car_plates_model extends CI_Model {

    public function __construct() {
        parent::__construct();  
       
    }
    public function update_car_plates_exit($data_car_plates)
	{	
		$site_exit= $data_car_plates['site_exit'];
		$cie_id= $data_car_plates['cie_id'];			
			
		$this->db->where('cie_id',$cie_id);
		$this->db->limit(1);
		$query=$this->db->get('car_plates');
		$row =$query->row();
	
		if (!empty($row)) 
		{
			if($row->parked==1 && $row->drivers_dec==0)
			{
				$row->drivers_dec=-1;
			}
			$data = array('drivers_dec'=>$row->drivers_dec,'exit'=>1,'site_exit' =>$site_exit ,'updated_date' => date("Y-m-d H:i:s"),'is_sync'=>false);
			$this->db->where('cp_id', $row->cp_id);
			$this->db->update('car_plates', $data);
			if($row->parked==1)
			{
				$data = array('trailer_rego'=> $row->trailer_rego,'container_no'=> $row->container_no,'axel_group_weight'=>$row->axel_group_weight,'total_weight'=>$row->total_weight,'cp_id'=>guidv4(),'exit'=>1,'parked'=>0,'reparked'=>1,'rego' =>$row->rego,'plate_c'=> $row->plate_c,'site_entry' => date('Y-m-d H:i:s'),'site_exit' => $site_exit,'updated_date' => date("Y-m-d H:i:s"),'manully_exit'=>1,'cie_id'=>$row->cie_id,
					'rapid_id'=>$row->rapid_id,
					'order_number'=>$row->order_number,
					'company_name'=>$row->company_name,
					'driver_name'=>$row->driver_name,
					'driver_mobile_no'=>$row->driver_mobile_no,'vehicle_type'=>$row->vehicle_type);
				$this->db->insert('car_plates', $data);
				$car_plates_id = $this->db->insert_id();
			}
		} 
		else 
		{
			return false;
		}	
		
		return true;
	}
}
