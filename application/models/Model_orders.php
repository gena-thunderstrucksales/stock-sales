<?php

class Model_orders extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_orders_item');
		$this->load->model('model_report_payments_orders');
		$this->load->model('model_orders_status');
	}

	/* get the orders data */
	public function getOrdersData($active = null)
	{
		$sql = "SELECT * FROM orders WHERE active = ? ORDER BY date_time DESC";
		$query = $this->db->query($sql, array($active));
		return $query->result_array();
	}

	public function getOrdersDataByIdUser($active = null, $id_user)
	{
		$sql = "SELECT * FROM orders WHERE active = ?  AND user_id = ? ORDER BY date_time DESC";
		$query = $this->db->query($sql, array($active, $id_user));
		return $query->result_array();
	}

	public function getOrdersDataByIdCustomersUser($active = null, $id_user)
	{
		$sql = "SELECT orders.id, orders.date_time, orders.customer_id, orders.total_order, orders.currency_id, orders.user_id FROM orders 
		LEFT JOIN  customers ON customers.id = orders.customer_id
		WHERE orders.active = ?  AND customers.user_id = ?
		ORDER BY orders.date_time DESC";
		$query = $this->db->query($sql, array($active, $id_user));
		return $query->result_array();
	}

	/* get the orders data */
	public function getOrdersDataById($id = null)
	{
		if ($id) {
			$sql = "SELECT * FROM orders WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}
	}

	/* get the orders data */
	public function getOrdersDateNumber($active = null, $customer_id)
	{
		if ($customer_id && $active) {
			$sql = "SELECT id, date_time, total_order FROM orders WHERE active = ? AND customer_id = ? ORDER BY date_time DESC";
			$query = $this->db->query($sql, array($active, $customer_id));
			return $query->result_array();
		}
		return '';
	}

	public function create($data)
	{
		try {
			$this->db->trans_start();
			$user_id = $this->session->userdata('id');
			$this->db->insert('orders', $data['data_header']);
			$order_id = $this->db->insert_id();

			$data_report_payments_orders = $data['data_report_payments_orders'];
			$data_report_payments_orders['item_table_id'] = $order_id;
			$data_report_payments_orders['order_id'] = $order_id;
			$this->model_report_payments_orders->create($data_report_payments_orders);

			$data_orders_status = $data['data_orders_status'];
			$data_orders_status['order_id'] = $order_id;
			$data_orders_status['data_header'] = $data_orders_status;
			$this->model_orders_status->create($data_orders_status);

			$count_product = count($this->input->post('product'));
			for ($x = 0; $x < $count_product; $x++) {
				$items = array(
					'order_id' => $order_id,
					'product_id' => $this->input->post('product')[$x],
					'qty' => $this->input->post('qty')[$x],
					'option_item_id' => $this->input->post('option_item_id')[$x],
					'price' => $this->input->post('price')[$x],
					'discount_volume' => $this->input->post('discount_volume')[$x],
					'discount_early_bird' => $this->input->post('discount_early_bird')[$x],
					'discount_cash' => $this->input->post('discount_cash')[$x],
					'sub_total' => $this->input->post('sub_total')[$x],
					'total' => $this->input->post('total')[$x],
					'sum_discount_early_bird' => $this->input->post('sum_discount_early_bird')[$x],
					'sum_discount_cash' => $this->input->post('sum_discount_cash')[$x],
					'sum_discount_volume' => $this->input->post('sum_discount_volume')[$x],
				);
				$this->db->insert('orders_item', $items);
			}
			$this->db->trans_complete();
			$this->db->trans_status();
		} catch (\Exception $e) {
			log_message('error', 'The Order did not create! User id ' . $user_id . 'ERROR: ' . $e->getMessage());
		}
		return $order_id;
	}

	public function countOrderItem($order_id)
	{
		if ($order_id) {
			$sql = "SELECT * FROM orders_item WHERE order_id = ?";
			$query = $this->db->query($sql, array($order_id));
			return $query->num_rows();
		}
	}

	public function update($id, $data)
	{
		if ($id) {
			try {
				$this->db->trans_start();
				$user_id = $this->session->userdata('id');

				$this->db->where('id', $id);
				$this->db->update('orders',  $data['data_header']);
				$this->db->where('order_id', $id);
				$this->db->delete('orders_item');

				$this->model_report_payments_orders->update($id, 3, $data['data_report_payments_orders']);

				$count_product = count($this->input->post('product'));
				for ($x = 0; $x < $count_product; $x++) {
					$items = array(
						'order_id' => $id,
						'product_id' => $this->input->post('product')[$x],
						'qty' => $this->input->post('qty')[$x],
						'option_item_id' => $this->input->post('option_item_id')[$x],
						'price' => $this->input->post('price')[$x],
						'discount_volume' => $this->input->post('discount_volume')[$x],
						'discount_early_bird' => $this->input->post('discount_early_bird')[$x],
						'discount_cash' => $this->input->post('discount_cash')[$x],
						'sub_total' => $this->input->post('sub_total')[$x],
						'total' => $this->input->post('total')[$x],
						'sum_discount_early_bird' => $this->input->post('sum_discount_early_bird')[$x],
						'sum_discount_cash' => $this->input->post('sum_discount_cash')[$x],
						'sum_discount_volume' => $this->input->post('sum_discount_volume')[$x],
					);
					$this->db->insert('orders_item', $items);
				}
				log_message('info', 'The Order did not update id ' . $id . ' ! User id ' . $user_id);
				$this->db->trans_complete();
				return $this->db->trans_status();
			} catch (\Exception $e) {
				log_message('error', 'The Order did not update id ' . $id . ' ! User id ' . $user_id . 'ERROR: ' . $e->getMessage());
			}
		}
	}

	public function deactive($id)
	{
		if ($id) {
			$this->db->trans_start();
			$deactive = array('active' => 0);
			$this->db->where('id', $id);
			$this->db->update('orders', $deactive);

			$this->db->where('order_id', $id);
			$this->db->update('orders_item', $deactive);
			$this->db->trans_complete();

			$this->db->where('item_table_id', $id);
			$this->db->update('report_payments_orders', $deactive);
			$this->db->trans_complete();

			return $this->db->trans_status();
		}
	}

	public function countTotalPaidOrders()
	{
		$sql = "SELECT * FROM orders";
		$query = $this->db->query($sql, array(1));
		return $query->num_rows();
	}

	public function exist_links($id)
	{
		$sql = "SELECT
		b.id_count AS  Orders_status,
		c.id_count AS  Payments
    from orders a
	left join (
		  select 
		  order_id,
		  count(id) as id_count
		  from orders_status
		  WHERE active = 1 and table_type_id = 4
		  group by order_id
		) b ON b.order_id = a.id
	left join (
		  select 
		  order_id,
		  count(id) as id_count
		  from payments
		  WHERE active = 1
		  group by order_id
		) c ON c.order_id = a.id

		  WHERE a.id = ?";

		$query = $this->db->query($sql, array($id));
		return $query->row_array();
	}
}
