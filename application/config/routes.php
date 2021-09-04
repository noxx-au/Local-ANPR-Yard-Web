<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/


// $route['default_controller'] = "home";
$route['default_controller'] =DEFAULT_ROUTE;
$route['HeartbeatCheck/(:any)'] = 'HeartbeatCheck/index/$1';

$route['admin/(:any)/pdf_export/(:any)'] = "admin/$1/pdf_export/$2";
$route['(:any)/drivers_declaration/download/(:any)'] = "admin/drivers_declaration/download/$1/$2";
$route['CronLiveDeleteImages/(:any)/(:any)'] = "CronLiveDeleteImages/index/$1/$2";
$route['admin/Parked_vehicles/export/(:any)'] = "admin/Parked_vehicles/export/$1";

$route['admin/induction/induction_view/(:any)'] = "admin/induction/induction_view/$1";
$route[ADMIN_PATH.'induction/export'] = "admin/induction/export";
$route['admin/drivers_declaration/drivers_declaration_view/(:any)'] = "admin/drivers_declaration/drivers_declaration_view/$1";
$route['admin/Drivers_Declaration_Adherence/drivers_declaration_adherence_view/(:any)'] = "admin/Drivers_Declaration_Adherence/drivers_declaration_adherence_view/$1";

$route['dashboard/export/(:any)'] = "admin/dashboard/export/$1";
$route['vehicles/export/(:any)'] = "admin/vehicles/export/$1";
$route['induction/pdf_export/(:any)'] = "admin/induction/pdf_export/$1";
$route['drivers_declaration/pdf_export/(:any)'] = "admin/drivers_declaration/pdf_export/$1";
$route[ADMIN_PATH.'home/auto-login/(:any)'] = "admin/Home/autoLogin/$1";
$route[ADMIN_PATH.'warehouse/edit/(:any)'] = "admin/Warehouse/edit/$1";
$route[ADMIN_PATH.'company/edit/(:any)'] = "admin/Company/edit/$1";
$route[ADMIN_PATH.'warehouse-user/(:any)'] = "admin/Warehouse_user/index/$1";
$route[ADMIN_PATH.'warehouse_user/warehouse_user_view/(:any)'] = "admin/Warehouse_user/warehouse_user_view/$1";
$route[ADMIN_PATH.'warehouse-user/add-user/(:any)'] = "admin/Warehouse_user/add_user/$1";
$route[ADMIN_PATH.'warehouse-user/edit-user/(:any)'] = "admin/Warehouse_user/edit/$1";
$route[ADMIN_PATH.'company-warehouse/(:any)'] = "admin/Warehouse/index/$1";
$route[ADMIN_PATH.'warehouse/warehouse_view/(:any)'] = "admin/Warehouse/warehouse_view/$1";
$route[ADMIN_PATH.'dashboard'] = "admin/dashboard/index";
$route[ADMIN_PATH.'vehicles'] = "admin/Vehicles";
$route[ADMIN_PATH.'invalid_plates'] = "admin/Invalid_plates";
$route[ADMIN_PATH.'unknown_vehicles'] = "admin/Unknown_vehicles";
$route[ADMIN_PATH.'blacklist_whitelist_report'] = "admin/Blacklist_whitelist_report";
$route[ADMIN_PATH.'blacklist_vehicles'] = "admin/Blacklist_vehicles";
$route[ADMIN_PATH.'whitelist_vehicles'] = "admin/Whitelist_vehicles";
$route[ADMIN_PATH.'restore_vehicles'] = "admin/Restore_vehicles";
$route[ADMIN_PATH.'parked_vehicles'] = "admin/Parked_vehicles";
$route[ADMIN_PATH.'drivers_declaration'] = "admin/drivers_declaration";
$route[ADMIN_PATH.'induction_form'] = "admin/Induction";
$route[ADMIN_PATH.'drivers_declaration_question'] = "admin/Drivers_declaration_question";
$route[ADMIN_PATH.'operator_question'] = "admin/Operator_question";
$route[ADMIN_PATH.'drivers_declaration_question/drivers_declaration_question_view'] = "admin/Drivers_declaration_question/drivers_declaration_question_view";
$route[ADMIN_PATH.'operator_question/operator_question_view'] = "admin/operator_question/operator_question_view";
$route[ADMIN_PATH.'site_induction_question'] = "admin/Site_induction_question";
$route[ADMIN_PATH.'chart'] = "admin/Chart";
$route[ADMIN_PATH.'report'] = "admin/Report";
$route[ADMIN_PATH.'location'] = "admin/Location";
$route[ADMIN_PATH.'location/location_view'] = "admin/Location/location_view";
$route[ADMIN_PATH.'site_settings'] = "admin/site_settings";
$route[ADMIN_PATH.'kpi_setting'] = "admin/kpi_setting";
$route[ADMIN_PATH.'site_settings/email_template_view'] = "admin/Site_Settings/email_template_view";
$route[ADMIN_PATH.'site_settings_history'] = "admin/Site_settings_history";
$route[ADMIN_PATH.'entries_today'] = "admin/Entries_today";
$route[ADMIN_PATH.'exits_today'] = "admin/Exits_today";
$route[ADMIN_PATH.'drivers_declaration/today'] = "admin/drivers_declaration/today";
$route[ADMIN_PATH.'induction/today'] = "admin/Induction/today";
$route[ADMIN_PATH.'vehicles_on_site'] = "admin/Vehicles_on_site";
$route[ADMIN_PATH.'vehicles_throughput'] = "admin/Vehicles_throughput";
$route[ADMIN_PATH.'vehicles_parked'] = "admin/Vehicles_parked";
$route[ADMIN_PATH.'site_settings/index'] = "admin/site_settings/index";
$route[ADMIN_PATH.'kpi_setting/index'] = "admin/kpi_setting/index";
$route[ADMIN_PATH.'site_settings/vehicle_setting'] = "admin/site_settings/vehicle_setting";
$route[ADMIN_PATH.'site_settings/update_image_attribute'] = "admin/site_settings/update_image_attribute";
$route[ADMIN_PATH.'site_settings/update_email'] = "admin/site_settings/update_email";
$route[ADMIN_PATH.'drivers_declaration/adherence'] = "admin/drivers_declaration/adherence";
$route[ADMIN_PATH.'drivers_declaration/export'] = "admin/drivers_declaration/export";
$route[ADMIN_PATH.'email_template/edit'] = "admin/Email_template/edit";
$route[ADMIN_PATH.'home/logout'] = "admin/home/logout";
$route[ADMIN_PATH.'warehouse'] = "admin/Warehouse/index";
$route[ADMIN_PATH.'warehouse/add_warehouse'] = "admin/Warehouse/add_warehouse";
$route[ADMIN_PATH.'company/add_company'] = "admin/Company/add_company";
$route[ADMIN_PATH.'company'] = "admin/Company/index";
$route[ADMIN_PATH.'error_log'] = "admin/Error_log/index";
$route[ADMIN_PATH.'forgot_password'] = "admin/forgot_password/index";
$route[ADMIN_PATH.'forgot_password/send_mail'] = "admin/forgot_password/send_mail";
$route[ADMIN_PATH.'notification'] = "admin/notification/index";

