<?php

class Model_log_emails extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_company');
	}

	/*get the active log_emails information*/
	public function getActivelog_emails()
	{
		$sql = "SELECT * FROM log_emails ORDER BY date_time DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function create($data)
	{
		if ($data) {
			$insert = $this->db->insert('log_emails', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if ($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('log_emails', $data);
			return ($update == true) ? true : false;
		}
	}

	public function deactive($id)
	{
		if ($id) {
			$deactive = array('active' => 0);
			$this->db->where('id', $id);
			$update = $this->db->update('log_emails', $deactive);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if ($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('log_emails');
			return ($delete == true) ? true : false;
		}
	}

	public function send_by_email($array_data = null)
	{
		$company = $this->model_company->getCompanyData();
		!$send_email = $company['send_email'];
		if (!$send_email) {
			return false;
		}
		$this->email->clear();
		$from_email = $array_data['from_email'];
		$to_email = $array_data['to_email'];
		$subject = $array_data['subject'];
		$config = $array_data['config'];
		$message = $array_data['message'];
		$id_doc = $array_data['id_doc'];
		$user_id = $array_data['user_id'];
		$date_time = $array_data['date_time'];
		$body = $array_data['body'];

		$this->email->initialize($config);

		$this->email->from($from_email);
		$this->email->to($to_email);
		$this->email->subject($subject);
		$this->email->message($message);

		$array_data_base = array();
		$array_data_base['from_email'] = $from_email;
		$array_data_base['to_email'] = 	$to_email;
		$array_data_base['id_doc'] = $id_doc;
		$array_data_base['user_id'] = $user_id;
		$array_data_base['date_time'] = $date_time;

		try {
			if ($this->email->send()) {
				$array_data_base['success'] = 	true;
				$array_data_base['info_message'] = 'Your Email has successfully been sent.';
			} else {
				$array_data_base['success'] = 	false;
				$array_data_base['info_message'] = $this->email->print_debugger();
			}

			$this->create($array_data_base);
			return 	$array_data_base['success'];
		} catch (Exception $e) {
			return 	false;
		}
	}
}
