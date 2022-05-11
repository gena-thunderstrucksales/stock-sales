<?php 

class Model_category extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get active brand infromation */
	public function getActiveCategroy()
	{
		$sql = "SELECT * FROM categories WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/* get the brand data */
	public function getCategoryData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM categories WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM categories  WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('categories', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function deactive($id)
	{
		if($id) {
			$deactive = array('active' => 0);    
			$this->db->where('id', $id);
			$update = $this->db->update('categories', $deactive); 
			return ($update == true) ? true : false;
		}
	}


	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('categories', $data);
			return ($update == true) ? true : false;
		}
	}

	public function exist_links($id)
	{
		$sql = "SELECT
		b.id_count AS Products
    from categories a
	left join (
		  select 
		  brand_id,
		  count(id) as id_count
		  from products
		  WHERE active = 1
		  group by brand_id
		) b ON b.brand_id = a.id
		  WHERE a.id = ?";

		$query = $this->db->query($sql, array($id));
		return $query->row_array();
	}
}