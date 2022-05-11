<?php

class Model_report_payments_orders extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the report_payments_orders  data */
	public function getReport($data_filter = null)
	{
		$result  = array();
		if ($data_filter) {

			$result['data_customers'] = $this->getBalanceByCustomers($data_filter);
			$result['data_orders'] = $this->getBalanceByDetailsOrders($data_filter);
			$result['data_total'] = $this->getBalanceByTotal($data_filter);
		}
		return $result;
	}

	public function getBalanceByTotal($data_filter = null)
	{
		$customer_id = $data_filter['customer_id'];
		$user_id = $data_filter['user_id'];
		$active = $data_filter['active'];
		$start_date = strtotime($data_filter['start_date']);
		$end_date = strtotime($data_filter['end_date']. '+1 day');
		$currency_id = $data_filter['currency_id'];

		$sql =	"SELECT 
			SUM(m.balance) AS balance,
			SUM(COALESCE(CASE WHEN o.balance > 0 THEN o.balance END,0)) AS total_order,
            SUM(COALESCE(CASE WHEN o.balance < 0 THEN o.balance END,0)) AS total_payment

		FROM report_payments_orders m 	
		LEFT JOIN `report_payments_orders` o ON o.id = m.id AND 
                              o.`date_time`>= $start_date AND 
                              o.`date_time`<= $end_date
		WHERE 
							  m.`customer_id` LIKE $customer_id AND
							  m.`currency_id` LIKE $currency_id AND
							  m.`user_id` LIKE $user_id AND
							  m.`active` LIKE $active  AND
							  m.`date_time`<= $end_date  
		";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getBalanceByCustomers($data_filter = null)
	{
		$customer_id = $data_filter['customer_id'];
		$user_id = $data_filter['user_id'];
		$active = $data_filter['active'];
		$start_date = strtotime($data_filter['start_date']);
		$end_date = strtotime($data_filter['end_date']. '+1 day');
		$currency_id = $data_filter['currency_id'];

		$sql =	"SELECT 
		   m.`customer_id` AS customer_id,
		   t.`balance` as balance,
		   c.name as customer_name,
		   SUM(COALESCE(CASE WHEN o.balance > 0 THEN o.balance END,0)) AS total_order,
           SUM(COALESCE(CASE WHEN o.balance < 0 THEN o.balance END,0)) AS total_payment
		FROM `report_payments_orders` m
			JOIN (
					SELECT 
						`customer_id`,
						SUM(`balance`) AS `balance`
					FROM 
						`report_payments_orders`
				GROUP BY 
			`customer_id`
				) t ON t.`customer_id` = m.`customer_id` 
				    LEFT JOIN `customers` c ON 
				              c.id = m.customer_id
				    LEFT JOIN `report_payments_orders` o ON o.id = m.id AND 
                              o.`date_time`>= $start_date AND 
                              o.`date_time`<= $end_date						 
				    WHERE 
							  m.`customer_id` LIKE $customer_id AND
							  m.`currency_id` LIKE $currency_id AND
							  m.`user_id` LIKE $user_id AND
							  m.`active` LIKE $active AND
							  m.`date_time`<= $end_date 
                             	
		GROUP BY 
			m.`customer_id`
		ORDER BY customer_name ASC , m.`date_time` ASC";
		
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getBalanceByDetailsOrders($data_filter = null)
	{
		$customer_id = $data_filter['customer_id'];
		$user_id = $data_filter['user_id'];
		$active = $data_filter['active'];
		$start_date = strtotime($data_filter['start_date']);
		$end_date = strtotime($data_filter['end_date']. '+1 day');
		$currency_id = $data_filter['currency_id'];

		$sql =	"SELECT 
		    m.`customer_id` as customer_id,
			m.`order_id`  as order_id, 
			t.`balance` as balance,
		   c.name as customer_name,
		   o.total_order as total_order,
		   -p.total_payment as total_payment,
		   m.`date_time` as data_order
		FROM `report_payments_orders` m
			JOIN (
					SELECT 
						`order_id`,
						SUM(`balance`) AS `balance`
					FROM 
						`report_payments_orders`
				GROUP BY 
			`order_id`
				) t ON t.`order_id` = m.`order_id` 
				LEFT JOIN `customers` c ON 
				              c.id = m.customer_id
				    LEFT JOIN `orders` o ON 
						      o.id = m.order_id AND 
                              o.`date_time`>= $start_date AND 
                              o.`date_time`<= $end_date
					LEFT JOIN `payments` p ON
							  p.order_id = m.order_id AND 
                              p.`date_time`>= $start_date AND 
                              p.`date_time`<= $end_date							 
				    WHERE 
							  m.`customer_id` LIKE $customer_id AND
							  m.`currency_id` LIKE $currency_id AND
							  m.`user_id` LIKE $user_id AND
							  m.`active` LIKE $active AND
							  m.`date_time`<= $end_date 
							
		GROUP BY 
			m.`order_id`
	 
		ORDER BY customer_name ASC , m.`date_time` ASC";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getPaymentsDataById($id = null)
	{
		if ($id) {
			$sql = "SELECT * FROM report_payments_orders  WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}
	}

	public function create($data)
	{
		$insert = $this->db->insert('report_payments_orders', $data);
		return ($insert == true) ? true : false;
	}

	public function update($item_table_id, $table_type_id, $data)
	{
		if ($data && $item_table_id) {
			$this->db->where('item_table_id', $item_table_id);
			$this->db->where('table_type_id', $table_type_id);
			$update = $this->db->update('report_payments_orders', $data);
			return ($update == true) ? true : false;
		}
	}

	public function deactive($id)
	{
		if ($id) {
			$deactive = array('active' => 0);
			$this->db->where('id', $id);
			$update = $this->db->update('report_payments_orders', $deactive);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if ($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('report_payments_orders ');
			return ($delete == true) ? true : false;
		}
	}
}
