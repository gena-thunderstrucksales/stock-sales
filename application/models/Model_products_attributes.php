<?php 

class Model_products_attributes extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get active brand infromation */
	public function getActiveProductsAttributes()
	{
		$sql = "SELECT * FROM products_attributes WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/* get the brand data */
	public function getProductsAttributesData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM products_attributes WHERE product_id = ? AND active = ?";
			$query = $this->db->query($sql, array($id, 1));
			return $query->result_array();
		}
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('products_attributes', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('products_attributes', $data);
			return ($update == true) ? true : false;
		}
	}

	public function deactive($id)
	{
		if ($id) {
			$deactive = array('active' => 0);
			$this->db->where('id', $id);
			$update = $this->db->update('products_attributes', $deactive);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('products_attributes');
			return ($delete == true) ? true : false;
		}
	}

	public function removeByProductId($product_id)
	{
		if($product_id) {
			$this->db->where('product_id', $product_id);
			$delete = $this->db->delete('products_attributes');
			return ($delete == true) ? true : false;
		}
	}

}