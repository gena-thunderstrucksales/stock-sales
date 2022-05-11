<?php

defined('BASEPATH') or exit('No direct script access allowed');
include_once(__DIR__ . "/Orders.php");

class OrdersStatus extends Orders
{
	var $id_ordersStatus;
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->data['page_title'] = 'OrdersStatus';

		$this->load->model('model_orders_status');
		$this->load->model('model_orders');
		$this->load->model('model_users');
		$this->load->library('enumstypestatusobject');
		$this->load->library('enumstabletypeid');
		$this->load->library('enumsorderstatus');
		$this->load->model('model_customers');
	}

	/* 
	* It only redirects to the manage ordersStatus page
	*/
	public function index()
	{
		if (!in_array('viewOrdersStatus', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->data['page_title'] = 'ALL Orders Status';
		$this->render_template('ordersStatus/index', $this->data);
	}

	/*
	* Fetches the ordersStatus data from the ordersStatus table 
	* this function is called from the datatable ajax function
	*/
	public function fetchOrdersStatusData()
	{
		$result = array('data' => array());
		$active = $this->enumstypestatusobject->enumsNum['active'];
		$data = $this->model_orders_status->getOrdersStatusData($active);

		foreach ($data as $key => $value) {
			$date = date('Y-m-d', $value['date_time']);
			$time = date('h:i a', $value['date_time']);
			$id =  $value['id'];
			$date_time = $date . ' ' . $time;
			$number_order = $value['order_id'];
			$customer_name = $this->model_customers->getCustomerData($value['customer_id'])['name'];

			// button
			$buttons = '';
			if (in_array('updateOrdersStatus', $this->permission)) {
				$buttons .= ' <button type="button"  onclick=window.location.href="' . base_url('ordersStatus/update/' . $id) . '" class="label-base-icon-doc edit-doc"></button>';
			}
			if (in_array('deleteOrdersStatus', $this->permission)) {
				$buttons .= ' <button type="button" class="label-base-icon-doc remove-doc" onclick="removeFunc(' . $id . ')" data-toggle="modal" data-target="#removeModal"></button>';
			}

			$labelStatus = $this->enumsorderstatus->enumsStr[$value['type_status_id']];
			$order_status = $value['type_status_id'];
			if ($order_status == 0) {
				$paid_status = '<a class="label-base-status label-panding"></a> <span>' . $labelStatus . '</span> ';
			} elseif ($order_status == 1) {
				$paid_status = '<a class="label-base-status label-approved"></a> <span>' . $labelStatus . '</span> ';
			} elseif ($order_status == 2) {
				$paid_status = '<a class="label-base-status label-shipped"></a> <span>' . $labelStatus . '</span> ';
			} elseif ($order_status == 3) {
				$paid_status = '<a class="label-base-status label-delivered"></a> <span >' . $labelStatus . '</span>';
			} elseif ($order_status == 4) {
				$paid_status = '<a class="label-base-status label-canceled"></a> <span>' . $labelStatus . '</span> ';
			} else {
				$paid_status = '<span class="">n/a</span> <a class="label-base-status "></a>';
			}

			$result['data'][$key] = array(
				$id,
				$date_time,
				$customer_name,
				$number_order,
				$paid_status,
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

	function  setListOrders()
	{
		$active = $this->enumstypestatusobject->enumsNum['active'];
		$customer_id = $this->input->post('customer_id');

		$list_orders = $this->model_orders->getOrdersDateNumber($active, $customer_id);

		echo json_encode($list_orders);
	}

	function  onChangeOrder()
	{
		$result = array();
		$order_id = $this->input->post('order_id');
		$active = $this->enumstypestatusobject->enumsNum['active'];
		$order_status = $this->model_orders_status->getOrdersStatusDataByIdOrder($order_id, strtotime(date('Y-m-d h:i:s a')), $active);

		$result['order_status'] = $order_status['type_status_id'];
		$result['updateOrderSetComplete'] = in_array('updateOrderSetComplete', $this->permission);
		$result['updateOrderSetApproved'] = in_array('updateOrderSetApproved', $this->permission);

		echo json_encode($result);
	}

	/*
	* If the validation is not valid, then it redirects to the create page.
	* If the validation for each input field is valid then it inserts the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function create()
	{
		if (!in_array('createOrdersStatus', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->data['page_title'] = 'Add Orders Status';

		$this->form_validation->set_rules('order_id', 'Order', 'trim|required');
		$this->form_validation->set_rules('type_status_id', 'Type', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$table_type_id = $this->enumstabletypeid->enumsNum['OrdersStatus'];
			$type_status_id = $this->input->post('type_status_id');

			$user_id = $this->session->userdata('id');
			$order_id = $this->input->post('order_id');
			$data_header = array(
				'user_id' => $user_id,
				'date_time' => strtotime(date('Y-m-d h:i:s a')),
				'order_id' => $order_id,
				'customer_id' => $this->input->post('customer_id'),
				'type_status_id' => $type_status_id,
				'table_type_id' => $table_type_id,
			);

			$data =  array(
				'data_header' => $data_header,
			);
			$create = $this->model_orders_status->create($data);

			if ($create) {
				$this->session->set_flashdata('success', 'Successfully created');
				$approved = $this->enumsorderstatus->enumsNum['Approved'];

				if ($type_status_id == $approved) {
					$arr_data_send_order =  array(
						'order_id' => $order_id,
						'user_id' => $user_id,
						'arr_type_notification_id' => array(
							$this->enumstypenotificationid->enumsNum['Admin'],
							$this->enumstypenotificationid->enumsNum['Accounting']
						),
					);

					if ($this->send_oredr_by_email($arr_data_send_order)) {
						$this->session->set_flashdata('success', 'Successfully, email sent');
					} else {
						$this->session->set_flashdata('errors', 'Error occurred, email!!');
					}
				}

				redirect('ordersStatus/', 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('ordersStatus/create/', 'refresh');
			}
		} else {
			$active = $this->enumstypestatusobject->enumsNum['active'];
			$customer_id = $this->input->post('customer_id');
			$this->data['username'] = $this->session->userdata('username');
			$this->data['customers'] = $this->model_customers->getCustomerListData($active);

			//$this->data['updateOrderSetComplete'] = in_array('updateOrderSetComplete', $this->permission) ? 'true' : 'false';
			//$this->data['updateOrderSetApproved'] = in_array('updateOrderSetApproved', $this->permission) ? 'true' : 'false';

			$type_orders_status = $this->enumsorderstatus->enumsStr;
			$this->data['type_orders_status'] = $type_orders_status;
			$list_orders = $this->model_orders->getOrdersDateNumber($active, $customer_id);
			$this->data['list_orders'] = $list_orders;

			$this->render_template('ordersStatus/create', $this->data);
		}
	}

	/*
	* If the validation is not valid, then it redirects to the edit ordersStatus page 
	* If the validation is successfully then it updates the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function update($id)
	{
		if (!in_array('updateOrdersStatus', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		if (!$id) {
			redirect('dashboard', 'refresh');
		}
		$this->data['page_title'] = 'Update Orders Status';
		$this->form_validation->set_rules('order_id', 'Order', 'trim|required');
		$this->form_validation->set_rules('type_status_id', 'Type', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$table_type_id = $this->enumstabletypeid->enumsNum['OrdersStatus'];
			$user_id = $this->session->userdata('id');

			$data_header = array(
				'id' => $id,
				'user_id' => $user_id,
				'date_time' => $this->input->post('doc_date_doc_time'),
				'order_id' => $this->input->post('order_id'),
				'customer_id' => $this->input->post('customer_id'),
				'type_status_id' => $this->input->post('type_status_id'),
				'table_type_id' => $table_type_id,
			);

			$data =  array(
				'data_header' => $data_header
			);

			$update = $this->model_orders_status->update($id, $data);
			if ($update == true) {
				$this->session->set_flashdata('success', 'Successfully updated');
				redirect('ordersStatus/update/' . $id, 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('ordersStatus/update/' . $id, 'refresh');
			}
		} else {
			$active = $this->enumstypestatusobject->enumsNum['active'];

			$type_orders_status = $this->enumsorderstatus->enumsStr;
			$this->data['type_orders_status'] = $type_orders_status;

			$this->data['customers'] = $this->model_customers->getCustomerListData($active);

			$orders_status_header = $this->model_orders_status->getOrdersStatusDataById($id);
			$list_orders = $this->model_orders->getOrdersDateNumber($active, $orders_status_header['customer_id']);
			$this->data['list_orders'] = $list_orders;
			$this->data['orders_status_header']['username'] = $this->model_users->getUserData($orders_status_header['user_id'])['username'];

			$this->data['orders_status_header']['customer_id'] = $orders_status_header['customer_id'];
			$this->data['orders_status_header']['id'] = $orders_status_header['id'];
			$this->data['orders_status_header']['doc_date'] =  date('Y-m-d', $orders_status_header['date_time']);
			$this->data['orders_status_header']['doc_time'] =  date('h:i a', $orders_status_header['date_time']);
			$this->data['orders_status_header']['order_id'] =  $orders_status_header['order_id'];
			$this->data['orders_status_header']['type_status_id'] =  $orders_status_header['type_status_id'];

			$this->data['orders_status_header']['doc_date_doc_time'] =  $orders_status_header['date_time'];

			$this->render_template('ordersStatus/edit', $this->data);
		}
	}

	/*
	* It removes the data from the database
	* and it returns the response into the json format
	*/
	public function remove()
	{
		if (!in_array('deleteOrdersStatus', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$ordersStatus_id = $this->input->post('ordersStatus_id');
		$response = array();
		if ($ordersStatus_id) {
			$delete = $this->model_orders_status->deactive($ordersStatus_id);
			if ($delete == true) {
				$response['success'] = true;
				$response['messages'] = "Successfully removed";
			} else {
				$response['success'] = false;
				$response['messages'] = "Error in the database while removing the Orders Status information";
			}
		} else {
			$response['success'] = false;
			$response['messages'] = "Refersh the page again!!";
		}
		echo json_encode($response);
	}
}
