<?php
function customer_email()
{
	$CI =& get_instance(); 
	if($CI->uri->segment(1)!="" && filter_var($CI->uri->segment(1), FILTER_VALIDATE_EMAIL) && LIVE) 
	{
	  return $CI->uri->segment(1)."/";	
	}
	else
	{
		return "";
	}
}
if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle) {
        return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}
function send_sms($id,$mobile_number,$download_driver_dec)
{
   
   if(SMS)
   {
        require(FCPATH . '/vendor/autoload.php');
            $client = new Twilio\Rest\Client(SMS_SID, SMS_AUTH_TOKEN);
                $message = $client->messages->create($mobile_number, // Text this number
                  array(
                    'from' => SMS_FROM,
                    'body' => "Driver Dec Completed.\n Driver Dec ID: #".$id."\n Download Driver Dec ".$download_driver_dec
                  )
                );
    }
}
function compressImage($source_img, $destination_img)
{
    $CI =& get_instance();  
    $quality=$CI->data['settings']->image_quality; 
    if (strpos($source_img, 'EXIT') !== false)
    {
        $CROP_IMAGE_LEFT=$CI->data['settings']->exit_crop_image_left;
        $CROP_IMAGE_RIGHT=$CI->data['settings']->exit_crop_image_right;
        $CROP_IMAGE_TOP=$CI->data['settings']->exit_crop_image_top;
        $CROP_IMAGE_BOTTOM=$CI->data['settings']->exit_crop_image_bottom;
    }
	else
	{
		$CROP_IMAGE_LEFT=$CI->data['settings']->entry_crop_image_left;
		$CROP_IMAGE_RIGHT=$CI->data['settings']->entry_crop_image_right;
		$CROP_IMAGE_TOP=$CI->data['settings']->entry_crop_image_top;
		$CROP_IMAGE_BOTTOM=$CI->data['settings']->entry_crop_image_bottom;
	}
    $SIZE = 0.5;
   	$original_image = @imagecreatefromjpeg($source_img);
  	 list($original_width, $original_height) =  getimagesize($source_img);
	
	$CROP_IMAGE_WIDTH =$original_width- $CROP_IMAGE_LEFT-$CROP_IMAGE_RIGHT;
	$CROP_IMAGE_HEIGHT =$original_height- $CROP_IMAGE_TOP-$CROP_IMAGE_BOTTOM;
	
	$CROP_NEW_IMAGE_WIDTH =$CROP_IMAGE_WIDTH*$SIZE;
	$CROP_NEW_IMAGE_HEIGHT =$CROP_IMAGE_HEIGHT*$SIZE;
	
	$new_image = imagecreatetruecolor($CROP_NEW_IMAGE_WIDTH,$CROP_NEW_IMAGE_HEIGHT );
	
    imagecopyresized($new_image,$original_image, 0, 0, $CROP_IMAGE_LEFT, $CROP_IMAGE_TOP,$CROP_NEW_IMAGE_WIDTH,$CROP_NEW_IMAGE_HEIGHT, $CROP_IMAGE_WIDTH, $CROP_IMAGE_HEIGHT);
    imagejpeg($new_image,$destination_img,$quality);
    imagedestroy($new_image);
}
function make_image($image)
{
    $image_parts    = explode(";base64,", $image);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type     = $image_type_aux[1];
    $image_base64   = base64_decode($image_parts[1]);
    $file           = UPLOAD_DIR . uniqid() . '.png';
    file_put_contents($file, $image_base64);
    return $file;
}
function base64_image($image,$signature_full_path)
{
	
    $image_parts    = explode(";base64,", $image);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type     = $image_type_aux[1];
    $image_base64   = base64_decode($image_parts[1]);
    $file           = $signature_full_path;
    file_put_contents($file, $image_base64);
    return $file;
}
function get_plate($rego, $image_path, $type,$manully_entry,$manully_exit,$exit=0,$cp_id=0)
{
    $ci =& get_instance();
    $file = 'sync/' . $image_path . '/' . $type . '/' . $rego . '.jpg';
	if(($type=='ENTRY' && $manully_entry==1) || ($type=='EXIT' && $manully_exit==1))
	{
		 $file_path = HTTP_ASSETS_PATH_ADMIN.MANUAL_IMAGE;
	}
	else if (file_exists($file) && is_file($file)) {
        //Plate Image
        $file_path = base_url($file) . "?n=" . rand(1, 1000);
    } else {
     
        $file_path = HTTP_ASSETS_PATH_ADMIN.DEFAULT_IMAGE;
    }
    
    //   $type = str_replace("ENTRY","",$type);
    
    $filethumb = 'sync/' . $image_path . '/' . $type . '/' . $rego . '.jpg';
    if(($type=='ENTRY' && $manully_entry==1) || ($type=='EXIT' && $manully_exit==1))
	{
		 $file_path_thumb = HTTP_ASSETS_PATH_ADMIN.MANUAL_THUMB_IMAGE;
	}
	else if (file_exists($filethumb) && is_file($filethumb)) {
        $file_path_thumb = base_url($filethumb) . "?=" . rand(1, 1000);
    } else {
        $file_path_thumb =  HTTP_ASSETS_PATH_ADMIN.DEFAULT_THUMB_IMAGE;
    }
 	$file_path_entry='"'.$file_path.'"';
    $btn_custome='';
	
	
    $show_entry_btn='';
    $show_exit_btn='';
    $entry_class='';
    $exit_class='';
    $entry_disabled='';
    $exit_disabled='';
    if($ci->uri->segment('2') == 'invalid_plates')
    {
        if($type == 'ENTRY')
        {
            $show_entry_btn=1;
            $entry_class='custom_active_btn';
            $exit_class='custom_deactive_btn';
            $entry_disabled='';
            $exit_disabled='disabled';
        }
        else
        {
            $show_exit_btn=1;
            $entry_class='custom_deactive_btn';
            $exit_class='custom_active_btn';
            $entry_disabled='disabled';
            $exit_disabled='';
        }
    }
    else if($ci->uri->segment('2') == 'unknown_vehicles')
    {
        $show_exit_btn=1;
        $entry_class='custom_deactive_btn';
        $exit_class='custom_active_btn';
        $entry_disabled='';
        $exit_disabled='disabled';
    }
    else
    {
        if($exit == 1)
        {
            $show_exit_btn=1;
        }
        $show_entry_btn=1;
        $entry_class='custom_active_btn';
        $exit_class='custom_deactive_btn';

    }
	
    if($show_entry_btn == 1)
    {
        $btn_custome = "<button type=button class=".$entry_class." custom_btn btn btn-info onClick=changeVehicleImage(this.id,".$file_path_entry."); data-type=entry id=custom_entry ".$entry_disabled.">".language_translate('entry')."</button>";    
    }
    
    if ($show_exit_btn == 1 ) 
	{
		
		 $file_exit = 'sync/' . $image_path . '/EXIT/' . $rego . '.jpg';
		if($manully_exit==1)
		{
			 $file_path_exit = HTTP_ASSETS_PATH_ADMIN.MANUAL_IMAGE;
		}
		else if (file_exists($file_exit) && is_file($file_exit)) {
			//Plate Image
			$file_path_exit = base_url($file_exit) . "?n=" . rand(1, 1000);
		} else {
		 
			$file_path_exit =  HTTP_ASSETS_PATH_ADMIN.DEFAULT_IMAGE;
		}
        $file_path_exit='"'.$file_path_exit.'"';
        $btn_custome = $btn_custome."  <button type=button class=".$exit_class." btn btn-info onClick=changeVehicleImage(this.id,".$file_path_exit."); data-type=exit id=custom_exit ".$exit_disabled.">".language_translate('exit')."</button>";
    }
    $custom_type='custom_type';
    $custom_plate_text='custom_plate';
    $modify_plate='';
    if($ci->uri->segment('2') == 'vehicles'){
        $rand_number=mt_rand(10000000,99999999);
        $custom_type='custom_type_btn';
        $custom_plate_text='custom_plate_text';        
        $modify_plate="<button type=button class=modify_plate_btn onClick=modify_plate(".$rand_number."); id=".$rand_number." data-id=".$cp_id." data-type=".$type." data-rego=".$rego.">".language_translate('modify_plate')."</button>";
    }
    //$this->data['settings']->website_logo;
    return "<center><a class='parentrego' href='" . $file_path . "' data-toggle='lightbox' data-title='".language_translate('vehicle_image')."' data-footer='<div class=".$custom_type.">
                        " . $btn_custome. " 
                    </div> <div class=".$custom_plate_text.">".$modify_plate." ".language_translate('license_plate')." $rego</div>' >

                    <img width='".$ci->data['admin_site_settings']->thumb_image_width."px;' src='$file_path_thumb'><div class='top-right top-right-custom' style=min-width:".($ci->data['admin_site_settings']->thumb_image_width-1)."px;>" . $rego . "</div></a></center>";
    
    
}


