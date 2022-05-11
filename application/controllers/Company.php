<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Company extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->data['page_title'] = 'Company';
		$this->load->model('model_company');
		$this->load->model('model_contacts_info');
		$this->load->model('model_addresses');
		$this->load->library('enumstypeaddresscustomers');
		$this->load->library('enumstypestatusobject');
		$this->load->library('enumstabletypeid');
		$this->load->library('enumstypenotificationid');
		$this->load->model('model_notification_settings');
		$this->load->model('model_currencies');
		
	}

	/* 
    * It redirects to the company page and displays all the company information
    * It also updates the company information into the database if the 
    * validation for each input field is successfully valid
    */
	public function index()
	{
		if (!in_array('updateCompany', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$count = $this->model_company->countTotalCompanies();
		if ($count == 0) {
			$this->create();
		} else {
			$data_company = $this->model_company->getCompanyData();
			if ($data_company) {
				$this->update($data_company['id']);
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('company/edit', 'refresh');
			}
		}
	}
	public function create()
	{
		$this->form_validation->set_rules('business_name', 'Business name', 'trim|required');
		$this->form_validation->set_rules('vat_charge_value', 'Vat Charge', 'trim|integer');
		$this->form_validation->set_rules('smtp_host', 'Smtp Host', 'trim');
		$this->form_validation->set_rules('smtp_pass', 'Smtp Password', 'trim');
		$this->form_validation->set_rules('smtp_port', 'Smtp Port', 'trim');
		$this->form_validation->set_rules('smtp_user', 'Smtp email', 'trim');
		$this->form_validation->set_rules('dealer_discount',  'Discount', 'trim');
		$this->form_validation->set_rules('currency_id',  'Currency', 'trim');
		$this->form_validation->set_rules('cash_discount',  'Cash Discount', 'trim');

		$this->form_validation->set_rules('contact_name', '(Customer Name)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('email', '(Email)', 'trim|required|valid_email|max_length[64]');
		$this->form_validation->set_rules('phone_number', '(Phone No.)', 'trim|required|max_length[64]');

		//address BillING
		$this->form_validation->set_rules('bil_address', '(Address)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('bil_city', '(City)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('bil_state', '(State)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('bil_country', '(Country)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('bil_postal_code', '(Postal Code)', 'trim|required|max_length[64]');

		//address SHIPPING
		$this->form_validation->set_rules('ship_address', '(Address)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('ship_city', '(City)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('ship_state', '(State)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('ship_country', '(Country)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('ship_postal_code', '(Postal Code)', 'trim|required|max_length[64]');

		if ($this->form_validation->run() == TRUE) {
			$active = $this->enumstypestatusobject->enumsNum['active'];

			$type_billing = $this->enumstypeaddresscustomers->getEnumsNum['Billing'];
			$type_shipping = $this->enumstypeaddresscustomers->getEnumsNum['Shipping'];
			$table_type_id = $this->enumstabletypeid->enumsNum['Company'];

			$data_company = array(
				'business_name' => $this->input->post('business_name'),
				'vat_charge_value' => $this->input->post('vat_charge_value'),
				'smtp_host' => $this->input->post('smtp_host'),
				'smtp_pass' => $this->input->post('smtp_pass'),
				'smtp_port' => $this->input->post('smtp_port'),
				'smtp_user' => $this->input->post('smtp_user'),
				'send_email' => $this->input->post('send_email'),
				'dealer_discount' => $this->input->post('dealer_discount'),
				'cash_discount' => $this->input->post('cash_discount'),
				'currency_id' => $this->input->post('currency_id'),
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
				'data_company' => $data_company,
				'data_contact_info' => $data_contact_info,
				'data_address_ship' => $data_address_ship,
				'data_address_bil' => $data_address_bil,
			);

			$update = $this->model_company->create($data);
			if ($update == true) {
				$this->session->set_flashdata('success', 'Successfully created');
				redirect('company/', 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('company/create', 'refresh');
			}
		} else {
			$this->data['currencies'] = $this->model_currencies->getActiveCurrencies();
			$this->render_template('company/create', $this->data);
		}
	}


	public function update($id_company)
	{
		$this->form_validation->set_rules('business_name', 'Business name', 'trim|required');
		$this->form_validation->set_rules('vat_charge_value', 'Vat Charge', 'trim|integer');
		$this->form_validation->set_rules('smtp_host', 'Smtp Host', 'trim');
		$this->form_validation->set_rules('smtp_pass', 'Smtp Password', 'trim');
		$this->form_validation->set_rules('smtp_port', 'Smtp Port', 'trim');
		$this->form_validation->set_rules('smtp_user', 'Smtp email', 'trim');
		$this->form_validation->set_rules('dealer_discount',  'Discount', 'trim');
		$this->form_validation->set_rules('cash_discount',  'Cash Discount', 'trim');
		$this->form_validation->set_rules('currency_id',  'Currency', 'trim');
		
	
		$this->form_validation->set_rules('contact_name', '(Customer Name)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('email', '(Email)', 'trim|required|valid_email|max_length[64]');
		$this->form_validation->set_rules('phone_number', '(Phone No.)', 'trim|required|max_length[64]');

		//address BillING
		$this->form_validation->set_rules('bil_address', '(Address)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('bil_city', '(City)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('bil_state', '(State)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('bil_country', '(Country)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('bil_postal_code', '(Postal Code)', 'trim|required|max_length[64]');

		//address SHIPPING
		$this->form_validation->set_rules('ship_address', '(Address)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('ship_city', '(City)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('ship_state', '(State)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('ship_country', '(Country)', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('ship_postal_code', '(Postal Code)', 'trim|required|max_length[64]');

		if ($this->input->post('notification_email[]')) {
            $count = count($this->input->post('notification_email[]'));
            if ($count > 0) {
                $this->form_validation->set_rules('notification_email[]', '(Notification email)', 'trim|required|valid_email|max_length[64]');
				$this->form_validation->set_rules('type_notification_id[]', '(Type notification)', 'trim|required|max_length[64]');
            }
        }

		$table_type_id = $this->enumstabletypeid->enumsNum['Company'];

		if ($this->form_validation->run() == TRUE) {
			$active = $this->enumstypestatusobject->enumsNum['active'];

			$type_billing = $this->enumstypeaddresscustomers->getEnumsNum['Billing'];
			$type_shipping = $this->enumstypeaddresscustomers->getEnumsNum['Shipping'];

			$data_company = array(
				'business_name' => $this->input->post('business_name'),
				'vat_charge_value' => $this->input->post('vat_charge_value'),
				'smtp_host' => $this->input->post('smtp_host'),
				'smtp_pass' => $this->input->post('smtp_pass'),
				'smtp_port' => $this->input->post('smtp_port'),
				'smtp_user' => $this->input->post('smtp_user'),
				'send_email' => $this->input->post('send_email'),
				'dealer_discount' => $this->input->post('dealer_discount'),
				'cash_discount' => $this->input->post('cash_discount'),
				'currency_id' => $this->input->post('currency_id'),
			);

			$data_contact_info = array(
				'item_table_id' => $id_company,
				'contact_name' => $this->input->post('contact_name'),
				'email' => $this->input->post('email'),
				'phone_number' => $this->input->post('phone_number'),
				'active' => $active,
				'table_type_id' => $table_type_id,
			);

			$data_address_ship = array(
				'item_table_id' => $id_company,
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
				'item_table_id' => $id_company,
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
				'data_company' => $data_company,
				'data_contact_info' => $data_contact_info,
				'data_address_ship' => $data_address_ship,
				'data_address_bil' => $data_address_bil,
			);

			$update = $this->model_company->update($data, $id_company);
			if ($update == true) {
				$this->session->set_flashdata('success', 'Successfully updated');
				redirect('company/', 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('company/edit', 'refresh');
			}
		} else {
			$active = $this->enumstypestatusobject->enumsNum['active'];
			$type_billing = $this->enumstypeaddresscustomers->getEnumsNum['Billing'];
			$type_shipping = $this->enumstypeaddresscustomers->getEnumsNum['Shipping'];

			$types_notifications = $this->enumstypenotificationid->enumsStr;
			$this->data['types_notifications']  = $types_notifications;
			
			$this->data['currencies'] = $this->model_currencies->getActiveCurrencies();
			// false case
			$customers_addresses_billing_data = $this->model_addresses->getCustomersAddressByTypeData($id_company, $type_billing, $table_type_id);
			$this->data['company_addresses_billing_data'] = $customers_addresses_billing_data;

			$customers_addresses_shipping_data = $this->model_addresses->getCustomersAddressByTypeData($id_company, $type_shipping, $table_type_id);
			$this->data['company_addresses_shipping_data'] = $customers_addresses_shipping_data;

			$customers_data_contact_info = $this->model_contacts_info->getContactInfoData($id_company, $active);
			$this->data['company_data_contact_info'] = $customers_data_contact_info;

			$this->data['company_data'] = $this->model_company->getCompanyData($id_company);
			$this->render_template('company/edit', $this->data);
		}
	}

	function getTypesNotification()
	{
		$result = array('data' => array());
		$types_notifications = $this->enumstypenotificationid->enumsStr;

		foreach ($types_notifications as $key => $value) {
			$result['data'][$key] = array(
				'id' =>  $key,
				'value'  => $types_notifications[$key],
			);
		} 
		echo json_encode($result);
	}

	function getNotificationSettings()
	{
		$result = array('data' => array());
		$settings_data = $this->model_notification_settings->getNotificationSettingsData();

		foreach ($settings_data as $key => $value) {
			$result['data'][$key] = array(
				'id' =>  $value['id'],
				'type_notification_id'  => $value['type_notification_id'],
				'email'  => $value['email'],
			);
		} // /foreach
		echo json_encode($result);
	}
}