$route[ADMIN_PATH.'drivers-declaration-pending'] = "DriversDeclarationPending/index";
$route[ADMIN_PATH.'drivers_declaration_pending/verify_drivers_declaration_ajax'] = "DriversDeclarationPending/verify_drivers_declaration_ajax";
$route[ADMIN_PATH.'DriversDeclarationPending/search'] = "DriversDeclarationPending/search";
$route[ADMIN_PATH.'DriversDeclaration/search'] = "DriversDeclaration/search";


$route[ADMIN_PATH.'drivers-declaration'] = "DriversDeclaration/index";



$route[ADMIN_PATH.'operator-home'] = "OperatorHome/index";



$route[ADMIN_PATH.'diagnosis_vehicles'] = "admin/Diagnosis_vehicles/index";
$route['Live/CronReport/start'] = "Live/CronReport/start";
$route['CronReport/start'] = "CronReport/start";
$route['CronCopyCameraImage/start'] = "CronCopyCameraImage/start";
$route['CronDeleteImages/start'] = "CronDeleteImages/start";
$route['CronDriverDescInComplete/start'] = "CronDriverDescInComplete/start";
$route['CronServiceStatus/start'] = "CronServiceStatus/start";
$route['CronUploadCameraImage/start'] = "CronUploadCameraImage/start";
$route['CronUploadLogs/start'] = "CronUploadLogs/start";
$route['DownloadImages/start'] = "DownloadImages/start";
$route['LocalHeartbeatCheck/start'] = "LocalHeartbeatCheck/start";
$route['SyncAppImages/start'] = "SyncAppImages/start";
$route['SyncData/start'] = "SyncData/start";
$route['CronAutoPark/start'] = "CronAutoPark/start";
$route['CronQuestions/start'] = "CronQuestions/start";
$route['ServiceStatus/service_status_change'] = "ServiceStatus/service_status_change";
$route['ServiceStatus/set_all_service'] = "ServiceStatus/set_all_service";
$route[ADMIN_PATH.'CronEmailSend'] = "admin/CronEmailSend";
$route[ADMIN_PATH.'ServerStausCheck'] = "admin/ServerStausCheck";
// $route[ADMIN_PATH.'ServiceStatus'] = "admin/ServiceStatus";
$route[ADMIN_PATH.'change_password'] = "admin/Change_password";
$route[ADMIN_PATH.'change_password/update_password'] = "admin/Change_password/update_password";
$route[ADMIN_PATH.'task_list'] = "admin/Task_list";
$route[ADMIN_PATH.'Task_list/view'] = "admin/Task_list/view";
$route[ADMIN_PATH.'dabase-backup/(:any)'] = "admin/Database_backup/index/$1";
$route[ADMIN_PATH.'Database_backup/view/(:any)'] = "admin/Database_backup/view/$1";
$route[ADMIN_PATH.'api_key'] = "admin/Api_key";
$route[ADMIN_PATH.'Api_key/view'] = "admin/Api_key/view";
$route[ADMIN_PATH.'warehouse_site_settings'] = "admin/Warehouse_site_settings";
$route[ADMIN_PATH.'kiosk-dashboard'] = "admin/dashboard/index";
/*
$route['(:any)'] = "admin/$1";

$route['vehicle_log/(:any)'] = "admin/vehicle_log/index/$1";

$route['(:any)/(:any)'] = "admin/$1/$2";

$route['(:any)/(:any)/(:any)/(:any)'] = "admin/$1/$2/$3/$4";
*/
$route['induction/send_induction_ajax'] = "Induction/send_induction_ajax";
$route['home/do_login'] = "admin/Home/do_login";
$route['reset_password/update_password/(:any)'] = "admin/Reset_password/update_password/$1";
// $route['(:any)'] = "";
// $route['(:any)/(:any)'] = "$2";
 $route['plateresults/add_vehicle_manully_ajax'] = "Plateresults/add_vehicle_manully_ajax";
 $route['plateresults/force_to_add_vehicle'] = "Plateresults/force_to_add_vehicle";
 $route['drivers/send_driver_ajax'] = "Drivers/send_driver_ajax";
 $route['drivers/send_flashdata'] = "Drivers/send_flashdata";
 $route['drivers/get_driver_detail'] = "Drivers/get_driver_detail";
 $route['parking/park_vehicle_ajax'] = "Parking/park_vehicle_ajax";
 $route['unparking/unpark_vehicle_ajax'] = "Unparking/unpark_vehicle_ajax";
 $route[ADMIN_PATH.'equipment'] = "admin/Equipment/index";
 $route[ADMIN_PATH.'equipment/add_equipment'] = "admin/Equipment/add_equipment";
 $route[ADMIN_PATH.'equipment/edit/(:any)'] = "admin/Equipment/edit/$1";
 $route[ADMIN_PATH.'warehouse-equipment/(:any)'] = "admin/Warehouse_equipment/index/$1";
 $route[ADMIN_PATH.'warehouse-equipment/add-equipment/(:any)'] = "admin/Warehouse_equipment/add_equipment/$1";
 $route[ADMIN_PATH.'warehouse-equipment/edit/(:any)'] = "admin/Warehouse_equipment/edit/$1";
 $route[ADMIN_PATH.'equipment/export'] = "admin/Equipment/export";
 $route[ADMIN_PATH.'Warehouse_equipment/export/(:any)'] = "admin/Warehouse_equipment/export/$1";