function kiosk_get_plate($rego, $image_path, $type,$manully_entry,$manully_exit,$exit=0,$cp_id=0)
{
    $ci =& get_instance();
    $file = 'sync/' . $image_path . '/' . $type . '/' . $rego . '.jpg';
	if(($type=='ENTRY' && $manully_entry==1) || ($type=='EXIT' && $manully_exit==1))
	{
		 $file_path = HTTP_ASSETS_PATH_ADMIN.MANUAL_IMAGE;
	}
	else if (file_exists($file) && is_file($file)) {
        //Plate Image
        $file_path = base_url($file) . "?n=" . rand(1, 1000);
    } else {
     
        $file_path = HTTP_ASSETS_PATH_ADMIN.DEFAULT_IMAGE;
    }
    
    //   $type = str_replace("ENTRY","",$type);
    
    $filethumb = 'sync/' . $image_path . '/' . $type . '/' . $rego . '.jpg';
    if(($type=='ENTRY' && $manully_entry==1) || ($type=='EXIT' && $manully_exit==1))
	{
		 $file_path_thumb = HTTP_ASSETS_PATH_ADMIN.MANUAL_THUMB_IMAGE;
	}
	else if (file_exists($filethumb) && is_file($filethumb)) {
        $file_path_thumb = base_url($filethumb) . "?=" . rand(1, 1000);
    } else {
        $file_path_thumb =  HTTP_ASSETS_PATH_ADMIN.DEFAULT_THUMB_IMAGE;
    }
 	$file_path_entry='"'.$file_path.'"';
    $btn_custome='';
	
	
    $show_entry_btn='';
    $show_exit_btn='';
    $entry_class='';
    $exit_class='';
    $entry_disabled='';
    $exit_disabled='';
    if($ci->uri->segment('2') == 'invalid_plates')
    {
        if($type == 'ENTRY')
        {
            $show_entry_btn=1;
            $entry_class='custom_active_btn';
            $exit_class='custom_deactive_btn';
            $entry_disabled='';
            $exit_disabled='disabled';
        }
        else
        {
            $show_exit_btn=1;
            $entry_class='custom_deactive_btn';
            $exit_class='custom_active_btn';
            $entry_disabled='disabled';
            $exit_disabled='';
        }
    }
    else if($ci->uri->segment('2') == 'unknown_vehicles')
    {
        $show_exit_btn=1;
        $entry_class='custom_deactive_btn';
        $exit_class='custom_active_btn';
        $entry_disabled='';
        $exit_disabled='disabled';
    }
    else
    {
        if($exit == 1)
        {
            $show_exit_btn=1;
        }
        $show_entry_btn=1;
        $entry_class='custom_active_btn';
        $exit_class='custom_deactive_btn';

    }
	
    if($show_entry_btn == 1)
    {
        $btn_custome = "<button type=button class=".$entry_class." custom_btn btn btn-info onClick=changeVehicleImage(this.id,".$file_path_entry."); data-type=entry id=custom_entry ".$entry_disabled.">".language_translate('entry')."</button>";    
    }
    
    if ($show_exit_btn == 1 ) 
	{
		
		 $file_exit = 'sync/' . $image_path . '/EXIT/' . $rego . '.jpg';
		if($manully_exit==1)
		{
			 $file_path_exit = HTTP_ASSETS_PATH_ADMIN.MANUAL_IMAGE;
		}
		else if (file_exists($file_exit) && is_file($file_exit)) {
			//Plate Image
			$file_path_exit = base_url($file_exit) . "?n=" . rand(1, 1000);
		} else {
		 
			$file_path_exit =  HTTP_ASSETS_PATH_ADMIN.DEFAULT_IMAGE;
		}
        $file_path_exit='"'.$file_path_exit.'"';
        $btn_custome = $btn_custome."  <button type=button class=".$exit_class." btn btn-info onClick=changeVehicleImage(this.id,".$file_path_exit."); data-type=exit id=custom_exit ".$exit_disabled.">".language_translate('exit')."</button>";
    }
    $custom_type='custom_type';
    $custom_plate_text='custom_plate';
    $modify_plate='';
    if($ci->uri->segment('2') == 'vehicles'){
        $rand_number=mt_rand(10000000,99999999);
        $custom_type='custom_type_btn';
        $custom_plate_text='custom_plate_text';        
        $modify_plate="<button type=button class=modify_plate_btn onClick=modify_plate(".$rand_number."); id=".$rand_number." data-id=".$cp_id." data-type=".$type." data-rego=".$rego.">".language_translate('modify_plate')."</button>";
    }
    //$this->data['settings']->website_logo;
    return "<center><a class='parentrego' href='" . $file_path . "' data-toggle='lightbox' data-title='".language_translate('vehicle_image')."' data-footer='<div class=".$custom_type.">
                        " . $btn_custome. " 
                    </div> <div class=".$custom_plate_text.">".$modify_plate." ".language_translate('license_plate')." $rego</div>' >

                    <img width='265px;' src='$file_path'><div class='top-right top-right-custom' style=min-width:265px;>" . $rego . "</div></a></center>";
    
    
}
function getUserIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
function is_connected()
{
    $connected = @fsockopen(SERVER_URL, 80);
    //website, port  (try 80 or 443)
    if ($connected) {
        $is_conn = true; //action when connected
        fclose($connected);
    } else {
        $is_conn = false; //action in connection failure
    }
    return $is_conn;
    
}




