<?php

class Model_orders_item extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	// get the orders item data
	public function getOrdersItemData($order_id = null)
	{
		if (!$order_id) {
			return false;
		}

		$sql = "SELECT * FROM orders_item WHERE order_id = ?";
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}

	public function create($items = null)
	{
		if ($items) {
			return false;
		}
		return $this->db->insert('orders_item', $items);
	}
	
}
