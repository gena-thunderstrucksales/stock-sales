<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Orders extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Orders';

		$this->load->model('model_orders');
		$this->load->model('model_products');
		$this->load->model('model_company');
		$this->load->model('model_brands');
		$this->load->model('model_products_attributes');
		$this->load->model('model_attributes');
		$this->load->model('model_prices');
		$this->load->model('model_discounts');
		$this->load->model('model_customers');
		$this->load->model('model_addresses');
		$this->load->model('model_users');
		$this->load->model('model_orders_item');
		$this->load->model('model_orders_status');
		$this->load->model('model_log_emails');
		$this->load->model('model_notification_settings');
		$this->load->model('model_currencies');
		$this->load->model('model_category');
		$this->load->model('model_taxes');

		$this->load->library('enumstabletypeid');
		$this->load->library('enumstypeaddresscustomers');
		$this->load->library('enumstypepriority');
		$this->load->library('enumstypestatusobject');
		$this->load->library('email');
		$this->load->library('enumstypenotificationid');
		$this->load->library('helper');
		$this->load->library('enumsorderstatus');
		$this->load->library('enumstypecustomer');
		$this->load->library('enumstypepayments');
		$this->load->library('enumslistearlybirddiscount');
		$this->load->library('enumslistvolumediscount');
	}

	/* 
	* It only redirects to the manage order page
	*/
	public function index()
	{
		if (!in_array('viewOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->data['page_title'] = 'ALL ORDERS';
		$this->render_template('orders/index', $this->data);
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchOrdersData()
	{
		$result = array('data' => array());
		$active = $this->enumstypestatusobject->enumsNum['active'];
		$user_id = $this->session->userdata('id');

		if (in_array('viewAllOrdersCustomers', $this->permission)) {
			$data = $this->model_orders->getOrdersData($active);
		} else{
			$data = $this->model_orders->getOrdersDataByIdCustomersUser($active, $user_id);
		}
	
		foreach ($data as $key => $value) {
			$date = date('Y-m-d', $value['date_time']);
			$time = date('h:i a', $value['date_time']);
			$customer_id = $value['customer_id'];
			$customer_name = $this->model_customers->getCustomerData($customer_id)['name'];
			$total_order = '<sup>$</sup>' . number_format($value['total_order'], 2);
			$id =  $value['id'];
			$date_time = $date . ' ' . $time;
			$arr_order_status = $this->model_orders_status->getOrdersStatusDataByIdOrder($id, strtotime(date('Y-m-d h:i:s a')), $active);
			$currency_name = $this->model_currencies->getCurrencyData($value['currency_id'])['name'];

			if ($value['user_id']) {
				$user = $this->model_users->getUserData($value['user_id']);
				$user_name = $user['username'];
			} else {
				$user_name = "n/a";
			}

			$buttons = '';
			if (in_array('updateOrder', $this->permission)) {
				$buttons .= '<button type="button"  onclick=window.location.href="' . base_url('orders/update/' . $value['id']) . '" class="label-base-icon-doc edit-doc"></button>';
			}
			if (in_array('deleteOrder', $this->permission)) {
				$buttons .= '<button type="button" class="label-base-icon-doc remove-doc" onclick="removeFunc(' . $value['id'] . ')" data-toggle="modal" data-target="#removeModal"></button>';
			}

			if (in_array('viewOrder', $this->permission)) {
				$buttons .= '<button type="button" onclick=window.open("' . base_url('orders/print/' . $value['id']) . '") class="label-base-icon-doc print-doc"></button>';
			}

			if (in_array('createPayment', $this->permission)) {
				$buttons .= '<button type="button" class="label-base-icon-doc dollar" onclick="makePayment(' . $value['id'] . ',' . $customer_id . ')" data-toggle="modal" data-target="#makePaymentModal"></button>';
			}

			if (in_array('updateOrder', $this->permission)) {
				$buttons .= '<div class="btn-group">
                <button type="button" class="label-base-icon-doc select-menu" data-toggle="dropdown" data-disabled="true" aria-expanded="true"> </button>
                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                    <li><a onclick="setStatusOrder(' . $value['id'] . ',0,' . $customer_id . ')" >Pending </a></li>
                    <li><a onclick="setStatusOrder(' . $value['id'] . ',1,' . $customer_id . ')" >Approved</a></li>
                    <li><a onclick="setStatusOrder(' . $value['id'] . ',2,' . $customer_id . ')" >In Progress</a></li>
                    <li><a onclick="setStatusOrder(' . $value['id'] . ',3,' . $customer_id . ')" >Completed</a></li>
                    <li><a onclick="setStatusOrder(' . $value['id'] . ',4,' . $customer_id . ')" >Cancelled</a></li>
                </ul>
            </div>';
			}
			/*if (in_array('updateOrder', $this->permission)) {
                $buttons .= '<div class="btn-group">
                <button type="button" class="label-base-icon-doc select-menu" data-toggle="dropdown" data-disabled="true" aria-expanded="true"> </button>';
                $buttons .= '<ul class="dropdown-menu dropdown-menu-right" role="menu">';
                foreach ($this->enumsorderstatus->enumsStr as $key => $v) {
                    $buttons .= '<li><a onclick="setStatusOrder(' . $value['id'] . ','.$key.',' . $customer_id . ')" >'.$v.' </a></li>';
                }
                $buttons .= '</ul></div>';
            }*/

			if ($arr_order_status) {
				$labelStatus = $this->enumsorderstatus->enumsStr[$arr_order_status['type_status_id']];
				$order_status = $arr_order_status['type_status_id'];
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
			} else {
				$paid_status = '<span class="">n/a</span> <a class="label-base-status "></a>';
			}

			$result['data'][$key] = array(
				$id,
				$date_time,
				$customer_name,
				$paid_status,
				$currency_name,
				$total_order,
				$user_name,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	public function fetchAddProductTable()
	{
		$result = array('data' => array());
		$brand_id = $this->input->post('category');
		if ($brand_id) {
			$data = $this->model_products->getProductFiltrCategoryData($brand_id);
			if (!$data) {
				$data = $this->model_products->getActiveProductData();
			}
			foreach ($data as $key => $value) {
				$result['data'][$key] = array(
					$value['id'],
					$value['name'],
				);
			}
		}
		echo json_encode($result);
	}

	public function getProductValueById()
	{
		$product_id = $this->input->post('product_id');
		if ($product_id) {
			$product_data = $this->model_products->getProductData($product_id);
			echo json_encode($product_data);
		}
	}

	public function getCustomerContactInfoValueById()
	{
		$result = array('data' => array());
		$customer_id = $this->input->post('customer_id');
		if ($customer_id) {

			$customer = $this->model_customers->getCustomerData($customer_id);
			$data_tax = $this->model_taxes->getTaxData($customer['tax_id']);
			$typecustomer = $this->enumstypecustomer->getEnumsStr[$customer['type_customer_id']];
			$type_customer_id = $customer['type_customer_id'];

			$result['data']['tax_info']['name'] =  $data_tax['name'];
			$result['data']['tax_info']['id'] = $data_tax['id'];
			$result['data']['tax_info']['rate'] = $data_tax['rate'];

			$result['data']['type_customer'] = $typecustomer;
			$result['data']['type_customer_id'] = $type_customer_id;

			$result['data']['contact_info'] = $this->model_contacts_info->getContactInfoData($customer_id, 1);
			$result['data']['addresses_info'] = $this->model_addresses->getActiveCustomersAddressByTypeData($customer_id);
			echo json_encode($result);
		}
	}

	public function fetchAddAttributeTable()
	{
		$result = array('data' => array());
		$product_id = $this->input->post('product_id');
		$currency_id = $this->input->post('currency_id');
		$doc_date_doc_time = $this->input->post('doc_date_doc_time');

		if ($doc_date_doc_time) {
			$time_request = $doc_date_doc_time;
		} else {
			$time_request = strtotime(date('Y-m-d h:i:s a'));
		}
		$active = $this->enumstypestatusobject->enumsNum['active'];

		if ($product_id && $currency_id) {
			$data = $this->model_products_attributes->getProductsAttributesData($product_id);
			foreach ($data as $key => $value) {
				$name_attributes  = $this->model_attributes->getAttributeValueById($value['attribute_id']);
				$prices = $this->model_prices->getPricesItemWithOptinoData($product_id, $value['attribute_id'], $time_request, $active, $currency_id);
				if ($prices) {
					$price = number_format($prices['price'], 2);
				} else {
					$price =  "0.00";
				}
				$result['data'][$key] = array(
					$value['attribute_id'],
					$name_attributes['value'],
					$price,
				);
			} // /foreach

			echo json_encode($result);
		} else {
			echo json_encode('');
		}
	}
	

	public function fetchSetFillProductTable($id_order)
	{
		$result = array('data' => array());
		$order_data = $this->model_orders->getOrdersDataById($id_order);

		$order_items = $this->model_orders_item->getOrdersItemData($order_data['id']);

		foreach ($order_items as $key => $value) {
			$id  =  $value['id'];
			$product_id  =  $value['product_id'];
			$name_product  =  $this->model_products->getProductData($value['product_id'])['name'];
			$name_option  =  $this->model_attributes->getAttributeValueById($value['option_item_id'])['value'];
			$price  =  $value['price'];
			$it_count  =  $key;
			$id_option  =  $value['option_item_id'];
			$sub_total  =  $value['sub_total'];
			$total  =  $value['total'];
			$sum_discount_early_bird  =  $value['sum_discount_early_bird'];
			$sum_discount_cash  =  $value['sum_discount_cash'];
			$sum_discount_volume =  $value['sum_discount_volume'];
			$discount_early_bird  =  $value['discount_early_bird'];
			$discount_cash  =  $value['discount_cash'];
			$discount_volume  =  $value['discount_volume'];
			$qty  =  $value['qty'];

			$result['data'][$key] = array(
				'id' =>	$id,
				'id_product' =>	$product_id,
				'id_option' =>	$id_option,
				"name_product" => $name_product,
				'name_option' =>	$name_option,
				'price' =>	$price,
				'it_count' =>	$it_count,
				'sub_total' => $sub_total,
				'total' => $total,
				'sum_discount_early_bird' => $sum_discount_early_bird,
				'sum_discount_cash' => $sum_discount_cash,
				'sum_discount_volume' => $sum_discount_volume,
				'discount_volume' => $discount_volume,
				'discount_cash' => $discount_cash,
				'discount_early_bird' => $discount_early_bird,
				'qty' => $qty,
			);
		}
		return $result;
	}

	/*
	* If the validation is not valid, then it redirects to the create page.
	* If the validation for each input field is valid then it inserts the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function create()
	{
		if (!in_array('createOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->data['page_title'] = 'Add Order';
		$this->form_validation->set_rules('id_bill_address', 'Billing address', 'trim|required');
		$this->form_validation->set_rules('id_shipping_address', 'Shipping address', 'trim|required');
		$this->form_validation->set_rules('id_contact_info', 'Contact info', 'trim|required');
		$this->form_validation->set_rules('customer_id', 'Customer name', 'trim|required');
		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		$this->form_validation->set_rules('currency_id', 'Currency', 'trim|required');
		$this->form_validation->set_rules('tax_id', 'Tax%', 'trim|required');
		$this->form_validation->set_rules('type_customer_id', 'Type customer', 'required');
		$this->form_validation->set_rules('type_payment_id', 'Type payment', 'required');
		$this->form_validation->set_rules('dealer_discount', 'Volume Discount', 'required');
		$this->form_validation->set_rules('early_dealer_discount', 'Early dealer discount', 'required');


		$this->form_validation->set_rules('price[]', 'Price', array(
			'required',
			function ($value) {
				if ($value > 0) {
					return true;
				} else {
					return false;
				}
			}
		));

		$this->form_validation->set_rules('qty[]', 'Qty', array(
			'required',
			function ($value) {
				if ($value > 0) {
					return true;
				} else {
					return false;
				}
			}
		));

		if ($this->form_validation->run() == TRUE) {
			$table_type_id = $this->enumstabletypeid->enumsNum['Orders'];

			$user_id = $this->session->userdata('id');

			$dealer_discount_id = $this->enumslistvolumediscount->enumsNum[$this->input->post('dealer_discount')];
			$early_dealer_discount_id = $this->enumslistearlybirddiscount->enumsNum[$this->input->post('early_dealer_discount')];

			$data_header = array(
				'date_time' => strtotime(date('Y-m-d h:i:s a')),
				'user_id' => $user_id,
				'id_shipping_address' => $this->input->post('id_shipping_address'),
				'id_bill_address' => $this->input->post('id_bill_address'),
				'id_contact_info'  => $this->input->post('id_contact_info'),
				'customer_id' => $this->input->post('customer_id'),
				'currency_id' => $this->input->post('currency_id'),
				'tax_id' => $this->input->post('tax_id'),
				'type_customer_id'  => $this->input->post('type_customer_id'),
				'type_payment_id' => $this->input->post('type_payment_id'),
				'dealer_discount_id' => $dealer_discount_id,
				'early_dealer_discount_id' =>$early_dealer_discount_id ,
				'comments' => $this->input->post('comments'),


				'sub_total_order' => $this->input->post('sub_total_order_value'),
				'total_shipping' => $this->input->post('total_shipping_value'),
				'sub_total_table_order' => $this->input->post('sub_total_table_order_value'),
				'total_table_order' => $this->input->post('total_table_order_value'),
				'total_discount_early_bird' => $this->input->post('total_discount_early_bird_value'),
				'total_discount_cash' => $this->input->post('total_discount_cash_value'),
				'total_discount_valume' => $this->input->post('total_discount_volume_value'),
				'total_tax_order' => $this->input->post('tax_order_value'),
				'total_order' => $this->input->post('total_order_value'),
				'total_after_discount_early_bird' => $this->input->post('total_after_discount_early_bird_value'),
			);

			$data_report_payments_orders = array(
				'user_id' => $user_id,
				'date_time' => $data_header['date_time'],
				'table_type_id' => $table_type_id,
				'item_table_id' => '',
				'order_id' => '',
				'customer_id' =>  $data_header['customer_id'],
				'balance' => $data_header['total_order'],
				'currency_id' =>  $data_header['currency_id'],
			);

			$data_orders_status = array(
				'user_id' => $user_id,
				'date_time' => strtotime(date('Y-m-d h:i:s a')),
				'order_id' => '',
				'customer_id' => $this->input->post('customer_id'),
				'type_status_id' => $this->enumsorderstatus->enumsNum['Pending'],
				'table_type_id' => $table_type_id,
			);


			$data =  array(
				'data_header' => $data_header,
				'data_report_payments_orders' => $data_report_payments_orders,
				'data_orders_status' => $data_orders_status,
			);
			$order_id = $this->model_orders->create($data);

			if ($order_id) {
				$this->session->set_flashdata('success', 'Successfully created, email not sent!');

				$name_customer = $this->model_customers->getCustomerData($this->input->post('customer_id'))['name'];

				$arr_data_send_order =  array(
					'order_id' => $order_id,
					'user_id' => $user_id,
					'arr_type_notification_id' => array(
						$this->enumstypenotificationid->enumsNum['Admin']
					),
					'name_customer' => $name_customer,
				);

				if ($this->send_oredr_by_email($arr_data_send_order)) {
					$this->session->set_flashdata('success', 'Successfully, email sent');
				} else {
					$this->session->set_flashdata('errors', 'Error occurred, email!!');
				}

				redirect('orders/', 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('orders/create/', 'refresh');
			}
		} else {

			$active = $this->enumstypestatusobject->enumsNum['active'];
			$this->data['username'] = $this->session->userdata('username');
			$this->data['status_order'] = strtoupper($this->enumsorderstatus->enumsStr[0]);
			$this->data['currencies'] = $this->model_currencies->getActiveCurrencies();

			$type_volume_discount = $this->enumslistvolumediscount->enumsStr;
			$this->data['type_volume_discount'] = $type_volume_discount;

			$type_payments = $this->enumstypepayments->enumsStr;
			$this->data['type_payments'] = $type_payments;

			$type_early_bird_discount= $this->enumslistearlybirddiscount->enumsStr;
			$this->data['type_early_bird_discount'] = $type_early_bird_discount;
			// false case
			$company = $this->model_company->getCompanyData();
			$this->data['company_data']= $company;

			$this->data['products'] = $this->model_products->getActiveProductData();
			$this->data['brands'] = $this->model_brands->getActiveBrands();

			if (in_array('viewAllOrdersCustomers', $this->permission)) {
				$this->data['customers'] = $this->model_customers->getCustomerListData($active);
			}else{
				$user_id = $this->session->userdata('id');
				$this->data['customers'] = $this->model_customers->getCustomerListDataByUser($active, $user_id);
			}

			$this->data['category'] = $this->model_category->getActiveCategroy();

			$this->render_template('orders/create', $this->data);
		}
	}

	/*
	* If the validation is not valid, then it redirects to the edit orders page 
	* If the validation is successfully then it updates the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function update($id)
	{
		if (!in_array('updateOrder', $this->permission)) {
			redirect('orders/', 'refresh');
		}

		if (!$id) {
			redirect('orders/', 'refresh');
		}
		$this->data['page_title'] = 'Update Order';
		$this->form_validation->set_rules('id_bill_address', 'Billing address', 'trim|required');
		$this->form_validation->set_rules('id_shipping_address', 'Shipping address', 'trim|required');
		$this->form_validation->set_rules('id_contact_info', 'Contact info', 'trim|required');
		$this->form_validation->set_rules('customer_id', 'Customer name', 'trim|required');
		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		$this->form_validation->set_rules('currency_id', 'Currency', 'trim|required');
		$this->form_validation->set_rules('tax_id', 'Tax%', 'trim|required');
		$this->form_validation->set_rules('type_customer_id', 'Type customer', 'required');
		$this->form_validation->set_rules('type_payment_id', 'Type payment', 'required');
		$this->form_validation->set_rules('dealer_discount', 'Volume Discount', 'required');
		$this->form_validation->set_rules('early_dealer_discount', 'Early dealer discount', 'required');

		$this->form_validation->set_rules('price[]', 'Price', array(
			'required',
			function ($value) {
				if ($value > 0) {
					return true;
				} else {
					return false;
				}
			}
		));

		$this->form_validation->set_rules('qty[]', 'Qty', array(
			'required',
			function ($value) {
				if ($value > 0) {
					return true;
				} else {
					return false;
				}
			}
		));

		if ($this->form_validation->run() == TRUE) {
			$user_id = $this->session->userdata('id');

			$dealer_discount_id = $this->enumslistvolumediscount->enumsNum[$this->input->post('dealer_discount')];
			$early_dealer_discount_id = $this->enumslistearlybirddiscount->enumsNum[$this->input->post('early_dealer_discount')];

			$data_header = array(
				'id' => $id,
				'date_time' => $this->input->post('doc_date_doc_time'),
				'user_id' => $user_id,
				'id_shipping_address' => $this->input->post('id_shipping_address'),
				'id_bill_address' => $this->input->post('id_bill_address'),
				'id_contact_info'  => $this->input->post('id_contact_info'),
				'customer_id' => $this->input->post('customer_id'),
				'currency_id' => $this->input->post('currency_id'),
				'tax_id' => $this->input->post('tax_id'),
				'type_customer_id'  => $this->input->post('type_customer_id'),
				'type_payment_id' => $this->input->post('type_payment_id'),
				'dealer_discount_id' => $dealer_discount_id,
				'early_dealer_discount_id' => $early_dealer_discount_id ,
				'comments' => $this->input->post('comments'),

				'sub_total_order' => $this->input->post('sub_total_order_value'),
				'total_shipping' => $this->input->post('total_shipping_value'),
				'sub_total_table_order' => $this->input->post('sub_total_table_order_value'),
				'total_table_order' => $this->input->post('total_table_order_value'),
				'total_discount_early_bird' => $this->input->post('total_discount_early_bird_value'),
				'total_discount_cash' => $this->input->post('total_discount_cash_value'),
				'total_discount_valume' => $this->input->post('total_discount_volume_value'),
				'total_tax_order' => $this->input->post('tax_order_value'),
				'total_order' => $this->input->post('total_order_value'),
				'total_after_discount_early_bird' => $this->input->post('total_after_discount_early_bird_value'),
				
			);
			$table_type_id = $this->enumstabletypeid->enumsNum['Orders'];

			$data_report_payments_orders = array(
				'user_id' => $this->session->userdata('id'),
				'date_time' => $data_header['date_time'],
				'table_type_id' => $table_type_id,
				'item_table_id' => $id,
				'order_id' => $id,
				'customer_id' =>  $data_header['customer_id'],
				'balance' => $data_header['total_order'],
				'currency_id' =>  $data_header['currency_id'],
			);


			$data =  array(
				'data_header' => $data_header,
				'data_report_payments_orders' => $data_report_payments_orders,
			);
			$update = $this->model_orders->update($id, $data);
			if ($update == true) {
				$this->session->set_flashdata('success', 'Successfully updated');
				redirect('orders/', 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('orders/update/' . $id, 'refresh');
			}
		} else {
			$this->data['list_products'] = $this->fetchSetFillProductTable($id);
			$active = $this->enumstypestatusobject->enumsNum['active'];
			$arr_order_status = $this->model_orders_status->getOrdersStatusDataByIdOrder($id, strtotime(date('Y-m-d h:i:s a')), $active);

			$type_payments = $this->enumstypepayments->enumsStr;
			$this->data['type_payments'] = $type_payments;

			// false case
			$company = $this->model_company->getCompanyData();
			$this->data['company_data']= $company;

			$this->data['currencies'] = $this->model_currencies->getActiveCurrencies();

			$orders_data = $this->model_orders->getOrdersDataById($id);
			$this->data['order_header'] = $orders_data;
			$this->data['order_header']['id'] = $orders_data['id'];
			$this->data['order_header']['doc_date'] =  date('Y-m-d', $orders_data['date_time']);
			$this->data['order_header']['doc_time'] =  date('h:i a', $orders_data['date_time']);
			$this->data['order_header']['currency_id'] = $orders_data['currency_id'];

			$type_volume_discount = $this->enumslistvolumediscount->enumsStr;
			$this->data['type_volume_discount'] = $type_volume_discount;

			$type_early_bird_discount= $this->enumslistearlybirddiscount->enumsStr;
			$this->data['type_early_bird_discount'] = $type_early_bird_discount;

			$labelStatus = $this->enumsorderstatus->enumsStr[$arr_order_status['type_status_id']];

			$this->data['order_header']['type_status_id'] = $arr_order_status['type_status_id'];
			$this->data['order_header']['status_order'] = strtoupper($labelStatus);

			$data_tax = $this->model_taxes->getTaxData($orders_data['tax_id']);

			$typecustomer = $this->enumstypecustomer->getEnumsStr[$orders_data['type_customer_id']];
			$this->data['order_header']['type_customer'] = $typecustomer;
			$this->data['order_header']['type_customer_id'] = $typecustomer;

			$this->data['order_header']['tax_info']['tax_id'] = $data_tax['id'];
			$this->data['order_header']['tax_info']['tax_name'] = $data_tax['name'];
			$this->data['order_header']['tax_info']['tax_rate'] = $data_tax['rate'];

			$this->data['order_header']['customer_id'] = $orders_data['customer_id'];
			$this->data['order_header']['username'] = $this->model_users->getUserData($orders_data['user_id'])['username'];

			$this->data['order_header']['contact_info'] = $this->model_contacts_info->getContactData($orders_data['id_contact_info']);
			$this->data['order_header']['addresses_info']['bill'] = $this->model_addresses->getsAddressData($orders_data['id_bill_address']);
			$this->data['order_header']['addresses_info']['shipping'] = $this->model_addresses->getsAddressData($orders_data['id_shipping_address']);

			$this->data['order_header']['doc_date_doc_time'] =  $orders_data['date_time'];

			$this->data['products'] = $this->model_products->getActiveProductData();
			$this->data['brands'] = $this->model_brands->getActiveBrands();
			
			if (in_array('viewAllOrdersCustomers', $this->permission)) {
				$this->data['customers'] = $this->model_customers->getCustomerListData($active);
			}else{
				$user_id = $this->session->userdata('id');
				$this->data['customers'] = $this->model_customers->getCustomerListDataByUser($active, $user_id);
			}
			$this->data['category'] = $this->model_category->getActiveCategroy();

			$this->render_template('orders/edit', $this->data);
		}
	}

	/*
	* It removes the data from the database
	* and it returns the response into the json format
	*/
	public function remove()
	{
		if (!in_array('deleteOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$order_id = $this->input->post('order_id');
		$response = array();
		if ($order_id) {
			$arr_check = $this->helper->parse_answer_links($this->model_orders->exist_links($order_id));
			if ($arr_check) {
				$response['success'] = false;
				$response['messages'] = "This item has some links for " . $arr_check . ' cannot be removed now!';
			} else {
				$delete = $this->model_orders->deactive($order_id);
				if ($delete == true) {
					$response['success'] = true;
					$response['messages'] = "Successfully removed";
				} else {
					$response['success'] = false;
					$response['messages'] = "Error in the database while removing the item";
				}
			}
		} else {
			$response['success'] = false;
			$response['messages'] = "Refersh the page again!!";
		}

		echo json_encode($response);
	}

	public function create_html_order($id)
	{
		if (!in_array('viewOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		if (!$id) {
			redirect('dashboard', 'refresh');
		}
		$orders_data = $this->model_orders->getOrdersDataById($id);
		$active = $this->enumstypestatusobject->enumsNum['active'];
		$type_billing = $this->enumstypeaddresscustomers->getEnumsNum['Billing'];
		$table_type_id = $this->enumstabletypeid->enumsNum['Company'];

		$this->data['list_products'] = $this->fetchSetFillProductTable($id);
		// false case
		$company = $this->model_company->getCompanyData();
		$this->data['company_data']['business_name'] = $company['business_name'];
		$this->data['company_data']['vat_charge_value'] = $company['vat_charge_value'];
		$this->data['company_data']['addresses_info']['bill'] = $this->model_addresses->getCustomersAddressByTypeData($company['id'], $type_billing, $table_type_id);

		$arr_order_status = $this->model_orders_status->getOrdersStatusDataByIdOrder($id, strtotime(date('Y-m-d h:i:s a')), $active);
		$id_status = $arr_order_status['type_status_id'];
		$name_order_status = $this->enumsorderstatus->enumsStr[$id_status];


		$this->data['order_header'] = $orders_data;
		$this->data['order_header']['name_order_status'] = $name_order_status;
		$this->data['order_header']['currency_name'] = $this->model_currencies->getCurrencyData($orders_data['currency_id'])['name'];
		$this->data['order_header']['id'] = $orders_data['id'];
		$this->data['order_header']['doc_date'] =  date('Y-m-d', $orders_data['date_time']);
		$this->data['order_header']['doc_time'] =  date('h:i a', $orders_data['date_time']);
		$this->data['order_header']['user_name'] = $this->model_users->getUserData($orders_data['user_id']);

		$this->data['order_header']['contact_info'] = $this->model_contacts_info->getContactData($orders_data['id_contact_info']);
		$this->data['order_header']['addresses_info']['bill'] = $this->model_addresses->getsAddressData($orders_data['id_bill_address']);
		$this->data['order_header']['addresses_info']['shipping'] = $this->model_addresses->getsAddressData($orders_data['id_shipping_address']);

	
		$this->data['order_header']['early_bird_discount'] = $this->enumslistearlybirddiscount->enumsStr[$orders_data['early_dealer_discount_id']];
		$this->data['order_header']['payment_type'] = $this->enumstypepayments->enumsStr[$orders_data['type_payment_id']];

		$customer = $this->model_customers->getCustomerData($orders_data['customer_id']);

		$this->data['order_header']['customer'] = $customer;
		$this->data['seller'] = $this->model_users->getUserData($customer['user_id'])['username'];
		
		if ($orders_data['type_payment_id'] == 0 ) {
			$this->data['order_header']['cash_discount'] = $company['cash_discount'];
        } else {
			$this->data['order_header']['cash_discount'] = 0;
        }
		
		$this->data['order_header']['doc_date_doc_time'] =  $orders_data['date_time'];

		$this->data['products'] = $this->model_products->getActiveProductData();
		$this->data['brands'] = $this->model_brands->getActiveBrands();
		$this->data['customers'] = $this->model_customers->getCustomerListData(1);

		return $this->load->view('orders/print-order', $this->data, TRUE);
	}

	/*
	* It gets the product id and fetch the order data. 
	* The order print logic is done here 
	*/
	public function print($id)
	{
		if (!in_array('viewOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->load->library('pdf');
		$html = $this->create_html_order($id);
		$this->pdf->loadHtml($html);
		$this->pdf->setPaper('letter', 'portrait');
		$this->pdf->render();
		$this->pdf->stream('Order-' . $id . ' from ' . $this->data['order_header']['doc_date'] . ".pdf", array("Attachment" => 0));
	}

	function send()
	{
		// Load PHPMailer library
		$this->load->library('Phpmailerlibrary');
		// PHPMailer object
		$mail = $this->phpmailerlibrary->load();
		try {
			//Server settings
			$mail->SMTPDebug = 2;                      //Enable verbose debug output
			$mail->isSMTP();                                            //Send using SMTP
			$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			$mail->Username   = 'ThunderStruckAg';                     //SMTP username
			$mail->Password   = '';                               //SMTP password
			$mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
			$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
			//Recipients
			$mail->setFrom('thunderstruckagtest@gmail.com', 'Mailer');
			$mail->addAddress('gena@thunderstrucksales.com', 'Joe User');     //Add a recipient
			//Content
			$mail->isHTML(true);                                  //Set email format to HTML
			$mail->Subject = 'Here is the subject';
			$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
			$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

			$mail->send();
			echo 'Message has been sent';
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}

	public function send_oredr_by_email($arr_data)
	{
		if (!in_array('viewOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$order_id = $arr_data['order_id'];
		$user_id = $arr_data['user_id'];
		$arr_type_notification_id = $arr_data['arr_type_notification_id'];
		$name_customer = $arr_data['name_customer'];

		$notification_settings = $this->model_notification_settings->getNotificationSettingsDataByType();
		$company = $this->model_company->getCompanyData();

		$config = array(
			'smtp_host' => $company['smtp_host'],
			'smtp_port' => $company['smtp_port'],
			'smtp_user' => $company['smtp_user'],
			'smtp_pass' => $company['smtp_pass'],
			'charset' => 'utf-8',
			'mailtype' => 'html',
			'newline' => "\r\n",
			'wordwrap' => TRUE
		);

		$subject = 'Order-' . $order_id . ' from ' . date('Y-m-d h:i:s a');
		$message_html =	$this->create_html_order($order_id);
		$date_time = strtotime(date('Y-m-d h:i:s a'));
		$sent_success = false;
		$body = "Hi," . $name_customer . "
		Thank you for your purchase. 
		We've attached your receipt below Please contact us with any questions you may have";

		foreach ($notification_settings as $key => $value) {
			if (!in_array($value['type_notification_id'], $arr_type_notification_id)) {
				continue;
			}
			$data_send_massage = array(
				'user_id' => $user_id,
				'date_time' => $date_time,
				'id_doc' => $order_id,
				'message' => $message_html,
				'config' => $config,
				'subject' => $subject,
				'from_email' => $company['smtp_user'],
				'to_email' => $value['email'],
				'body' => $body,
			);
			if ($data_send_massage) {
				$sent_success =  $this->model_log_emails->send_by_email($data_send_massage);
				if (!$sent_success) {
					return $sent_success;
				}
			} else {
				$sent_success = false;
			}
		}
		return $sent_success;
	}
}
