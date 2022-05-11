<?php

class Model_discounts extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('enumstypediscount');
		$this->load->library('enumstypepriority');
	}

	public function getDiscountsData($active = null)
	{
		$sql = "SELECT * FROM discounts WHERE active = ? ORDER BY date_time DESC";
		$query = $this->db->query($sql, array($active));
		return $query->result_array();
	}

	/* get the discounts  data */
	public function getDiscountsDataById($id = null)
	{
		if ($id) {
			$sql = "SELECT * FROM discounts  WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}
	}

	// get the discounts  item data
	public function getDiscountsItemData($discount_id = null)
	{
		if (!$discount_id) {
			return false;
		}

		$sql = "SELECT * FROM discounts_item WHERE discount_id = ?";
		$query = $this->db->query($sql, array($discount_id));
		return $query->result_array();
	}

	public function getDiscountsItemWithOptinoData($product_id = null, $option_id = null, $sum_product = null, $date_time = null, $active = null, $currency_id = null, $priority_id= null)
	{

		if (!$product_id && !$option_id && !$date_time) {
			return false;
		}

		$sql = "SELECT * FROM discounts_item 
		RIght JOIN  discounts ON discounts_item.discount_id = discounts.id 
		WHERE product_id = ? AND attribute_id = ? AND discounts.date_time <= ? 
		AND discounts.start_date <= ? AND 
		discounts.end_date >= ? AND
		discounts.band_start <= ? AND 
		discounts.band_end >= ? AND 
		discounts.active = ? AND 
		discounts.currency_id = ? AND
		discounts.priority_id = ?
		ORDER BY discounts.date_time DESC limit 1";
		$query = $this->db->query($sql, array($product_id, $option_id, $date_time, $date_time, $date_time, $sum_product, $sum_product, $active, $currency_id, $priority_id));
		return $query->row_array();
	}

	public function getLastDiscounts($data_filter = null)
	{
		$result  = array();
		if ($data_filter) {
			$product_id = $data_filter['product_id'];
			$priority_id =  $data_filter['priority_id'];
			$currency_id = $data_filter['currency_id'];
			$end_date = strtotime($data_filter['end_date'] . '+1 day');

			$sql = "SELECT p.name as product_name, discount.option_name, discount.amount, discount.discount_id
			FROM products p
			LEFT JOIN (
			  SELECT maxd.product_id, d.amount As amount, db.discount_id,  av.value As option_name
				FROM
				  (SELECT db.product_id, db.attribute_id,   max(d.date_time) max_date
					 FROM discounts d
					INNER JOIN discounts_item db ON d.id = db.discount_id
					WHERE d.date_time <= $end_date  AND d.currency_id LIKE $currency_id and d.priority_id LIKE $priority_id and d.active = 1
					GROUP BY db.product_id,db.attribute_id
				  ) maxd
				JOIN discounts_item db ON db.product_id = maxd.product_id and db.attribute_id = maxd.attribute_id
				LEFT JOIN  attribute_value av ON db.attribute_id = av.id
				JOIN discounts d
				  ON d.id = db.discount_id AND d.date_time=maxd.max_date 
			) discount ON discount.product_id=p.id
			WHERE p.id LIKE $product_id ";

			$query = $this->db->query($sql);
			return $query->result_array();
		}
	}

	public function create($data)
	{
		try {
			$this->db->trans_start();
			$insert = $this->db->insert('discounts', $data['data_header']);
			$discounts_id = $this->db->insert_id();

			$this->load->model('model_products');

			$count_product = count($this->input->post('product'));
			for ($x = 0; $x < $count_product; $x++) {
				$items = array(
					'discount_id' => $discounts_id,
					'product_id' => $this->input->post('product')[$x],
					'attribute_id' => $this->input->post('attribute_id')[$x],
				);
				$this->db->insert('discounts_item', $items);
			}
			$this->db->trans_complete();
			return $this->db->trans_status();
		} catch (\Exception $e) {
			log_message('error', 'The Discounts did not create! User id ' . $this->session->userdata('id') . 'ERROR: ' . $e->getMessage());
		}
	}

	public function countOrderItem($discount_id)
	{
		if ($discount_id) {
			$sql = "SELECT * FROM discounts_item WHERE discount_id = ?";
			$query = $this->db->query($sql, array($discount_id));
			return $query->num_rows();
		}
	}

	public function update($id, $data)
	{
		if ($id) {
			try {
				$this->db->trans_start();
				$this->db->where('id', $id);
				$this->db->update('discounts', $data['data_header']);

				$this->db->where('discount_id', $id);
				$this->db->delete('discounts_item');

				$count_product = count($this->input->post('product'));
				for ($x = 0; $x < $count_product; $x++) {
					$items = array(
						'discount_id' => $id,
						'product_id' => $this->input->post('product')[$x],
						'attribute_id' => $this->input->post('attribute_id')[$x],
					);
					$this->db->insert('discounts_item', $items);
				}
				$this->db->trans_complete();
				return $this->db->trans_status();
			} catch (\Exception $e) {
				log_message('error', 'The Discounts did not update id ' . $id . ' ! User id ' . $this->session->userdata('id') . 'ERROR: ' . $e->getMessage());
			}
		}
	}

	public function deactive($id)
	{
		if ($id) {
			try {
				$this->db->trans_start();
				$deactive = array('active' => 0);
				$this->db->where('id', $id);
				$this->db->update('discounts', $deactive);

				$this->db->where('discount_id', $id);
				$this->db->update('discounts_item', $deactive);
				$this->db->trans_complete();

				return $this->db->trans_status();
			} catch (\Exception $e) {
				log_message('error', 'The Order did not deactive id ' . $id . ' ! User id ' . $this->session->userdata('id')  . 'ERROR: ' . $e->getMessage());
			}
		}
	}
}