function create_formatted_date($site_entry_exit)
{
    
    
    $data_entry_exit = explode("#", $site_entry_exit);
    
    $from_time = strtotime($data_entry_exit[0]);
    if (count($data_entry_exit) == 2 && $data_entry_exit[1] != "" && $data_entry_exit[1] != "PARKED") {
        $to_time = strtotime($data_entry_exit[1]);
    } else {
        $to_time = strtotime(date('y-m-d h:i a'));
    }
    
    if (!empty($from_time)) {
        $start_date  = new DateTime(date('y-m-d h:i a', $from_time));
        $since_start = $start_date->diff(new DateTime(date('y-m-d h:i a', $to_time)));
        
        $formatted_date = "";
        if ($since_start->d != 0) {
            if ($since_start->d == 1) {
                $formatted_date = $formatted_date . $since_start->d . ' '.language_translate('day').', ';
            } else {
                $formatted_date = $formatted_date . $since_start->d . ' '.language_translate('days').', ';
            }
        }
        if ($since_start->h != 0) {
            if ($since_start->h == 1) {
                $formatted_date = $formatted_date . $since_start->h . ' '.language_translate('table_hour').', ';
            } else {
                $formatted_date = $formatted_date . $since_start->h . ' '.language_translate('table_hours').', ';
            }
        }
        if ($since_start->i == 1) {
            $formatted_date = $formatted_date . $since_start->i . ' '.language_translate('table_minute').' ';
        } else if ($since_start->i == 0) {
            $formatted_date = $formatted_date . '1 '.language_translate('table_minute').' ';
        } else {
            $formatted_date = $formatted_date . $since_start->i . ' '.language_translate('table_minutes').' ';
        }
    } else {
        $formatted_date = "-";
    }
    return $formatted_date;
}
function UR_exists($url)
{
    $headers = get_headers($url);
    return stripos($headers[0], "200 OK") ? true : false;
}

