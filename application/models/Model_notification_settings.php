<?php

class Model_notification_settings extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getNotificationSettingsData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM notification_settings WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM notification_settings";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getNotificationSettingsDataByType($type_notification_id = null)
	{
		if($type_notification_id) {
			$sql = "SELECT * FROM notification_settings WHERE type_notification_id = ?";
			$query = $this->db->query($sql, array($type_notification_id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM notification_settings";
		$query = $this->db->query($sql);
		return $query->result_array();
	}


	public function create($data)
	{
		if ($data) {
			$insert = $this->db->insert('notification_settings', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if ($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('notification_settings', $data);
			return ($update == true) ? true : false;
		}
	}

	public function deactive($id)
	{
		if ($id) {
			$deactive = array('active' => 0);
			$this->db->where('id', $id);
			$update = $this->db->update('notification_settings', $deactive);
			return ($update == true) ? true : false;
		}
	}


	public function removeByProductId($company_id)
	{
		if($company_id) {
			$this->db->where('company_id', $company_id);
			$delete = $this->db->delete('notification_settings');
			return ($delete == true) ? true : false;
		}
	}
}
