<?php

class Model_products extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_products_attributes');
		$this->load->model('model_products_upload_pictures');
		
	}

	/* get the brand data */
	public function getProductData($id = null)
	{
		if ($id) {
			$sql = "SELECT * FROM products where id = ? AND  active = ?";
			$query = $this->db->query($sql, array($id, 1));
			return $query->row_array();
		}

		$sql = "SELECT * FROM products  where active = ? ORDER BY name ASC";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	public function getProductDataStatusPublish($status_publish = null)
	{
		if ($status_publish != null)  {
			$sql = "SELECT * FROM products where status_publish = ? AND  active = ? ORDER BY name ASC";
			$query = $this->db->query($sql, array($status_publish, 1));
			return $query->result_array();
		} else{
			$sql = "SELECT * FROM products  where active = ? ORDER BY name ASC";
			$query = $this->db->query($sql, array(1));
			return $query->result_array();
		}
	}

	public function getProductFiltrBrandData($brand_id = null)
	{
		if ($brand_id) {
			$sql = "SELECT * FROM products where brand_id = ? AND  active = ?";
			$query = $this->db->query($sql, array($brand_id, 1));
			return $query->result_array();
		}
	}

	public function getProductFiltrCategoryData($category_id = null)
	{
		if ($category_id) {
			$sql = "SELECT * FROM products where category_id = ? AND  active = ? AND status_publish  = ?";
			$query = $this->db->query($sql, array($category_id, 1, 1));
			return $query->result_array();
		}
	}

	public function setStatusProduct($id = null, $status_publish_id = null){
		if ($id) {
			$status_publish = array('status_publish' => $status_publish_id);
			$this->db->where('id', $id);
			$update = $this->db->update('products', $status_publish);
			return ($update == true) ? true : false;
		}
	}

	public function getActiveProductData()
	{
		$sql = "SELECT * FROM products  where active = ? AND status_publish  = ? ORDER BY name ASC";
		$query = $this->db->query($sql, array(1,1));
		return $query->result_array();
	}

	public function create($data)
	{
		$this->db->trans_start();
		if ($data) {

			$insert = $this->db->insert('products', $data['data_product']);
			$id = $this->db->insert_id();

			$data_list_pictures = $data['data_list_pictures'];
			foreach ($data_list_pictures as $key_p => $value) {
				$value['product_id'] = $id ;
				$this->model_products_upload_pictures->create( $value);
			}
   
			$this->load->model('model_products_attributes');

			if ($this->input->post('attribute')) {
				$count_product = count($this->input->post('attribute'));
				for ($x = 0; $x < $count_product; $x++) {
					$items = array(
						'product_id' => $id,
						'attribute_id' => $this->input->post('attribute_id')[$x],
					);
					$this->model_products_attributes->create($items);
				}
			}
			$this->db->trans_complete();
			return $this->db->trans_status();
		}
	}

	public function update($data, $id)
	{
		$this->db->trans_start();
		if ($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('products', $data['data_product']);
			$this->load->model('model_products_attributes');

			$this->model_products_upload_pictures->removeByProductId($id);

			$data_list_pictures = $data['data_list_pictures'];
			foreach ($data_list_pictures as $key_p => $value) {
				$value['product_id'] = $id ;
				$this->model_products_upload_pictures->create( $value);
			}

			$this->model_products_attributes->removeByProductId($id);
			if ($this->input->post('attribute')) {
				$count_product = count($this->input->post('attribute'));
				for ($x = 0; $x < $count_product; $x++) {
					$items = array(
						'product_id' => $id,
						'attribute_id' => $this->input->post('attribute_id')[$x],
					);
					$this->model_products_attributes->create($items);
				}
			}
			$this->db->trans_complete();
			return $this->db->trans_status();
		}
	}


	public function deactive($id)
	{
		if ($id) {
			$deactive = array('active' => 0);
			$this->db->where('id', $id);
			$update = $this->db->update('products', $deactive);
			return ($update == true) ? true : false;
		}
	}
	public function countTotalProducts()
	{
		$sql = "SELECT * FROM products";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	public function exist_links($id)
	{
		$sql = "SELECT
	 	p_i.id_count AS Prices,
		o_i.id_count AS Orders,
		d_i.id_count AS Discounts
 from products p
	left join (
		  select 
		  product_id,
		  count(id) as id_count
		  from orders_item
		  WHERE active = 1
		  group by product_id
		) o_i ON o_i.product_id = p.id
	left join (
		  select
		  product_id,
		  count(id) as id_count
		  from discounts_item 
		  WHERE active = 1
		  group by product_id
		  ) d_i ON d_i.product_id = p.id
	left join (
		  select
		  product_id,
		  count(id) as id_count
		  from prices_item 
		  WHERE active = 1
		  group by product_id
		  ) p_i ON p_i.product_id = p.id
		  WHERE p.id = ?";

		$query = $this->db->query($sql, array($id));
		return $query->row_array();
	}
}
