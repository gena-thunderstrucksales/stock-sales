<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Payments extends Admin_Controller
{
	var $id_payment;
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->data['page_title'] = 'Payments';

		$this->load->model('model_payments');
		$this->load->model('model_orders');
		$this->load->model('model_users');
		$this->load->library('enumstypepayments');
		$this->load->library('enumstypestatusobject');
		$this->load->library('enumstabletypeid');
		$this->load->model('model_customers');
	}

	/* 
	* It only redirects to the manage payment page
	*/
	public function index()
	{
		if (!in_array('viewPayment', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->data['page_title'] = 'ALL Payments';
		$this->render_template('payments/index', $this->data);
	}

	/*
	* Fetches the payments data from the payments table 
	* this function is called from the datatable ajax function
	*/
	public function fetchPaymentsData()
	{
		$result = array('data' => array());
		$active = $this->enumstypestatusobject->enumsNum['active'];
		$user_id = $this->session->userdata('id');

		if (in_array('viewAllOrdersCustomers', $this->permission)) {
			$data = $this->model_payments->getPaymentsData($active);
		}else{
			$data = $this->model_payments->getPaymentsDataByUser($active, $user_id );
		}

		
		foreach ($data as $key => $value) {
			$date = date('Y-m-d', $value['date_time']);
			$time = date('h:i a', $value['date_time']);
			$id =  $value['id'];
			$date_time = $date . ' ' . $time;
			$amount = '<sup>$</sup>'.$value['total_payment'];
			$number_order = $value['order_id'];
			$customer_name = $this->model_customers->getCustomerData($value['customer_id'])['name'];

			// button
			$buttons = '';
			if (in_array('updatePayment', $this->permission)) {
				$buttons .= ' <button type="button"  onclick=window.location.href="' . base_url('payments/update/' . $id) . '" class="label-base-icon-doc edit-doc"></button>';
			}
			if (in_array('deletePayment', $this->permission)) {
				$buttons .= ' <button type="button" class="label-base-icon-doc remove-doc" onclick="removeFunc(' . $id . ')" data-toggle="modal" data-target="#removeModal"></button>';
			}

			$result['data'][$key] = array(
				$id,
				$date_time,
				$customer_name,
				$amount,
				$number_order,
				$buttons
			);
		} // /foreach
		echo json_encode($result);
	}

	function getInfoOrder()
	{
		$order_id = $this->input->post('order_id');
		$order_data = $this->model_orders->getOrdersDataById($order_id);
		echo json_encode($order_data);
	}

	function  setListOrders (){
		$active = $this->enumstypestatusobject->enumsNum['active'];
		$customer_id = $this->input->post('customer_id');

		$list_orders = $this->model_orders->getOrdersDateNumber($active, $customer_id);

		echo json_encode($list_orders);
	}

	/*
	* If the validation is not valid, then it redirects to the create page.
	* If the validation for each input field is valid then it inserts the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function create()
	{
		if (!in_array('createPayment', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->data['page_title'] = 'Add Payment';

		$this->form_validation->set_rules('total_payment', 'Amount', 'trim|required');
		$this->form_validation->set_rules('order_id', 'Order', 'trim|required');
		$this->form_validation->set_rules('type_payment_id', 'Type', 'trim|required');
		$this->form_validation->set_rules('customer_id', 'Type', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) {
			$table_type_id = $this->enumstabletypeid->enumsNum['Payments'];

			$user_id = $this->session->userdata('id');
			$data_header = array(
				'user_id' => $user_id,
				'date_time' => strtotime(date('Y-m-d h:i:s a')),
				'order_id' => $this->input->post('order_id'),
				'customer_id' => $this->input->post('customer_id'),
				'type_payment_id' => $this->input->post('type_payment_id'),
				'total_payment' => $this->input->post('total_payment'),
			);

			$data_report_payments_orders = array(
				'user_id' => $user_id,
				'date_time' => $data_header['date_time'],
				'table_type_id' => $table_type_id,
				'item_table_id' => "",
				'order_id' => $this->input->post('order_id'),
				'customer_id' =>  $data_header['customer_id'],
				'balance' => -$data_header['total_payment'],
			);

			$data =  array(
				'data_header' => $data_header,
				'data_report_payments_orders' => $data_report_payments_orders,
			);
			$create = $this->model_payments->create($data);

			if ($create) {
				$this->session->set_flashdata('success', 'Successfully created');
				redirect('payments/', 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('payments/create/', 'refresh');
			}
		} else {
			$active = $this->enumstypestatusobject->enumsNum['active'];
			$customer_id = $this->input->post('customer_id');

			if (in_array('viewAllOrdersCustomers', $this->permission)) {
				$this->data['customers'] = $this->model_customers->getCustomerListData($active);
			}else{
				$user_id = $this->session->userdata('id');
				$this->data['customers'] = $this->model_customers->getCustomerListDataByUser($active, $user_id);
			}
			$this->data['username'] = $this->session->userdata('username');

			$type_payments = $this->enumstypepayments->enumsStr;
			$this->data['type_payments'] = $type_payments;
			$list_orders = $this->model_orders->getOrdersDateNumber($active, $customer_id);
			$this->data['list_orders'] = $list_orders;

			$this->render_template('payments/create', $this->data);
		}
	}

	/*
	* If the validation is not valid, then it redirects to the edit payments page 
	* If the validation is successfully then it updates the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function update($id)
	{
		if (!in_array('updatePayment', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		if (!$id) {
			redirect('dashboard', 'refresh');
		}
		$this->data['page_title'] = 'Update Payment';
		$this->form_validation->set_rules('total_payment', 'Amount', 'trim|required');
		$this->form_validation->set_rules('order_id', 'Order', 'trim|required');
		$this->form_validation->set_rules('type_payment_id', 'Type', 'trim|required');
		$this->form_validation->set_rules('customer_id', 'Type', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$table_type_id = $this->enumstabletypeid->enumsNum['Payments'];
			$user_id = $this->session->userdata('id');

			$data_header = array(
				'id' => $id,
				'user_id' => $user_id,
				'date_time' => $this->input->post('doc_date_doc_time'),
				'order_id' => $this->input->post('order_id'),
				'customer_id' => $this->input->post('customer_id'),
				'type_payment_id' => $this->input->post('type_payment_id'),
				'total_payment' => $this->input->post('total_payment'),
			);

			$data_report_payments_orders = array(
				'user_id' => $user_id,
				'date_time' => $data_header['date_time'],
				'table_type_id' => $table_type_id,
				'item_table_id' => $id,
				'customer_id' =>  $data_header['customer_id'],
				'order_id' => $this->input->post('order_id'),
				'balance' => -$data_header['total_payment'],
			);

			$data =  array(
				'data_header' => $data_header,
				'data_report_payments_orders' => $data_report_payments_orders,
			);

			$update = $this->model_payments->update($id, $data);
			if ($update == true) {
				$this->session->set_flashdata('success', 'Successfully updated');
				redirect('payments/update/' . $id, 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('payments/update/' . $id, 'refresh');
			}
		} else {
			$active = $this->enumstypestatusobject->enumsNum['active'];

			$type_payments = $this->enumstypepayments->enumsStr;
			$this->data['type_payments'] = $type_payments;
	

			if (in_array('viewAllOrdersCustomers', $this->permission)) {
				$this->data['customers'] = $this->model_customers->getCustomerListData($active);
			}else{
				$user_id = $this->session->userdata('id');
				$this->data['customers'] = $this->model_customers->getCustomerListDataByUser($active, $user_id);
			}

			$payment_header = $this->model_payments->getPaymentsDataById($id);
			$list_orders = $this->model_orders->getOrdersDateNumber($active, $payment_header['customer_id']);
			$this->data['list_orders'] = $list_orders;
			$this->data['payment_header']['username'] = $this->model_users->getUserData($payment_header['user_id'])['username'];

			$this->data['payment_header']['customer_id'] = $payment_header['customer_id'];
			$this->data['payment_header']['id'] = $payment_header['id'];
			$this->data['payment_header']['doc_date'] =  date('Y-m-d', $payment_header['date_time']);
			$this->data['payment_header']['doc_time'] =  date('h:i a', $payment_header['date_time']);
			$this->data['payment_header']['order_id'] =  $payment_header['order_id'];
			$this->data['payment_header']['type_payment_id'] =  $payment_header['type_payment_id'];
			$this->data['payment_header']['total_payment'] =  $payment_header['total_payment'];
			$this->data['payment_header']['doc_date_doc_time'] =  $payment_header['date_time'];

			$this->render_template('payments/edit', $this->data);
		}
	}

	/*
	* It removes the data from the database
	* and it returns the response into the json format
	*/
	public function remove()
	{
		if (!in_array('deletePayment', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$payment_id = $this->input->post('payment_id');
		$response = array();
		if ($payment_id) {
			$delete = $this->model_payments->deactive($payment_id);
			if ($delete == true) {
				$response['success'] = true;
				$response['messages'] = "Successfully removed";
			} else {
				$response['success'] = false;
				$response['messages'] = "Error in the database while removing the payment information";
			}
		} else {
			$response['success'] = false;
			$response['messages'] = "Refersh the page again!!";
		}
		echo json_encode($response);
	}

}
