<?php

class Model_contacts_info extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getContactData($id = null)
	{
		if ($id) {
			$sql = "SELECT * FROM contacts_info WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}
	}

	/* get the customer_contact_info data */
	public function getContactInfoData($id = null, $active)
	{
		if ($id && $active) {
			$sql = "SELECT * FROM contacts_info WHERE item_table_id = ? AND active = ?";
			$query = $this->db->query($sql, array($id, $active));
			return $query->row_array();
		}
	}

	public function create($data)
	{
		if ($data) {
			$insert = $this->db->insert('contacts_info', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if ($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('contacts_info', $data);
			return ($update == true) ? true : false;
		}
	}

	public function deactive($id)
	{
		if ($id) {
			$deactive = array('active' => 0);
			$this->db->where('item_table_id', $id);
			$update = $this->db->update('contacts_info', $deactive);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if ($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('contacts_info');
			return ($delete == true) ? true : false;
		}
	}
}
