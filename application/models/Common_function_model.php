<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Common_function_model extends CI_Model {

    private $_table;

    public function __construct() {
        parent::__construct();
        $this->load->model('Email_template_model','email_template');
    }
   function get_records($table, $where, $condition)
    {
        $this->db->from($table);
        $this->db->where_in($where, $condition);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_record($table, $where, $condition)
    {
        $this->db->from($table);
        $this->db->where_in($where, $condition);
        $query = $this->db->get();
        return $query->row();
    }
    function GetValue($table, $field, $where, $condition) { //Get field value in the database//
        $this->db->select($field);
        $this->db->where($where, $condition);
        $querycat = $this->db->get($table);
        foreach ($querycat->result() as $row) {
            return $row->$field;
        }
    }
   // this is to get filed value in database
    function CountByTable($table, $where) {

        $qry = 'SELECT * FROM `' . $table . '` ' . $where . '';
        $query = $this->db->query($qry);
        return $query->num_rows();
    }
     function get_data_count($table)
    {
      
		 if($table == 'car_plates')
        {
            $this->db->from('car_plates');
			$this->db->where('site_entry IS NOT NULL');
			$this->db->where("invalid_plate",0);
          //  $this->db->join('driver_dec', 'driver_dec.id = car_plates.cp_id','left');
            $this->db->where("car_plates.is_deleted=0 and car_plates.is_black_white_list=0");
            $this->db->where('date_format(car_plates.site_entry,"%Y-%m-%d") =','CURDATE()', FALSE);
            return $this->db->count_all_results(); 
            
        }
		else if($table == 'car_plates_vehicles')
        {
		    $this->db->from('car_plates');
		    $this->db->where('parked',0);
			$this->db->where('exit',0);
			$this->db->where('is_deleted',0);
			$this->db->where('invalid_plate',0);
			$this->db->where('site_exit', NULL);
			$this->db->where('site_entry IS NOT NULL');
			 $this->db->where('is_black_white_list', 0);
			$this->db->group_start();
			$this->db->where('date_format(site_entry,"%Y-%m-%d") =','CURDATE()', FALSE);
			$this->db->or_where('date_format(site_entry,"%Y-%m-%d") =','subdate(CURDATE(), 1)', FALSE);
			$this->db ->group_end(); 
		
            return $this->db->count_all_results();
         
        }
        else 
        {
          return  $this->db->from($table)->where('is_deleted',0)->where('date_format(created_date,"%Y-%m-%d") =','CURDATE()', FALSE)->count_all_results();	
        }
    	
    }


    function sql_detail() {
        $sql_details = array(
            'user' => $this->db->username,
            'pass' => $this->db->password,
            'db' => $this->db->database,
            'host' => $this->db->hostname
        );
        return $sql_details;
    }
    function master_db_detail() {
        $sql_details = array(
            'user' => USERNAME_MAIN,
            'pass' => PASSWORD_MAIN,
            'db' => DATABASE_MAIN,
            'host' => HOSTNAME_MAIN
        );
        return $sql_details;
    }

    function random_string($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }


    function insert_record($tblName, $data) {  // this is to insert record in database  
        $query = $this->db->insert($tblName, $data);
        return $this->db->insert_id();
    }
    function delete_record($fieldName,$id,$tblName) { 
        $this->db->where($fieldName,$id);
        if ($this->db->delete($tblName)) {
            return true;
        } else {
            return false;
        }
    }
  function insert_batch_record($tblName, $data,$db2=null) {  // this is to insert record in database
        if($db2 !=null){
            $this->db=$db2;
        }  
        $query = $this->db->insert_batch($tblName, $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    function update_record($fieldName, $id, $tblName, $data) {  // this is to Update record in database  
        $this->db->where($fieldName, $id);
        $this->db->update($tblName, $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    function get_row_multiple_where($table, $multiple_where = array() , $field = "")
    {
        if (!empty($field))
        {
            $this->db->select($field);
        }
        $this->db->where($multiple_where);
        $query = $this->db->get($table);
        return $query->row_array();
    }

	
    function array_column($array, $column_name) {

        return array_map(function($element) use($column_name) {
            return $element[$column_name];
        }, $array);
    }

    function get_site_setting_value() {
        $result = $this->db->get('site_settings');
        return $result->row();
    }

  function empty_table($table) {
        $this->db->truncate($table);
    }

    function get_image_extension($string) {
        $parts = explode('.', $string);
        $last = array_pop($parts);
        return $last;
    }

    function removeFromString($str, $item) {
        $parts = explode(',', $str);

        while (($i = array_search($item, $parts)) !== false) {
            unset($parts[$i]);
        }

        return implode(',', $parts);
    }

  	function delete_data($field,$id,$table)
	{
            if($table=='admin'){
                    $this->db->where_in($field, $id);
                    return $this->db->delete($table);
            }else{
			if($table=='car_plates')
			{
				$this->db->set('is_image_deleted', 1);	
			}
			$this->db->set('is_deleted', true);
			$this->db->set('is_sync', false);
			$this->db->set('updated_date', date("Y-m-d H:i:s"));
			$this->db->where_in($field,$id);
			return $this->db->update($table);
            }
	}
	// this is only for sync download / upload
	function update_data($field,$id,$table,$data,$is_sync=false)
	{
	
		
			foreach($data as $key => $value)
			{
				if($key!=$field)
				{
					if($key=='site_exit' && $value=='')
					{
						$value=NULL;
					}
					if($key!='car_plates_id')
					{
						$this->db->set($key, $value);
					}
				}
			}
		
			$this->db->set("is_sync",TRUE);
			
			if($is_sync)
			{
				$this->db->where('updated_date <= ',$data['updated_date']);
			}
			else
			{
				$this->db->where('updated_date <= ',$data->updated_date);	
			}
			$this->db->where($field,$id);
		
			return $this->db->update($table);
	}
	function insert_data($tblName,$data,$is_sync=false)
	{
		if($is_sync)
		{
			$data->is_sync=$is_sync;
		}
		if($tblName=='car_plates')
		{
			 unset($data->car_plates_id);
		}
   		$this->db->insert($tblName, $data);
	}
    //get db detail
    function get_db_detail($email)
    {
        $this->db->select('host_name,host_username,host_password,db_name');
        $this->db->from('admin');
        $this->db->where('admin_email',$email);
        $qry=$this->db->get();
        $result_data=$qry->row();//get email to db detail
        return $result_data;
    }
   /* function get_black_white_list_vehicle()
	{
		$this->db->where('is_deleted',0);
		$query_black_white_list_vehicle=$this->db->get('black_white_list_vehicle');
		$result_black_white_list_vehicle=$query_black_white_list_vehicle->result_array();
		return array_column($result_black_white_list_vehicle, 'rego');
	}*/
    function speeding_limit_mail($id,$settings,$db2=null)
    {
        if($db2 !=null){
            $this->db=$db2;
        }
        $speed_limit=$settings->speeding_limit;
        $site_logo=$settings->website_logo;
        $to_email=$settings->admin_mailing_address;
        if(!empty($settings->email_vehicle_speeding))
        {
            $to_email=$to_email.','.$settings->email_vehicle_speeding;
        }
        
        
        $this->db->select('cp_id,rego,site_entry,site_exit');
        $this->db->where('is_black_white_list', 0);
        $this->db->where('is_deleted',0);
        $this->db->where('exit',1);
        $this->db->where('cp_id',$id);
        $query = $this->db->get('car_plates');
        $row=$query->row();
        if(!empty($row))
        {
            $html_template = $this->email_template->get_email_template_by_id('6',$this->db);
                $rego=$row->rego;
                $site_entry=$row->site_entry;
                $site_exit=$row->site_exit;
                $date = date("F j, Y H:i");
                $diff_time=round(abs(strtotime($site_exit) - strtotime($site_entry)) / 60,2);  
                if($diff_time <= $speed_limit && $diff_time > 0)
                {
                    $datetime1 = new DateTime($site_entry);
                    $datetime2 = new DateTime($site_exit);
                    $interval = $datetime1->diff($datetime2);
                    $minutes=$interval->format('%i');
                    $seconds=$interval->format('%s');
                    $speeding_time=$minutes.' minutes';
                    if($seconds > 0)
                    {
                        $speeding_time=$minutes.' minutes '.$seconds.' seconds';
                    }
                    if($html_template->send_email)
                    {
                        $message = $html_template->email_template_description;
                        $message = str_replace('%date%', $date, $message);
                        $message = str_replace('%site_entry%', $site_entry, $message);
                        $message = str_replace('%site_exit%', $site_exit, $message);
                        $message = str_replace('%speeding_limit%', $speeding_time, $message);
                        $message = str_replace('%rego%', $rego, $message);
                        $message = str_replace('%site_url%', BASE_URL, $message);
                        $message = str_replace('%site_logo%',$site_logo,$message);
                        $message = str_replace('%copyright%',(COPYRIGHT),$message);

                        $email_data['subject']=str_replace('%rego%',$rego,$html_template->email_template_subject);
                        $email_data['message']=$message;
                        $email_data['from']=FROM_MAIL_ADDRESS;
                        $email_data['to']=$to_email;
                        $email_data['bcc']=DEBUG_EMAIL;
                        $email_data['signature']='';
                        $email_data['updated_date']=date("Y-m-d H:i:s");
    					
    					$notification['rego']=$rego;
    					$notification['site_entry']=$site_entry;
    					$notification['site_exit']=$site_exit;
    					$notification['date_time']=date("Y-m-d H:i:s");
    					$notification['cp_id']=$id;
    				
                        if(LIVE)
                        {

                            $masterdb = $this->load->database('master_db', TRUE);
                            $masterdb->insert("email_data",$email_data);
                            $this->insert_notification($notification,'VehicleDetectedOverSpeeding',$db2);
                        }
                        else
                        {
                            $this->db->insert("email_data",$email_data);
                            $this->insert_notification($notification,'VehicleDetectedOverSpeeding');
                        }
                    }
                    $notification['rego']=$rego;
                    $notification['site_entry']=$site_entry;
                    $notification['site_exit']=$site_exit;
                    $notification['date_time']=date("Y-m-d H:i:s");
                    $notification['cp_id']=$id;
                
                    if(LIVE)
                    {
                        $this->insert_notification($notification,'VehicleDetectedOverSpeeding',$db2);
                    }
                    else
                    {
                        $this->insert_notification($notification,'VehicleDetectedOverSpeeding');
                    }
                    // $this->common->insert_data("email_data",$email_data);
                }
            
        }
    }
    function update_driver_dec($rego,$site_entry_time,$site_settings,$db2=null)
    {
        if($db2 !=null){
            $this->db=$db2;
        }
        $this->db->group_start();
        $this->db->where('drivers_dec <=',0);
        $this->db->or_where('drivers_dec',null);
        $this->db ->group_end(); 
        $this->db->where('is_black_white_list',0);
        $this->db->where('site_exit IS NOT NULL');
        $this->db->where('exit',1);
        $this->db->where('is_deleted',0);
        $this->db->where('invalid_plate',0);
        $this->db->where('rego',$rego);
        $this->db->order_by('site_exit','DESC');
		//here we have to write for more then one records for the site_exit 
		$this->db->order_by('car_plates_id','DESC');
        $this->db->limit(1);
        $query_car_plate=$this->db->get('car_plates');
        $row_car_plate=$query_car_plate->row();
        
        if(!empty($row_car_plate))
        {   
            $driver_declaration_not_required=$site_settings->driver_declaration_not_required;
            $site_exit_time=$row_car_plate->site_exit;
            $diff_time=round(abs(strtotime($site_entry_time) - strtotime($site_exit_time)) / 60,2); 
            if($diff_time <= $driver_declaration_not_required)
            {
                $car_plate_data = array('drivers_dec'=> -1,'updated_date'=>date("Y-m-d H:i:s"),'is_sync'=>false);
                $this->db->where('cp_id', $row_car_plate->cp_id);
                $this->db->update('car_plates', $car_plate_data);
            } 
        }
    }
    function insert_notification($notification,$type,$db2=null){
        if($db2 !=null){
            $this->db=$db2;
        }
        $data=array('notification_id'=>guidv4(),'notification_title'=>serialize($notification),'notification_type'=>$type,'updated_date' => date("Y-m-d H:i:s"));
        $this->db->insert('notification', $data);
    }
}

?>