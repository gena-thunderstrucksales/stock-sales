<?php

class Model_taxes extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/*get the active taxes information*/
	public function getActiveTaxes()
	{
		$sql = "SELECT * FROM taxes WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/* get the brand data */
	public function getTaxData($id = null)
	{
		if ($id) {
			$sql = "SELECT * FROM taxes WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}
	}

	public function create($data)
	{
		if ($data) {
			$insert = $this->db->insert('taxes', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if ($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('taxes', $data);
			return ($update == true) ? true : false;
		}
	}

	public function deactive($id)
	{
		if ($id) {
			$deactive = array('active' => 0);
			$this->db->where('id', $id);
			$update = $this->db->update('taxes', $deactive);
			return ($update == true) ? true : false;
		}
	}

	public function exist_links($id)
	{

		$sql = "SELECT
b.id_count AS Orders
from taxes a
left join (
  select 
  tax_id,
  count(id) as id_count
  from orders
  WHERE active = 1
  group by tax_id
) b ON b.tax_id = a.id

left join (
  select 
  tax_id,
  count(id) as id_count
  from customers
  WHERE active = 1
  group by tax_id
) c ON c.tax_id = a.id
  WHERE a.id = ?";

		$query = $this->db->query($sql, array($id));
		return $query->row_array();
	}
}