function create_formatted_minute($site_entry_exit)
{
    $data_entry_exit = explode("#", $site_entry_exit);
    
    $from_time = strtotime($data_entry_exit[0]);
    if (count($data_entry_exit) == 2 && $data_entry_exit[1] != "" && $data_entry_exit[1] != "PARKED") {
        $to_time = strtotime($data_entry_exit[1]);
    } else {
        $to_time = strtotime(date('y-m-d h:i a'));
    }
    
    $start_date  = new DateTime(date('y-m-d h:i:s a', $from_time));
    $end_date = new DateTime(date('y-m-d h:i:s a', $to_time));
    $since_start = date_diff($start_date, $end_date);
    $day       = $since_start->d * 24;
    $hour       = $day + $since_start->h;
    // $total       = $total + $since_start->i;
    // if ($total == 0 || $total == 1) {
    //     $total = '1 Minute';
    // } else {
    //     $total = (int) $total . ' Minutes';
    // }
    $total = $since_start->format('%r').($hour < 10 ? '0'.$hour : $hour).':'.($since_start->i < 10 ? '0'.$since_start->i : $since_start->i).':'.($since_start->s < 10 ? '0'.$since_start->s : $since_start->s);
    return $total;
    // return $since_start->d*24.".".$since_start->h.$since_start->i;
}
function get_format_date($date_time)
{
    if(!empty($date_time)){
    $ci =& get_instance();
    if($ci->data['admin_site_settings']->date_format == 0){
        return date("d.m.y", strtotime($date_time)).'<br>'.date("h:i:s a", strtotime($date_time));
    }else{
        return date("d.m.y", strtotime($date_time)).'<br>'.date("H:i:s", strtotime($date_time));
    }
    }else{
        return '-';
    }
}
function get_format_date_without_second($date_time)
{
    if(!empty($date_time)){
    $ci =& get_instance();
    if($ci->data['admin_site_settings']->date_format == 0){
        return date("d.m.y", strtotime($date_time)).' '.date("h:i a", strtotime($date_time));
    }else{
        return date("d.m.y", strtotime($date_time)).' '.date("H:i", strtotime($date_time));
    }
    }else{
        return '-';
    }
}
function get_time_format($date_time)
{
    if(!empty($date_time)){
    $ci =& get_instance();
    if($ci->data['admin_site_settings']->date_format == 0){
        return date("d.m.y", strtotime($date_time)).'<br>'.date("h:i:s a", strtotime($date_time));
    }else{
        return date("d.m.y", strtotime($date_time)).'<br>'.date("H:i:s", strtotime($date_time));
    }
    }else{
        return '-';
    }
}
function kiosk_get_time_format($date_time)
{
    if(!empty($date_time)){
    $ci =& get_instance();
    if($ci->data['admin_site_settings']->date_format == 0){
        return date("d.m.y", strtotime($date_time)).' '.date("h:i:s a", strtotime($date_time));
    }else{
        return date("d.m.y", strtotime($date_time)).' '.date("H:i:s", strtotime($date_time));
    }
    }else{
        return '-';
    }
}
function get_decision($val)
{
    if (!empty($val) && $val > 0) {
        return language_translate('yes');
    }
    else if($val < 0)
    {
        return '<p class="exit_custom">'.language_translate('na_capital').'</p>';
    } 
    else {
        return language_translate('no');
    }
}
function kiosk_get_decision($val)
{
    if (!empty($val) && $val > 0) {
        return language_translate('yes');
    }
    else if($val < 0)
    {
        return '<div class="exit_custom">'.language_translate('na_capital').'</div>';
    } 
    else {
        return language_translate('no');
    }
} 
function get_not_available()
{
   return '<div class="exit_custom">'.language_translate('not_available').'</div>';
}
function get_na()
{
   
        return '<div class="exit_custom">'.language_translate('na_capital').'</div>';
     
}
function get_empty_string($vals)
{

    if($vals==''){ return '';}
    $val=explode('#',$vals);
  /*  if($val[1] < 0)
    {
        return '<p class="exit_custom">'.language_translate('na_capital').'</p>';
    }    
    else if ($val[1] == 0) {
        return language_translate('no_drivers_dec');
    }else if($val[1] > 0 && (empty($val[0]) || $val[0]=="0") ){
        return '<p class="exit_custom">'.language_translate('na_capital').'</p>';
    } else {
        return $val[0];
    }*/

    if($val[1] > 0 && (empty($val[0]) || $val[0]=="0") ){
        return '<p class="exit_custom">'.language_translate('na_capital').'</p>';
    } else {
        return $val[0];
    }

}
function kiosk_get_empty_string($vals)
{
    $val=explode('#',$vals);
    if($val[1] < 0)
    {
        return '<div class="exit_custom">'.language_translate('na_capital').'</div>';
    }    
    else if ($val[1] == 0) {
        return language_translate('no_drivers_dec');
    }else if($val[1] > 0 && (empty($val[0]) || $val[0]=="0") ){
        return '<div class="exit_custom">'.language_translate('na_capital').'</div>';
    } else {
        return $val[0];
    }
}
function get_park_string($parked)
{
    if ($parked == 1) {
        return language_translate('yes');
    } else {
        return language_translate('no');
    }
}
function export_time($date_time)
{
    if(!empty($date_time)){
    $ci =& get_instance();
    if($ci->data['admin_site_settings']->date_format == 0){
        return date("h:i:s a", strtotime($date_time));
    }else{
        return date("H:i:s", strtotime($date_time));
    }
    }else{
        return '-';
    }
}
function no_type_exit($val)
{
    if(empty($val)){
        return language_translate('on_site');
    } else {
        return $val;
    }
}
function get_site_entry($site_entry_data)
{
    $parked=$site_entry_data[0];
    $site_entry=$site_entry_data[1];
    $exit=$site_entry_data[2];
    $reparked=$site_entry_data[3];
    /*if($exit == 0 && $parked==1)
    {
      return '<div class="parked_custom">'.language_translate('parked_capital').'</div>'; 
    }
    else*/ if($reparked==1)
    {
        return '<div class="parked_custom">'.language_translate('parked_capital').'</div>'; 
    }
    else
    {
      return get_format_date($site_entry); 
    }
}
function get_site_exit_detail($site_exit_detail)
{
    $site_exit_detail=explode('#',$site_exit_detail);
    $parked=$site_exit_detail[0];
    $site_entry=$site_exit_detail[1];  
    $exit=$site_exit_detail[2];  
    $site_exit=$site_exit_detail[3];
   if($parked==1)
    {
      return '<div class="parked_custom text-center">'.language_translate('parked_capital').'</div>'; 
      
    } 
	else
    {
      if(!$site_exit)
	  {
		  if($exit==0)
		  {
      	  	return no_type_exit($site_exit);
		  }
		  else
		  {
			  return language_translate('not_captured');
		  }
      }
      else if($site_exit && $site_exit != 'PARKED') 
      {
        return get_time_format($site_exit);
      } 
      else if($site_exit = 'PARKED') {
          return $site_exit;
      }  
    }
}
function kiosk_get_site_exit_detail($site_exit_detail)
{
    $site_exit_detail=explode('#',$site_exit_detail);
    $parked=$site_exit_detail[0];
    $site_entry=$site_exit_detail[1];  
    $exit=$site_exit_detail[2];  
    $site_exit=$site_exit_detail[3];
   if($parked==1)
    {
      return '<div class="parked_custom">'.language_translate('parked_capital').'</div>'; 
      
    } 
	else
    {
      if(!$site_exit)
	  {
		  if($exit==0)
		  {
      	  	return no_type_exit($site_exit);
		  }
		  else
		  {
			  return language_translate('not_captured');
		  }
      }
      else if($site_exit && $site_exit != 'PARKED') 
      {
        return kiosk_get_time_format($site_exit);
      } 
      else if($site_exit = 'PARKED') {
          return $site_exit;
      }  
    }
}
function get_time_on_site($site_entry_exit_detail){
                   
    $site_entry_exit_detail=explode('#',$site_entry_exit_detail);
	
    $parked=$site_entry_exit_detail[0];
	
    $site_entry=$site_entry_exit_detail[1];
    $exit=$site_entry_exit_detail[2];
    $site_exit=$site_entry_exit_detail[3];
    $reparked=$site_entry_exit_detail[4];
    $site_entry_exit=$site_entry.'#'.$site_exit;
     if(($parked==1 || $reparked==1) || ($exit==1 && $site_exit==''))
     {
       return '<div class="exit_custom text-center">'.language_translate('n_a').'</div>'; 
     }
     else
     {
        return create_formatted_date($site_entry_exit);
     }
}
function kiosk_get_time_on_site($site_entry_exit_detail){
                   
    $site_entry_exit_detail=explode('#',$site_entry_exit_detail);
	
    $parked=$site_entry_exit_detail[0];
	
    $site_entry=$site_entry_exit_detail[1];
    $exit=$site_entry_exit_detail[2];
    $site_exit=$site_entry_exit_detail[3];
    $reparked=$site_entry_exit_detail[4];
    $site_entry_exit=$site_entry.'#'.$site_exit;
     if(($parked==1 || $reparked==1) || ($exit==1 && $site_exit==''))
     {
       return '<div class="exit_custom">'.language_translate('n_a').'</div>'; 
     }
     else
     {
        return create_formatted_date($site_entry_exit);
     }
}
function excel_decision($cells,$objPHPExcel)
{
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cells,language_translate('n_a'));
    $styleArray = array(
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => 'FF0000'),
            'name' => 'Cambria',
            'size'  => 11,
        ));
    $objPHPExcel->getActiveSheet()->getStyle($cells)->applyFromArray($styleArray);
}
function excel_time_on_site($cells,$objPHPExcel)
{
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cells,language_translate('n_a'));
    $styleArray = array(
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => 'FF0000'),
            'name' => 'Cambria',
            'size'  => 11,
        ));
    $objPHPExcel->getActiveSheet()->getStyle($cells)->applyFromArray($styleArray);
}
function excel_parked($cells,$objPHPExcel)
{
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cells,language_translate('parked_capital'));
                 
    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFEB9C');
    $styleArray = array(
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => 'b67200'),
            'name' => 'Cambria',
            'size'  => 11,
        ));
    $objPHPExcel->getActiveSheet()->getStyle($cells)->applyFromArray($styleArray);
}
function site_settings_history_change($cells,$value,$objPHPExcel)
{
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cells,$value);
    $styleArray = array(
        'font'  => array(
            'color' => array('rgb' => 'FF0000'),
            'name' => 'Cambria',
            'size'  => 11,
        ));
    $objPHPExcel->getActiveSheet()->getStyle($cells)->applyFromArray($styleArray);
}
function guidv4()
{
    if (function_exists('com_create_guid') === true)
    return trim(com_create_guid(), '{}');

    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}


