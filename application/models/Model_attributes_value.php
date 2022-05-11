<?php

class Model_attributes_value extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the attribute value data */
	// $id = attribute_parent_id
	public function getAttributeValueData($id = null)
	{
		$sql = "SELECT * FROM attribute_value WHERE attribute_parent_id = ? And active = ?";
		$query = $this->db->query($sql, array($id, 1));
		return $query->result_array();
	}

	public function getAttributeValueById($id = null)
	{
		$sql = "SELECT * FROM attribute_value WHERE id = ?";
		$query = $this->db->query($sql, array($id));
		return $query->row_array();
	}

	public function create($data)
	{
		if ($data) {
			$insert = $this->db->insert('attributes', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if ($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('attribute_value', $data);
			return ($update == true) ? true : false;
		}
	}

	public function createValue($data)
	{
		if ($data) {
			$insert = $this->db->insert('attribute_value', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function updateValue($data, $id)
	{
		if ($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('attribute_value', $data);
			return ($update == true) ? true : false;
		}
	}

	public function deactive($id)
	{
		if ($id) {
			$deactive = array('active' => 0);
			$this->db->where('id', $id);
			$update = $this->db->update('attribute_value', $deactive);
			return ($update == true) ? true : false;
		}
	}

	public function exist_links($id)
	{
		$sql = "SELECT
		p_a.id_count AS Products_attributes
    from attribute_value p
	left join (
		  select 
		  attribute_id,
		  count(id) as id_count
		  from products_attributes
		  WHERE active = 1
		  group by attribute_id
		) p_a ON p_a.attribute_id = p.id
		  WHERE p.id = ?";

		$query = $this->db->query($sql, array($id));
		return $query->row_array();
	}
}
