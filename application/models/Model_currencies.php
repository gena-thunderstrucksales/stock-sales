<?php

class Model_currencies extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/*get the active currencies information*/
	public function getActiveCurrencies()
	{
		$sql = "SELECT * FROM currencies WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/* get the brand data */
	public function getCurrencyData($id = null)
	{
		if ($id) {
			$sql = "SELECT * FROM currencies WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM currencies";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function create($data)
	{
		if ($data) {
			$insert = $this->db->insert('currencies', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if ($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('currencies', $data);
			return ($update == true) ? true : false;
		}
	}

	public function deactive($id)
	{
		if ($id) {
			$deactive = array('active' => 0);
			$this->db->where('id', $id);
			$update = $this->db->update('currencies', $deactive);
			return ($update == true) ? true : false;
		}
	}

	public function exist_links($id)
	{
		$sql = "SELECT
		b.id_count AS Orders
    from currencies a
	left join (
		  select 
		  currency_id,
		  count(id) as id_count
		  from orders
		  WHERE active = 1
		  group by currency_id
		) b ON b.currency_id = a.id

		left join (
		  select 
		  currency_id,
		  count(id) as id_count
		  from payments
		  WHERE active = 1
		  group by currency_id
		) c ON c.currency_id = a.id
		  WHERE a.id = ?";

		$query = $this->db->query($sql, array($id));
		return $query->row_array();
	}
}