function language_translate($key)
{
    $CI =& get_instance();
    $data=$CI->lang->line($key);
    if(language_key == 1){ 
       $data= $key; 
    }
    return $data;
}

function get_format_zip($zip){
    if ($zip != "") {
                return "
            <div class='text-center'><a href='" . base_url() . $zip . "' download><img src='" . DEFAULT_ZIP . "' style='width: 25px'></a></div>";
            }
}
function get_format_sql($sql){
    if ($sql != "") {
                return "
            <div class='text-center'><a href='" . base_url() . $sql . "' download><img src='" . DEFAULT_SQL . "' style='width: 25px'></a></div>";
            }
}
function download_excel($objPHPExcel,$filename)
{
		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();
		ob_start();
        $objWriter->save('php://output');
		exit();
}
//upload local ho live
function send_to_ftp($source,$destination = "n/",$host_camera_flag) 
{
    if($host_camera_flag==0)
    {
        $ftp_server=FTP_HOST_NAME;//ftp host
        $ftp_username=FTP_USERNAME;//ftp username
        $ftp_password=FTP_PASSWORD;//ftp password
		
		if(file_exists ($source))
		{
			$ftp_conn = ftp_connect($ftp_server,FTP_PORT);
			if (!$ftp_conn) return false;
			$login = ftp_login($ftp_conn, $ftp_username, $ftp_password);
			ftp_pasv($ftp_conn, true);
			$ftp_put=ftp_put($ftp_conn, $destination, $source, FTP_BINARY);
            ftp_close($ftp_conn);
			return $ftp_put;
		}
		
    }
    else
    {
		if($host_camera_flag == 1)
		{
            if(FTP_UPLOAD_ENABLE)
            {
                $ftp_server=FTP_CAMERA_HOST_NAME;//ftp host
                $ftp_username=FTP_CAMERA_USERNAME;//ftp username
                $ftp_password=FTP_CAMERA_PASSWORD;//ftp password
				
				
				$new_name =	str_replace("VEHICLE_DETECTION_X.jpg","VEHICLE_DETECTION.jpg",$destination);
              //  $ext = pathinfo($destination, PATHINFO_EXTENSION);                
               // $fname=str_replace("_X","",pathinfo($destination, PATHINFO_FILENAME));
            //    $new_name = $fname.'.'.$ext;

                $destination=FTP_PATH.$destination;//ftp password
                if(file_exists ($source))
                {
                    $ftp_conn = ftp_connect($ftp_server,FTP_CAMERA_PORT);
                    if (!$ftp_conn) return false;
                    $login = ftp_login($ftp_conn, $ftp_username, $ftp_password);
                    ftp_pasv($ftp_conn, true);

                    $ftp_put=ftp_put($ftp_conn, $destination, $source, FTP_BINARY);
                    sleep(10);
                    ftp_rename( $ftp_conn,$destination, FTP_PATH.$new_name);
            
					sleep(10);
                    ftp_close($ftp_conn);
                    return $ftp_put;
                }    
            }
            else
            {
                return true;
            }
			
		}
		if($host_camera_flag == 2)
		{
            if(FTP_UPLOAD_ENABLE_2)
            {
    			$ftp_server=FTP_CAMERA_HOST_NAME_2;//ftp host
    			$ftp_username=FTP_CAMERA_USERNAME_2;//ftp username
    			$ftp_password=FTP_CAMERA_PASSWORD_2;//ftp password
                
              //  $ext = pathinfo($destination, PATHINFO_EXTENSION);                
              //  $fname=str_replace("_X","",pathinfo($destination, PATHINFO_FILENAME));
              //  $new_name = $fname.'.'.$ext;
    			$new_name =	str_replace("VEHICLE_DETECTION_X.jpg","VEHICLE_DETECTION.jpg",$destination);
                $destination=FTP_PATH_2.$destination;//ftp password
    			
    			if(file_exists ($source))
    			{
    				$ftp_conn = ftp_connect($ftp_server,FTP_CAMERA_PORT_2);                
    				if (!$ftp_conn) return false;
    				$login = ftp_login($ftp_conn, $ftp_username, $ftp_password);
                    ftp_pasv($ftp_conn, true);
    				$ftp_put=ftp_put($ftp_conn, $destination, $source, FTP_BINARY);
                    sleep(10);
                    ftp_rename( $ftp_conn,$destination, FTP_PATH_2.$new_name);
					
                    sleep(10);
                    ftp_close($ftp_conn);
                    return $ftp_put;
    			}
            }
            else
            {
                 return true;
            }
		}
		if($host_camera_flag == 3)
		{
            if(FTP_UPLOAD_ENABLE_3)
            {
                $ftp_server=FTP_CAMERA_HOST_NAME_3;//ftp host
                $ftp_username=FTP_CAMERA_USERNAME_3;//ftp username
                $ftp_password=FTP_CAMERA_PASSWORD_3;//ftp password

              //  $ext = pathinfo($destination, PATHINFO_EXTENSION);                
              //  $fname=str_replace("_X","",pathinfo($destination, PATHINFO_FILENAME));
                //   $new_name = $fname.'.'.$ext;
				$new_name =	str_replace("VEHICLE_DETECTION_X.jpg","VEHICLE_DETECTION.jpg",$destination);
                $destination=FTP_PATH_3.$destination;//ftp password
                
                if(file_exists ($source))
                {
                    $ftp_conn = ftp_connect($ftp_server,FTP_CAMERA_PORT_3);
                    if (!$ftp_conn) return false;
                    $login = ftp_login($ftp_conn, $ftp_username, $ftp_password);
                    ftp_pasv($ftp_conn, true);
                    $ftp_put=ftp_put($ftp_conn, $destination, $source, FTP_BINARY);
					sleep(10);
                    ftp_rename( $ftp_conn,$destination, FTP_PATH_3.$new_name);

					sleep(10);
                    ftp_close($ftp_conn);
                    return $ftp_put;
                }
            }
            else
            {
                return true;
            }
		}
		
    }
    
  
	return false;
    
}