$route['home/maintenance_mode_on_off'] = "Home/maintenance_mode_on_off";
// $route['kpi'] = "Home/kpi";
$route['(:any)/kpi'] = "Home/kpi";
$route['(:any)/kpi_ajax'] = "Home/kpi_ajax";
$route['home/get_maintenance_mode'] = "Home/get_maintenance_mode";
$route[ADMIN_PATH.'warehouse-login'] = "admin/Warehouse_login/index";
$route[ADMIN_PATH.'Warehouse_login/warehouse_login_view'] = "admin/Warehouse_login/warehouse_login_view";
$route[ADMIN_PATH.'dashboard-notification'] = "admin/Dashboard_notification";
$route[ADMIN_PATH.'company-dashboard'] = "admin/Company_dashboard/index";
$route[ADMIN_PATH.'location-login/(:any)'] = "admin/Auto_login/index/$1";
$route[ADMIN_PATH.'home/authentication-verify'] = "admin/Home/authentication_verify";
/*$route[ADMIN_PATH.'email-notification'] = "admin/Email_notification";
$route[ADMIN_PATH.'email-notification/email_notification_view'] = "admin/Email_notification/email_notification_view";
$route[ADMIN_PATH.'email-notification/export'] = "admin/Email_notification/export";
*/
$route[ADMIN_PATH.'kiosk-dashboard'] = "admin/Kiosk_dashboard/index";

$route['(:any)'] = function($slug){ 
	if($slug!="" && filter_var($slug, FILTER_VALIDATE_EMAIL) && LIVE) {
		return  "/home";
	}else{		
		if($slug =='' && LIVE){
			return  "/".ADMIN_PATH;
		}else{
			return  "/".$slug;
		}		
	}
	 
};
$route['(:any)/(:any)'] = function($slug,$slug1){
	if($slug!="" && filter_var($slug, FILTER_VALIDATE_EMAIL) && LIVE) {	
		if($slug1 == 'kiosk-dashboard'){
			return  'admin/Home/autoLogin/'.$slug.'/1';
		}else{
			return  "/".$slug1;
		}
		
	}else{
		if($slug =='' && LIVE){
			return  ADMIN_PATH.$slug1;
		}else{
			return $slug1;
		}		
	}
};


// $route[ADMIN_PATH.'notification'] = "admin/Notification/index";
$route['db_backup'] = "Db_Backup/index";
$route['404_override'] = '';


  


