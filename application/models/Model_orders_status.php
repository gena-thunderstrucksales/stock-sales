<?php

class Model_orders_status extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the orders_status  data */
	public function getOrdersStatusData($active = null)
	{
		$sql = "SELECT * FROM orders_status WHERE active = ? and table_type_id = ? ORDER BY date_time DESC";
		$query = $this->db->query($sql, array($active, 4));
		return $query->result_array();
	}

	public function getOrdersStatusDataByIdOrder($order_id = null, $date_time = null, $active = null)
	{
		if ($order_id) {
			$sql = "SELECT * FROM orders_status  
		WHERE order_id = ? AND date_time <= ? AND active = ?
		ORDER BY date_time DESC LIMIT 1";
			$query = $this->db->query($sql, array($order_id, $date_time, $active));
			return $query->row_array();
		}
	}

	public function getOrdersStatusDataById($id = null)
	{
		if ($id) {
			$sql = "SELECT * FROM orders_status WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}
	}

	public function create($data)
	{
		try {
			$this->db->trans_start();
			$insert = $this->db->insert('orders_status',  $data['data_header']);
			$order_id = $this->db->insert_id();
			$this->db->trans_complete();
			return $this->db->trans_status();
		} catch (\Exception $e) {
			log_message('error', 'The Order status did not create! User id ' . $this->session->userdata('id') . 'ERROR: ' . $e->getMessage());
		}
	}

	public function update($id, $data)
	{
		if ($data && $id) {
			try {
				$this->db->trans_start();
				$this->db->where('id', $id);
				$update = $this->db->update('orders_status', $data['data_header']);
				$this->db->trans_complete();
				return $this->db->trans_status();
			} catch (\Exception $e) {
				log_message('error', 'The Order status  did not update id ' . $id . ' ! User id ' . $this->session->userdata('id') . 'ERROR: ' . $e->getMessage());
			}
		}
	}

	public function deactive($id)
	{
		if ($id) {
			try {
				$deactive = array('active' => 0);
				$this->db->where('id', $id);
				$update = $this->db->update('orders_status', $deactive);
				return ($update == true) ? true : false;
			} catch (\Exception $e) {
				log_message('error', 'The Order status  did not deactive id ' . $id . ' ! User id ' . $this->session->userdata('id') . 'ERROR: ' . $e->getMessage());
			}
		}
	}
}