function db_backup_send_to_ftp($source,$destination) 
{
    $ftp_server=FTP_HOST_NAME;//ftp host
    $ftp_username=FTP_USERNAME;//ftp username
    $ftp_password=FTP_PASSWORD;//ftp password
    
    if(file_exists ($source))
    {
        $ftp_conn = ftp_connect($ftp_server,FTP_PORT);
        if (!$ftp_conn) return false;
        $login = ftp_login($ftp_conn, $ftp_username, $ftp_password);
        ftp_pasv($ftp_conn, true);
        $ftp_put=ftp_put($ftp_conn, $destination, $source, FTP_BINARY);
        ftp_close($ftp_conn);
        return $ftp_put;
    }
    
}
function get_services($is_flag)
{
    $service_data=array('CronAutoPark','CronDeleteImages','CronDriverDescInComplete','CronReport','CronUploadCameraImage','CronUploadLogs','DownloadImages','LocalHeartbeatCheck','SyncAppImages','SyncData','SyncV2');
    if($is_flag == 1)
    {
        array_push($service_data,"CronServiceStatus");
    }
    else  if($is_flag == 0)
    {
        array_push($service_data,"CronServiceStatus","MysqlnoXx");
    }
    return  $service_data;
}
function get_live_services()
{
    $service_data=array('LiveCronReport','LiveCronDriverDescInComplete','LiveCronAutoPark','LiveSyncV2');
    return  $service_data;
}
function db_config($host_name,$host_username,$host_password,$db_name){

        $config['hostname'] = $host_name;
        $config['username'] = $host_username;
        $config['password'] = $host_password;
        $config['database'] = $db_name;
        $config['dbdriver'] = "mysqli";
        $config['dbprefix'] = "";
        $config['pconnect'] = FALSE;
        $config['db_debug'] = TRUE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = "";
        $config['char_set'] = "utf8";
        $config['dbcollat'] = "utf8_general_ci";
        return  $config;
}
function create_site_setting_json($response){
    //creates the file
    $fp = fopen('uploads/SiteSetting/SiteSetting.json', 'w');
    fwrite($fp, json_encode($response));
    fclose($fp);
}
function get_site_setting_json(){
    //get the file
    $maintenance_mode_text='';
    if(file_exists('uploads/SiteSetting/SiteSetting.json')){
      $get_json_data = file_get_contents(base_url().'uploads/SiteSetting/SiteSetting.json');

      if(!empty($get_json_data)){
         $json_data = json_decode($get_json_data, true);
     	if($json_data['maintenance_mode'] == '1'){
          $maintenance_mode_text=$json_data['maintenance_mode_text'];  
         
       }
        
      }
    }
    return $maintenance_mode_text;
}
function get_datetime_format($date){
    if($date=="0000-00-00 00:00:00")
    {
        return "-";
    }
    else
    {
        if(!empty($date)){
           return date("d.m.y H:i", strtotime($date));
        }else{
            return "-";
        }
    }
}
function get_date_format($date){
    if($date=="0000-00-00")
    {
        return '';
    }
    else
    {
        return "<div class='text-center'>".date("d.m.y", strtotime($date))."</div>";
    }
}
function get_login($admin_email)
{
   return "<div class='textcenter'>&nbsp;<a class='btn btn-danger btn-sm m-btn m-btn--pill' href='".base_url().ADMIN_PATH."home/auto-login/".$admin_email."' title='".language_translate('login')."'><i class='la la-3x la-sign-in'></i></a></div>";
} 
function authentication_on_off($custom_data) {
    $authentication_on_off = explode('#', $custom_data);
    $checked=($authentication_on_off[0] == '1') ? "checked" : '';
    $val=($authentication_on_off[0] == '1') ? "0" : '1';
    $title=($authentication_on_off[0] == '1') ? language_translate('click_here_to_two_factor_authentication_off') : language_translate('click_here_to_two_factor_authentication_on');
    return '<label class="switch" title="'.$title.'">
                  <input type="checkbox" id="two_factor_authentication" name="two_factor_authentication" value="1" '.$checked.' onclick="update_authentication('.$val.',' . $authentication_on_off[1] . ');"><div class="slider round">
                    <span class="custom-on">'.language_translate('on').'</span>
                    <span class="custom-off" >'.language_translate('off').'</span>
                  </div>
                </label>';
}


