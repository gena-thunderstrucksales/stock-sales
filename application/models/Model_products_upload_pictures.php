<?php

class Model_products_upload_pictures extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function getListItemsById($id = null, $active = null)
	{
		$sql = "SELECT * FROM products_upload_pictures WHERE product_id = ? AND active = ?";
		$query = $this->db->query($sql, array($id, $active));
		return $query->result_array();
	}

	public function create($data)
	{
		if ($data) {
			$insert = $this->db->insert('products_upload_pictures', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $product_id)
	{
		if ($data && $product_id) {
			$this->db->where('product_id', $product_id);
			$update = $this->db->update('products_upload_pictures', $data);
			return ($update == true) ? true : false;
		}
	}

	public function deactive($id)
	{
		if ($id) {
			$deactive = array('active' => 0);
			$this->db->where('id', $id);
			$update = $this->db->update('products_upload_pictures', $deactive);
			return ($update == true) ? true : false;
		}
	}


	public function removeByProductId($product_id)
	{
		if($product_id) {
			$this->db->where('product_id', $product_id);
			$delete = $this->db->delete('products_upload_pictures');
			return ($delete == true) ? true : false;
		}
	}

}
