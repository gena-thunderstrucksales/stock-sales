<?php 

class Model_addresses extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getsAddressData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM addresses WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}
	}
	/* get the customers_addresses data */
	public function getCustomersAddressData($id = null, $active=null)
	{
		if($id) {
			$sql = "SELECT * FROM addresses WHERE item_table_id = ? AND active = ? ORDER BY type_address_id ASC";
			$query = $this->db->query($sql, array($id, $active));
			return $query->result_array();
		}
	}

	public function getCustomersAddressByTypeData($id = null, $type_address_id = null, $table_type_id = null)
	{
		if($id ) {
			$sql = "SELECT * FROM addresses WHERE item_table_id = ? AND type_address_id = ? AND table_type_id = ? ORDER BY id DESC" ;
			$query = $this->db->query($sql, array($id, $type_address_id, $table_type_id));
			return $query->row_array();
		}
	}

	public function getActiveCustomersAddressByTypeData($id = null)
	{
		if($id ) {
			$sql = "SELECT * FROM addresses WHERE item_table_id = ? AND active = ?";
			$query = $this->db->query($sql, array($id,1));
			return $query->result_array();
		}
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('addresses', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('addresses', $data);
			return ($update == true) ? true : false;
		}
	}

	public function deactive($id)
	{
		if($id) {
			$deactive = array('active' => 0);    
			$this->db->where('item_table_id', $id);
			$update = $this->db->update('addresses', $deactive); 
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('addresses');
			return ($delete == true) ? true : false;
		}
	}

	public function removeByCustomerId($customer_id)
	{
		if($customer_id) {
			$this->db->where('item_table_id', $customer_id);
			$delete = $this->db->delete('caddresses');
			return ($delete == true) ? true : false;
		}
	}

}