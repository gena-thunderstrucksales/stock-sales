<?php 

class Model_brands extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/*get the active brands information*/
	public function getActiveBrands()
	{
		$sql = "SELECT * FROM brands WHERE active = ?
		ORDER BY brands.name";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/* get the brand data */
	public function getBrandData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM brands WHERE id = ? and active = ?";
			$query = $this->db->query($sql, array($id, 1));
			return $query->row_array();
		}

		$sql = "SELECT * FROM brands  WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('brands', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('brands', $data);
			return ($update == true) ? true : false;
		}
	}

	public function deactive($id)
	{
		if($id) {
			$deactive = array('active' => 0);    
			$this->db->where('id', $id);
			$update = $this->db->update('brands', $deactive); 
			return ($update == true) ? true : false;
		}
	}

	public function exist_links($id)
	{
		$sql = "SELECT
		b.id_count AS Products
    from brands a
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