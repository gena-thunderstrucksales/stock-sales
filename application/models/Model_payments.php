<?php

class Model_payments extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the payments  data */
	public function getPaymentsData($active = null)
	{
		$sql = "SELECT * FROM payments WHERE active = ? ORDER BY date_time DESC";
		$query = $this->db->query($sql, array($active));
		return $query->result_array();
	}

	public function getPaymentsDataByUser($active = null, $id_user)
	{
		$sql = "SELECT * FROM payments WHERE active = ? AND user_id = ? ORDER BY date_time DESC";
		$query = $this->db->query($sql, array($active, $id_user));
		return $query->result_array();
	}

	public function getPaymentsDataById($id = null)
	{
		if ($id) {
			$sql = "SELECT * FROM payments  WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}
	}

	public function create($data)
	{
		try {
			$this->db->trans_start();
			$insert = $this->db->insert('payments',  $data['data_header']);
			$order_id = $this->db->insert_id();

			$data_report_payments_orders = $data['data_report_payments_orders'];
			$data_report_payments_orders['item_table_id'] = $order_id;
			$this->model_report_payments_orders->create($data_report_payments_orders);

			$this->db->trans_complete();
			return $this->db->trans_status();
		} catch (\Exception $e) {
			log_message('error', 'The Payment did not create! User id ' . $this->session->userdata('id') . 'ERROR: ' . $e->getMessage());
		}
	}

	public function update($id, $data)
	{
		if ($data && $id) {
			try {
				$this->db->trans_start();
				$this->db->where('id', $id);
				$update = $this->db->update('payments', $data['data_header']);

				$data_report_payments_orders = $data['data_report_payments_orders'];
				$this->model_report_payments_orders->update($id, 2, $data_report_payments_orders);
				$this->db->trans_complete();
				return $this->db->trans_status();
			} catch (\Exception $e) {
				log_message('error', 'The Payment did not update id ' . $id . ' ! User id ' . $this->session->userdata('id')  . 'ERROR: ' . $e->getMessage());
			}
		}
	}

	public function deactive($id)
	{
		if ($id) {
			$deactive = array('active' => 0);
			$this->db->where('id', $id);
			$update = $this->db->update('payments', $deactive);
			return ($update == true) ? true : false;
		}
	}
}
