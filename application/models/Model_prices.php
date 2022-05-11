<?php

class Model_prices extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the prices  data */
	public function getPricesData($active = null)
	{
		$sql = "SELECT * FROM prices WHERE active = ? ORDER BY date_time DESC";
		$query = $this->db->query($sql, array($active));
		return $query->result_array();
	}

	public function getPricesDataById($id = null)
	{
		if ($id) {
			$sql = "SELECT * FROM prices  WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}
	}

	// get the prices  item data     ORDER BY id DESC
	public function getPricesItemData($price_id = null)
	{
		if (!$price_id) {
			return false;
		}

		$sql = "SELECT * FROM prices_item WHERE price_id = ?";
		$query = $this->db->query($sql, array($price_id));
		return $query->result_array();
	}

	public function getPricesByProductId($product_id = null,  $date_time = null, $active = null, $currency_id = null)
	{
		if (!$product_id && !$date_time) {
			return false;
		}
		$sql = "SELECT * FROM prices_item 
		LEFT JOIN  prices ON prices_item.price_id = prices.id 
		WHERE product_id = ? AND prices.date_time <= ? AND prices.active = ? AND prices.currency_id LIKE ?
		ORDER BY prices.date_time DESC LIMIT 1";
		$query = $this->db->query($sql, array($product_id, $date_time, $active, $currency_id));
		return $query->row_array();
	}

	// get the prices  item data
	public function getPricesItemWithOptinoData($product_id = null, $option_id = null, $date_time = null, $active = null, $currency_id = null)
	{
		if (!$product_id && !$option_id && !$date_time) {
			return false;
		}
		$sql = "SELECT * FROM prices_item 
		LEFT JOIN  prices ON prices_item.price_id = prices.id 
		WHERE product_id = ? AND attribute_id = ? AND prices.date_time <= ? AND prices.active = ? AND prices.currency_id LIKE ?
		ORDER BY prices.date_time DESC LIMIT 1";
		$query = $this->db->query($sql, array($product_id, $option_id, $date_time, $active, $currency_id));
		return $query->row_array();
	}

	// get the prices  item data
	public function getListPricesItemWithOptinoData($product_id = null, $option_id = null, $date_time = null, $active = null)
	{
		if (!$product_id && !$option_id && !$date_time) {
			return false;
		}
		$sql = "SELECT * FROM prices_item 
		LEFT JOIN  prices ON prices_item.price_id = prices.id 
		WHERE product_id = ? AND attribute_id = ? AND prices.date_time <= ? AND prices.active = ? 
		ORDER BY prices.date_time DESC";
		$query = $this->db->query($sql, array($product_id, $option_id, $date_time, $active));
		return $query->result_array();
	}


	// get the prices  item data
	public function getLastPrices($data_filter = null)
	{
		$result  = array();
		if ($data_filter) {
			$product_id = $data_filter['product_id'];
			$currency_id = $data_filter['currency_id'];
			$end_date = strtotime($data_filter['end_date'] . '+1 day');

			$sql = "SELECT p.name as product_name, price.option_name, price.price, price.price_id
			FROM products p
			LEFT JOIN (
			  SELECT maxd.product_id, db.price, db.price_id, av.value As option_name
				FROM
				  (SELECT db.product_id, db.attribute_id, max(d.date_time) max_date
					 FROM prices d
					INNER JOIN prices_item db ON d.id = db.price_id
					WHERE d.date_time <= $end_date 
					AND d.currency_id LIKE $currency_id
					AND  d.active = 1 

					GROUP BY db.product_id,db.attribute_id
				  ) maxd

				JOIN prices_item db ON db.product_id = maxd.product_id and db.attribute_id = maxd.attribute_id
				LEFT JOIN  attribute_value av ON db.attribute_id = av.id
				JOIN prices d
				  ON d.id = db.price_id AND d.date_time=maxd.max_date 
			) price ON price.product_id=p.id
			WHERE p.id LIKE $product_id";

			$query = $this->db->query($sql);
			return $query->result_array();
		}
	}

	public function countOrderItem($price_id)
	{
		if ($price_id) {
			$sql = "SELECT * FROM prices_item WHERE price_id = ?";
			$query = $this->db->query($sql, array($price_id));
			return $query->num_rows();
		}
	}

	public function create($data)
	{
		try {
			$this->db->trans_start();

			$insert = $this->db->insert('prices', $data['data_header']);
			$price_id = $this->db->insert_id();

			$count_product = count($this->input->post('product'));
			for ($x = 0; $x < $count_product; $x++) {
				$items = array(
					'price_id' => $price_id,
					'product_id' => $this->input->post('product')[$x],
					'price' => $this->input->post('price')[$x],
					'attribute_id' => $this->input->post('attribute_id')[$x],
				);
				$this->db->insert('prices_item', $items);
			}
			$this->db->trans_complete();
			return $this->db->trans_status();
		} catch (\Exception $e) {
			log_message('error', 'The Prices did not create! User id ' . $this->session->userdata('id') . 'ERROR: ' . $e->getMessage());
		}
	}


	public function update($id, $data)
	{
		if ($id) {
			try {
			$this->db->trans_start();

			$this->db->where('id', $id);
			$insert = $this->db->update('prices',  $data['data_header']);

			$this->db->where('price_id', $id);
			$this->db->delete('prices_item');

			$count_product = count($this->input->post('product'));
			for ($x = 0; $x < $count_product; $x++) {
				$items = array(
					'price_id' => $id,
					'product_id' => $this->input->post('product')[$x],
					'price' => $this->input->post('price')[$x],
					'attribute_id' => $this->input->post('attribute_id')[$x],
				);
				$this->db->insert('prices_item', $items);
			}
			$this->db->trans_complete();
			return $this->db->trans_status();
		} catch (\Exception $e) {
			log_message('error', 'The Prices did not update id ' .$id.' ! User id ' . $this->session->userdata('id')  . 'ERROR: ' . $e->getMessage());
		}
		}
	}

	public function deactive($id)
	{
		if ($id) {
			$this->db->trans_start();
			$deactive = array('active' => 0);
			$this->db->where('id', $id);
			$this->db->update('prices', $deactive);

			$this->db->where('price_id', $id);
			$this->db->update('prices_item', $deactive);
			$this->db->trans_complete();

			return $this->db->trans_status();
		}
	}

	public function remove($id)
	{
		if ($id) {
			$this->db->trans_start();
			$this->db->where('id', $id);
			$this->db->delete('prices ');

			$this->db->where('price_id', $id);
			$this->db->delete('prices_item');
			$this->db->trans_complete();

			return $this->db->trans_status();
		}
	}
}
