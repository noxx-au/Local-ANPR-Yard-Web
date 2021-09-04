<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class CronServiceStatus extends CI_Controller {

    public function __construct() 
	{
        parent::__construct();
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
		$status = shell_exec('systemctl is-failed CronLiveSync.service');
		if(trim($status)){
			shell_exec('systemctl restart CronLiveSync.service');	
		}		
	}
	

}
?>