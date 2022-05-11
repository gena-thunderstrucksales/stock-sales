<?php

class Model_company extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_notification_settings');
	}

	/* get the brand data */
	public function getCompanyData($id = null)
	{
		if ($id) {
			$sql = "SELECT * FROM company WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}
		$sql = "SELECT * FROM company LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row_array();
	}


	public function update($data = array(), $id = null)
	{
		if ($data && $id) {
			try {
				$this->db->trans_start();
				$this->db->where('id', $id);
				$this->db->update('company', $data['data_company']);

				$data_contact_info = $data['data_contact_info'];
				$this->model_contacts_info->deactive($id);
				$this->model_contacts_info->create($data_contact_info);

				$this->model_addresses->deactive($id);
				$data_address_ship = $data['data_address_ship'];
				$this->model_addresses->create($data_address_ship);

				$data_address_bil = $data['data_address_bil'];
				$this->model_addresses->create($data_address_bil);

				$this->model_notification_settings->removeByProductId($id);
				if ($this->input->post('notification_email')) {
					$count = count($this->input->post('notification_email'));
					for ($x = 0; $x < $count; $x++) {
						$items = array(
							'company_id' => $id,
							'type_notification_id' => $this->input->post('type_notification_id')[$x],
							'email' => $this->input->post('notification_email')[$x],
						);
						$this->model_notification_settings->create($items);
					}
				}

				$this->db->trans_complete();
				return  $this->db->trans_status();
			} catch (\Exception $e) {
				log_message('error', 'The Company did not update! User id ' . $this->session->userdata('id') . 'ERROR: ' . $e->getMessage());
			}
		}
	}

	public function countTotalCompanies()
	{
		$sql = "SELECT * FROM company";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	public function create($data = '')
	{
		if ($data) {
			try {
				$this->db->trans_start();
				$this->db->insert('company', $data['data_company']);

				$id_company = $this->db->insert_id();

				$data_contact_info = $data['data_contact_info'];
				$data_contact_info['item_table_id'] = $id_company;
				$this->model_contacts_info->create($data_contact_info);

				$data_address_ship = $data['data_address_ship'];
				$data_address_ship['item_table_id'] = $id_company;
				$this->model_addresses->create($data_address_ship);

				$data_address_bil = $data['data_address_bil'];
				$data_address_bil['item_table_id'] = $id_company;
				$this->model_addresses->create($data_address_bil);

				$this->db->trans_complete();
				return  $this->db->trans_status();
			} catch (\Exception $e) {
				log_message('error', 'The Company did not create! User id ' . $this->session->userdata('id') . 'ERROR: ' . $e->getMessage());
			}
		}
	}
}