function client_connection($email)
{
    $CI =& get_instance();
    $CI->masterdb = $CI->load->database('master_db', TRUE);
    $CI->masterdb->select('host_name,host_username,host_password,db_name');
    $CI->masterdb->where('admin_email',$email);
    $CI->masterdb->where('user_type','SA');
    $result = $CI->masterdb->get('admin');
    $admin_user=$result->row();
    return $db2= $CI->load->database(db_config($admin_user->host_name,$admin_user->host_username,$admin_user->host_password,$admin_user->db_name), TRUE);
}

function insert_data_to_dock($data,$secu_admin_id)
{
        $url = ADHERENCE_REPORT_URL."api/Adherence_report/update_dock_data";
        $ch = curl_init($url);
        
        $data = array('load_number' => $data->load_number, 'site_entry' => $data->site_entry, 'secu_admin_id' =>$secu_admin_id);
        // 
        $data_json = json_encode($data);
        
}
function check_block_status($type,$admin_email)
{          
        if ($admin_email != '') {
            $CI =& get_instance();
            $CI->masterdb = $CI->load->database('master_db', TRUE);
            $CI->masterdb->select('admin.*,company.is_active as company_status');
            $CI->masterdb->join('company', 'company.id = admin.company_id','left');
            $CI->masterdb->where('admin.admin_email',str_replace("/","",$admin_email));
            $CI->masterdb->where('admin.is_active','1');
            $result = $CI->masterdb->get('admin');
            $admin_user=$result->row();

            if ($admin_user != '') {
                $api_token = $admin_user->cp_token;
                $x_api_key = $admin_user->cp_x_api_key;
            } else {
                $api_token = SECURITY_TOKEN;
                $x_api_key = SECURITY_X_API_KEY;
            } 
        } else {
            $api_token = SECURITY_TOKEN;
            $x_api_key = SECURITY_X_API_KEY;
        }
        

        $url = SECURITY_URL.'api/auth/ckeck_block_status';
        $ch = curl_init($url);
        $ip = getUserIpAddr();
        
        $data = array('ip' => $ip);
        $data_json = json_encode($data);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type:application/json',
                    'token: '.$api_token.'',
                    'x-api-key: '.$x_api_key.'',
                    'type:'.$type.''
                ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
        die;
}
function visium_approve($cie_id,$immediate,$pre_approved,$gate_name='')
{          
        
        $url = VISIUM_API_URL.'approve/'.$cie_id;
        $ch = curl_init($url);
    
        $data_request = array('exit_lane'=>$gate_name,'siteCode' => VISIUM_API_SITECODE,"immediate"=>$immediate,"pre-approved"=>$pre_approved,"key"=>VISIUM_API_KEY);
    
        $json_request = json_encode($data_request);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS,$json_request);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type:application/json'
                ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
        die;
}

