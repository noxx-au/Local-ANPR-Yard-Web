<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Email_template_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		
	}

	function get_datatables()
	{
			$result = $this->db->get('site_settings');
			$row = $result->row();

			

			$table = 'email_template';
			if ($row->induction_form_is_show != 1) {
				$custom_where = "email_template_id != 2";
			} else {
				$custom_where = '';
			}
			
			// Table's primary key
			$primaryKey = 'email_template_id';
		
						$columns = array(array( 'customfilter' => 'email_template_title','db' => 'email_template_title',  'dt' => 0 ),
						 array('customfilter' => 'email_template_id','db' => 'email_template_id', 'dt'  => 1,
						'formatter' => function( $demail_id, $row ) {
							return get_edit($demail_id);
						}));

				function get_edit($demail_template_id)
				{
					return "<div class='text-center'><a class='btn btn-success btn-sm m-btn m-btn--pill' href='".base_url().ADMIN_PATH."email_template/edit?id=".$demail_template_id."' title='".language_translate('edit')."'><i class='la la-pencil'></i></a></div>";
				}
	
            return json_encode(
                    Datatables_ssp::simple($_GET,$this->common->sql_detail(), $table, $primaryKey, $columns,'',$custom_where)
            );
	}
	// this is to insert delete in database created end
	function get_email_template_by_id($id,$db2=null)
	{	
		if($db2 !=null){
			$this->db=$db2;
		}
		$this->db->where('email_template_id',$id);
		$result = $this->db->get('email_template')->row();
		return $result;
	}
	//client side function
} 
?>