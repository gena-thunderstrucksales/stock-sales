<?php

class Model_customers extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_addresses');
		$this->load->model('model_contacts_info');
	}

	public function getCustomerData($customerId = null)
	{
		if ($customerId) {
			$sql = "SELECT * FROM customers WHERE id = ? ";
			$query = $this->db->query($sql, array($customerId));
			return $query->row_array();
		}
	}

	public function getCustomerListData($active = null)
	{
		$sql = "SELECT * FROM customers WHERE active = ? ORDER BY name ASC";
		$query = $this->db->query($sql, array($active));
		return $query->result_array();
	}

	public function getCustomerListDataByUser($active = null, $id_user)
	{
		$sql = "SELECT * FROM customers WHERE active = ? AND user_id = ? ORDER BY name ASC";
		$query = $this->db->query($sql, array($active, $id_user));
		return $query->result_array();
	}

	public function create($data = '')
	{
		if ($data) {
			try {
				$this->db->trans_start();
				$this->db->insert('customers', $data['data_customer']);

				$id_customer = $this->db->insert_id();

				$data_contact_info = $data['data_contact_info'];
				$data_contact_info['item_table_id'] = $id_customer;
				$this->model_contacts_info->create($data_contact_info);

				$data_address_ship = $data['data_address_ship'];
				$data_address_ship['item_table_id'] = $id_customer;
				$this->model_addresses->create($data_address_ship);

				$data_address_bil = $data['data_address_bil'];
				$data_address_bil['item_table_id'] = $id_customer;
				$this->model_addresses->create($data_address_bil);

				$this->db->trans_complete();
				return  $this->db->trans_status();
			} catch (\Exception $e) {
				log_message('error', 'The Customer did not create! User id ' . $this->session->userdata('id') . 'ERROR: ' . $e->getMessage());
			}
		}
	}

	public function edit($data = array(), $id = null)
	{
		if ($data && $id) {
			try {
				$this->db->trans_start();
				$this->db->where('id', $id);
				$this->db->update('customers', $data['data_customer']);

				$data_contact_info = $data['data_contact_info'];
				$this->model_contacts_info->deactive($id);
				$this->model_contacts_info->create($data_contact_info);

				$this->model_addresses->deactive($id);
				$data_address_ship = $data['data_address_ship'];
				$this->model_addresses->create($data_address_ship);

				$data_address_bil = $data['data_address_bil'];
				$this->model_addresses->create($data_address_bil);

				$this->db->trans_complete();
				return  $this->db->trans_status();
			} catch (\Exception $e) {
				log_message('error', 'The Customer did not edit! User id ' . $this->session->userdata('id') . 'ERROR: ' . $e->getMessage());
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
				$this->db->update('customers', $deactive);
				$this->model_contacts_info->deactive($id);
				$this->model_addresses->deactive($id);
				$this->db->trans_complete();
				return $this->db->trans_status();
			} catch (\Exception $e) {
				log_message('error', 'The Customer did not deactive! User id ' . $this->session->userdata('id') . 'ERROR: ' . $e->getMessage());
			}
		}
	}

	public function countTotalCustomers()
	{
		$sql = "SELECT * FROM customers";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	public function exist_links($id)
	{
		$sql = "SELECT
		b.id_count AS Orders
    from customers a
	left join (
		  select 
		  customer_id,
		  count(id) as id_count
		  from orders
		  WHERE active = 1
		  group by customer_id
		) b ON b.customer_id = a.id
		  WHERE a.id = ?";

		$query = $this->db->query($sql, array($id));
		return $query->row_array();
	}
}
