<?php

class Customers extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->data['page_title'] = 'Customers';
		$this->load->model('model_customers');
		$this->load->model('model_addresses');
		$this->load->model('model_contacts_info');
		$this->load->model('model_taxes');
		$this->load->model('model_users');

		$this->load->library('enumstypeaddresscustomers');
		$this->load->library('enumstypestatusobject');
		$this->load->library('enumstabletypeid');
		$this->load->library('enumstypecustomer');
		$this->load->library('helper');
	}

	public function index()
	{
		if (!in_array('viewCustomer', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->render_template('customers/index', $this->data);
	}

	public function fetchCustomersData()
	{
		$active = $this->enumstypestatusobject->enumsNum['active'];
		$result = array('data' => array());
		$user_id = $this->session->userdata('id');

		if (in_array('viewAllOrdersCustomers', $this->permission)) {
			$data = $this->model_customers->getCustomerListData($active);
		} else{
			$data = $this->model_customers->getCustomerListDataByUser($active, $user_id);
		}

		$type_billing = $this->enumstypeaddresscustomers->getEnumsNum['Billing'];
		$table_type_id = $this->enumstabletypeid->enumsNum['Customer'];


		foreach ($data as $key => $value) {
			// button
			$buttons = '';
			if (in_array('updateCustomer', $this->permission)) {
				$buttons .= '<button  type="button" onclick=window.location.href="' . base_url('customers/edit/' . $value['id']) . '"  class="label-base-icon-doc edit-doc"></button>';
			}

			if (in_array('deleteCustomer', $this->permission)) {
				$buttons .= ' <button type="button" class="label-base-icon-doc remove-doc" onclick="removeFunc(' . $value['id'] . ')" data-toggle="modal"  data-toggle="modal" data-target="#removeModal"></button>';
			}
			$customers_addresses_billing_data = $this->model_addresses->getCustomersAddressByTypeData($value['id'], $type_billing, $table_type_id);
			$customers_data_contact_info = $this->model_contacts_info->getContactInfoData($value['id'], $active);
			$typecustomer = $this->enumstypecustomer->getEnumsStr[$value['type_customer_id']];
		
			if($value['user_id']){
				$user = $this->model_users->getUserData($value['user_id']);
				$user_name = $user['username'];
			} else{
				$user_name = "n/a";
			}

			$result['data'][$key] = array(
				$value['id'],
				$value['name'],
				$customers_addresses_billing_data['city'],
				$customers_addresses_billing_data['country'],
				$customers_data_contact_info['phone_number'],
				$typecustomer,
				$user_name,
				$buttons
			);
		} // /foreach
		echo json_encode($result);
	}

	public function create()
	{
		if (!in_array('createCustomer', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->form_validation->set_rules('business_name', '(Business Name)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('tax_id', '(Tax Id No.)', 'trim|max_length[64]');
		$this->form_validation->set_rules('type_customer', '(Type customer)', 'trim|max_length[64]');
		$this->form_validation->set_rules('type_customer_id', '(Type customer)', 'trim|max_length[64]');
		$this->form_validation->set_rules('user_id', '(User id)', 'trim|max_length[64]');

		$this->form_validation->set_rules('contact_name', '(Customer Name)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('email', '(Email)', 'trim|required|valid_email|max_length[64]');
		$this->form_validation->set_rules('phone_number', '(Phone No.)', 'trim|required|max_length[64]');

		//address BillING
		$this->form_validation->set_rules('bil_address', '(Address)', 'trim|required|max_length[128]');
		$this->form_validation->set_rules('bil_city', '(City)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('bil_state', '(State)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('bil_country', '(Country)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('bil_postal_code', '(Postal Code)', 'trim|required|max_length[64]');

		//address SHIPPING
		$this->form_validation->set_rules('ship_address', '(Address)', 'trim|required|max_length[128]');
		$this->form_validation->set_rules('ship_city', '(City)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('ship_state', '(State)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('ship_country', '(Country)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('ship_postal_code', '(Postal Code)', 'trim|required|max_length[64]');

		if ($this->form_validation->run() == TRUE) {
			$type_billing = $this->enumstypeaddresscustomers->getEnumsNum['Billing'];
			$type_shipping = $this->enumstypeaddresscustomers->getEnumsNum['Shipping'];
			$active = $this->enumstypestatusobject->enumsNum['active'];
			$table_type_id = $this->enumstabletypeid->enumsNum['Customer'];
			// true case
			$data_customer = array(
				'name' => $this->input->post('business_name'),
				'tax_id' => $this->input->post('tax_id'),
				'type_customer_id' => $this->input->post('type_customer_id'),
				'user_id' => $this->input->post('user_id_value'),
				'active' => $active
			);

			$data_contact_info = array(
				'item_table_id' => '',
				'contact_name' => $this->input->post('contact_name'),
				'email' => $this->input->post('email'),
				'phone_number' => $this->input->post('phone_number'),
				'active' => $active,
				'table_type_id' => $table_type_id,
			);

			$data_address_ship = array(
				'item_table_id' => '',
				'address' => $this->input->post('ship_address'),
				'city' => $this->input->post('ship_city'),
				'state' => $this->input->post('ship_state'),
				'country' => $this->input->post('ship_country'),
				'postal_code' => $this->input->post('ship_postal_code'),
				'type_address_id' => $type_shipping,
				'active' => $active,
				'table_type_id' => $table_type_id,
			);

			$data_address_bil = array(
				'item_table_id' => '',
				'address' => $this->input->post('bil_address'),
				'city' => $this->input->post('bil_city'),
				'state' => $this->input->post('bil_state'),
				'country' => $this->input->post('bil_country'),
				'postal_code' => $this->input->post('bil_postal_code'),
				'type_address_id' => $type_billing,
				'active' => $active,
				'table_type_id' => $table_type_id,
			);

			$data =  array(
				'data_customer' => $data_customer,
				'data_contact_info' => $data_contact_info,
				'data_address_ship' => $data_address_ship,
				'data_address_bil' => $data_address_bil,
			);

			$create = $this->model_customers->create($data);
			if ($create) {
				if ($create) {
					$this->session->set_flashdata('success', 'Successfully created the billing address');
				}
				$this->session->set_flashdata('success', 'Successfully created');
				redirect('customers/', 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('customers/create', 'refresh');
			}
		} else {
			$this->data['taxes'] = $this->model_taxes->getActiveTaxes();
			$this->data['typecustomer'] = $this->enumstypecustomer->getEnumsStr;
			$this->data['users'] = $this->model_users->getUserData();
			$user_id = $this->session->userdata('id');
			$this->data['user_id'] = $user_id;
			$this->data['user_id_value'] = $user_id;
			
			$this->render_template('customers/create', $this->data);
		}
	}

	public function edit($id = null)
	{
		if (!in_array('updateCustomer', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$active = $this->enumstypestatusobject->enumsNum['active'];
		$table_type_id = $this->enumstabletypeid->enumsNum['Customer'];
		if ($id) {
			$this->form_validation->set_rules('business_name', '(Business Name)', 'trim|required');
			$this->form_validation->set_rules('tax_id', '(Tax Id No.)', 'trim');
			$this->form_validation->set_rules('type_customer', '(Type customer)', 'trim|max_length[64]');
			$this->form_validation->set_rules('type_customer_id', '(Type customer)', 'trim|max_length[64]');
			$this->form_validation->set_rules('user_id', '(User id)', 'trim|max_length[64]');

			$this->form_validation->set_rules('contact_name', '(Customer Name)', 'trim|required');
			$this->form_validation->set_rules('email', '(Email)', 'trim|required|valid_email');
			$this->form_validation->set_rules('phone_number', '(Phone No.)', 'trim|required');

			//address BillING
			$this->form_validation->set_rules('bil_address', '(Address)', 'trim|required');
			$this->form_validation->set_rules('bil_city', '(City)', 'trim|required');
			$this->form_validation->set_rules('bil_state', '(State)', 'trim|required');
			$this->form_validation->set_rules('bil_country', '(Country)', 'trim|required');
			$this->form_validation->set_rules('bil_postal_code', '(Postal Code)', 'trim|required');

			//address SHIPPING
			$this->form_validation->set_rules('ship_address', '(Address)', 'trim|required');
			$this->form_validation->set_rules('ship_city', '(City)', 'trim|required');
			$this->form_validation->set_rules('ship_state', '(State)', 'trim|required');
			$this->form_validation->set_rules('ship_country', '(Country)', 'trim|required');
			$this->form_validation->set_rules('ship_postal_code', '(Postal Code)', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$type_billing = $this->enumstypeaddresscustomers->getEnumsNum['Billing'];
				$type_shipping = $this->enumstypeaddresscustomers->getEnumsNum['Shipping'];
				// true case
				$data_customer = array(
					'name' => $this->input->post('business_name'),
					'tax_id' => $this->input->post('tax_id'),
					'type_customer_id' => $this->input->post('type_customer_id'),
					'user_id' => $this->input->post('user_id_value'),
					'active' => $active
				);

				$data_contact_info = array(
					'item_table_id' => $id,
					'contact_name' => $this->input->post('contact_name'),
					'email' => $this->input->post('email'),
					'phone_number' => $this->input->post('phone_number'),
					'active' => $active,
					'table_type_id' => $table_type_id,
				);

				$data_address_ship = array(
					'item_table_id' => $id,
					'address' => $this->input->post('ship_address'),
					'city' => $this->input->post('ship_city'),
					'state' => $this->input->post('ship_state'),
					'country' => $this->input->post('ship_country'),
					'postal_code' => $this->input->post('ship_postal_code'),
					'type_address_id' => $type_shipping,
					'active' => $active,
					'table_type_id' => $table_type_id,
				);

				$data_address_bil = array(
					'item_table_id' => $id,
					'address' => $this->input->post('bil_address'),
					'city' => $this->input->post('bil_city'),
					'state' => $this->input->post('bil_state'),
					'country' => $this->input->post('bil_country'),
					'postal_code' => $this->input->post('bil_postal_code'),
					'type_address_id' => $type_billing,
					'active' => $active,
					'table_type_id' => $table_type_id,
				);

				$data =  array(
					'data_customer' => $data_customer,
					'data_contact_info' => $data_contact_info,
					'data_address_ship' => $data_address_ship,
					'data_address_bil' => $data_address_bil,
				);

				$update = $this->model_customers->edit($data, $id);

				if ($update) {
					$this->session->set_flashdata('success', 'Successfully updated the billing address');
				}
				$this->session->set_flashdata('success', 'Successfully updated');
				redirect('customers/', 'refresh');
			} else {
				$type_billing = $this->enumstypeaddresscustomers->getEnumsNum['Billing'];
				$type_shipping = $this->enumstypeaddresscustomers->getEnumsNum['Shipping'];

				$this->data['taxes'] = $this->model_taxes->getActiveTaxes();
				$this->data['typecustomer'] = $this->enumstypecustomer->getEnumsStr;
				// false case
				$customers_addresses_billing_data = $this->model_addresses->getCustomersAddressByTypeData($id, $type_billing, $table_type_id);
				$this->data['customers_addresses_billing_data'] = $customers_addresses_billing_data;

				$customers_addresses_shipping_data = $this->model_addresses->getCustomersAddressByTypeData($id, $type_shipping, $table_type_id);
				$this->data['customers_addresses_shipping_data'] = $customers_addresses_shipping_data;

				$customers_data_contact_info = $this->model_contacts_info->getContactInfoData($id, $active);
				$this->data['customers_data_contact_info'] = $customers_data_contact_info;

				$customer_data = $this->model_customers->getCustomerData($id);
				$this->data['users'] = $this->model_users->getUserData();

				$this->data['user_id_value'] = $customer_data['user_id'];

				$this->data['customer_data'] = $customer_data;
				$this->render_template('customers/edit', $this->data);
			}
		}
	}

	public function delete()
	{
		if (!in_array('deleteCustomer', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$id = $this->input->post('customer_id');
		if ($id) {
			$arr_check = $this->helper->parse_answer_links($this->model_customers->exist_links($id));
			if ($arr_check) {
				$response['success'] = false;
				$response['messages'] = "This item has some links for " . $arr_check . ' cannot be removed now!';
			} else {
				$delete = $this->model_customers->deactive($id);
				if ($delete == true) {
					$response['success'] = true;
					$response['messages'] = "Successfully removed";
				} else {
					$response['success'] = false;
					$response['messages'] = "Error in the database while removing the item";
				}
			}

			echo json_encode($response);
		}
	}
}
