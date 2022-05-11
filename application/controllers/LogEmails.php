<?php  

defined('BASEPATH') OR exit('No direct script access allowed');

class LogEmails extends Admin_Controller 
{	
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->data['page_title'] = 'Stores';

		$this->load->library('enumsorderstatus');
		$this->load->library('enumstypestatusobject');
		$this->load->model('model_log_emails');
		$this->load->model('model_contacts_info');
		$this->load->model('model_users');
		$this->load->helper('text');
	}

	/* 
    * It redirects to the report page
    * and based on the year, all the orders data are fetch from the database.
    */
	public function index()
	{
		if (!in_array('viewLogEmails', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->data['page_title'] = 'Log Emails';
		$this->render_template('log-emails/index', $this->data);
	}

	public function fetchListItems()
	{
		$result = array('data' => array());
		$active = $this->enumstypestatusobject->enumsNum['active'];
		$data = $this->model_log_emails->getActivelog_emails();

		foreach ($data as $key => $value) {
			$date = date('Y-m-d', $value['date_time']);
			$time = date('h:i a', $value['date_time']);
			$date_time = $date . ' ' . $time;
			$id_doc = $value['id_doc'];
			$from_email = $value['from_email'];
			$to_email = $value['to_email'];
			$info_message = character_limiter($value['info_message'], 40); 
			$user =  $this->model_users->getUserData($value['user_id']);
			$user_name = $user['username'];

			$email_status = $value['success'];
			if ($email_status == 0) {
				$status = '<a class="label-base-status label-canceled"></a><span>error</span> ';
			} elseif ($email_status == 1) {
				$status = '<a class="label-base-status label-delivered"></a><span>success</span> ';
			} else {
				$status = '<a class="label-base-status label-canceled"></a><span class="">n/a</span> ';
			}

			$result['data'][$key] = array(
				$date_time,
				$id_doc,
				$from_email,
				$to_email,
				$info_message,
				$user_name,
				$status,
			);
		} // /foreach
		echo json_encode($result);
	}	
}	