function check_auth($email,$api_token,$x_api_key,$status)
{

        if ($status == 'status') {
          $url = SECURITY_URL.'api/auth/check_status';
        } else {
          $url = SECURITY_URL.'api/auth/check_auth';
        }

        if ($api_token != '' && $x_api_key != '') {
           $token = $api_token;
           $x_api_key = $x_api_key;
           $project_name = '';
        } else {
            $token = SECURITY_TOKEN;
            $x_api_key = SECURITY_X_API_KEY;
            $project_name = '';
        }

        $ch = curl_init($url);
        $ip = getUserIpAddr();
        
        $data = array('ip' => $ip, 'email' => $email);
        $data_json = json_encode($data);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type:application/json',
                    'token: '.$token.'',
                    'x-api-key: '.$x_api_key.'',
                    'project_name: '.$project_name.''

                ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
        die;
}

function custom_where_in($pthis_db,$car_plates_id)
{
           
            $pthis_db->group_start();
            $car_plates_ids_chunk = array_chunk($car_plates_id,25);
            foreach($car_plates_ids_chunk as $car_plates_ids)
            {
                $pthis_db->or_where_in('car_plates_id', $car_plates_ids);
            }
            $pthis_db->group_end();
}



function update_login_status($email,$api_token,$x_api_key)
{
        $url = SECURITY_URL.'api/auth/update_login_status';
        $ch = curl_init($url);
        $ip = getUserIpAddr();

        if ($api_token != '' && $x_api_key != '') {
            $token = $api_token;
            $x_api_key = $x_api_key;
            $project_name = '';
         } else {
             $token = SECURITY_TOKEN;
             $x_api_key = SECURITY_X_API_KEY;
             $project_name = '';
         }
        
        $data = array('ip' => $ip, 'email' => $email);
        $data_json = json_encode($data);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type:application/json',
                    'token: '.$token.'',
                    'x-api-key: '.$x_api_key.'',
                    'project_name: '.$project_name.''
                ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
        die;
}
function save_base64_image($img,$path)
{

  if(!empty($img))
  {
        $image_parts = explode(";base64,", $img);

        $image_type_aux = explode("image/", $image_parts[0]);

        $image_type = $image_type_aux[1];

        $image_base64 = base64_decode($image_parts[1]);

      
        file_put_contents($path, $image_base64);
    }

}
function save_image($img,$path)
{
    if(!empty($img))
    {
         move_uploaded_file($img,$path);
    }
}
function na($string)
{
    if(empty($string))
    {
        return language_translate('na_capital');
    }
    else
    {
            return $string;
    }
}
?>