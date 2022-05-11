<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Discounts extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->data['page_title'] = 'Discounts';

		$this->load->model('model_discounts');
		$this->load->model('model_products');
		$this->load->model('model_company');
		$this->load->model('model_brands');
		$this->load->model('model_prices');
		$this->load->model('model_products_attributes');
		$this->load->model('model_attributes');
		$this->load->model('model_users');
		$this->load->model('model_currencies');
		$this->load->model('model_category');

		$this->load->library('enumstypediscount');
		$this->load->library('enumstypepriority');
		$this->load->library('enumstypestatusobject');
	}

	/* 
	* It only redirects to the manage discount page
	*/
	public function index()
	{
		if (!in_array('viewDiscount', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->data['page_title'] = 'Manage Discounts';
		$this->render_template('discounts/index', $this->data);
	}

	/*
	* Fetches the discounts data from the discounts table 
	* this function is called from the datatable ajax function
	*/
	public function fetchDiscountsData()
	{
		$result = array('data' => array());
		$active = $this->enumstypestatusobject->enumsNum['active'];
		$data = $this->model_discounts->getDiscountsData($active);

		foreach ($data as $key => $value) {
			$date = date('Y-m-d', $value['date_time']);
			$time = date('h:i a', $value['date_time']);
			$id =  $value['id'];
			$date_time = $date . ' ' . $time;
			$priority =  $this->enumstypepriority->enumsStr[$value['priority_id']];
			$currency_name = $this->model_currencies->getCurrencyData($value['currency_id'])['name'];
			$name =  $value['name'];

			// button
			$buttons = '';
			if (in_array('updatePrice', $this->permission)) {
				$buttons .= ' <button type="button"  onclick=window.location.href="' . base_url('discounts/update/' . $id) . '" class="label-base-icon-doc edit-doc"></button>';
			}
			if (in_array('deletePrice', $this->permission)) {
				$buttons .= ' <button type="button" class="label-base-icon-doc remove-doc" onclick="removeFunc(' . $id . ')" data-toggle="modal" data-target="#removeModal"></button>';
			}
			$result['data'][$key] = array(
				$id,
				$date_time,
				$name,
				$priority,
				$currency_name,
				$buttons,
			);
		} // /foreach
		echo json_encode($result);
	}

	/*
	* If the validation is not valid, then it redirects to the create page.
	* If the validation for each input field is valid then it inserts the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function create()
	{
		if (!in_array('createDiscount', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->data['page_title'] = 'Add Discount';

		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
		$this->form_validation->set_rules('band_start', 'Band Start', 'trim|required');
		$this->form_validation->set_rules('band_end', 'Band End', 'trim|required');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim|required');
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
		$this->form_validation->set_rules('name', 'Discount Nname', 'trim|required');
		$this->form_validation->set_rules('currency_id', 'Currency', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$data_header = array(
				'user_id' => $user_id = $this->session->userdata('id'),
				'date_time' => strtotime(date('Y-m-d h:i:s a')),
				'amount' => $this->input->post('amount'),
				'band_start' => $this->input->post('band_start'),
				'band_end' => $this->input->post('band_end'),
				'name' => $this->input->post('name'),
				'end_date' => strtotime($this->input->post('end_date')),
				'start_date' => strtotime($this->input->post('start_date')),
				'type_discount' =>  $this->enumstypediscount->enumsNum[$this->input->post('type_discount')],
				'priority_id' =>   $this->enumstypepriority->enumsNum[$this->input->post('priority_id')],
				'currency_id' => $this->input->post('currency_id'),
			);
			$data =  array(
				'data_header' => $data_header,
			);
			$discount_id = $this->model_discounts->create($data);
			if ($discount_id) {
				$this->session->set_flashdata('success', 'Successfully created');
				redirect('discounts/', 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('discounts/create/', 'refresh');
			}
		} else {
			$this->data['currencies'] = $this->model_currencies->getActiveCurrencies();
			$this->data['username'] = $this->session->userdata('username');
			$this->data['products'] = $this->model_products->getActiveProductData();
			$this->data['type_discounts'] = $this->enumstypediscount->enumsStr;
			$this->data['type_prioritis'] = $this->enumstypepriority->enumsStr;
			$this->data['brands'] = $this->model_brands->getActiveBrands();
			$this->data['category'] = $this->model_category->getActiveCategroy();

			$this->render_template('discounts/create', $this->data);
		}
	}

	/*
	* If the validation is not valid, then it redirects to the edit discounts page 
	* If the validation is successfully then it updates the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function update($id)
	{
		if (!in_array('updateDiscount', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		if (!$id) {
			redirect('dashboard', 'refresh');
		}
		$this->data['page_title'] = 'Edit Discount';

		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
		$this->form_validation->set_rules('band_start', 'Band Start', 'trim|required');
		$this->form_validation->set_rules('band_end', 'Band End', 'trim|required');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim|required');
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
		$this->form_validation->set_rules('name', 'Discount Nname', 'trim|required');
		$this->form_validation->set_rules('type_discount', 'Type_discount', 'trim|required');
		$this->form_validation->set_rules('priority', 'Priority', 'trim|required');
		$this->form_validation->set_rules('currency_id', 'Currency', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$data_header = array(
				'user_id' => $this->session->userdata('id'),
				'date_time' => $this->input->post('doc_date_doc_time'),
				'amount' => $this->input->post('amount'),
				'band_start' => $this->input->post('band_start'),
				'band_end' => $this->input->post('band_end'),
				'name' => $this->input->post('name'),
				'end_date' => strtotime($this->input->post('end_date')),
				'start_date' => strtotime($this->input->post('start_date')),
				'type_discount' =>  $this->enumstypediscount->enumsNum[$this->input->post('type_discount')],
				'priority_id' =>   $this->enumstypepriority->enumsNum[$this->input->post('priority')],
				'currency_id' => $this->input->post('currency_id'),
			);
			$data =  array(
				'data_header' => $data_header,
			);
			$update = $this->model_discounts->update($id, $data);
			if ($update == true) {
				$this->session->set_flashdata('success', 'Successfully updated');
				redirect('discounts/', 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('discounts/update/' . $id, 'refresh');
			}
		} else {
			$this->data['list_products'] = $this->fetchSetFillProductTable($id);
			$this->data['currencies'] = $this->model_currencies->getActiveCurrencies();

			$this->data['products'] = $this->model_products->getActiveProductData();
			$this->data['brands'] = $this->model_brands->getActiveBrands();
			$this->data['category'] = $this->model_category->getActiveCategroy();

			$discount_header = $this->model_discounts->getDiscountsDataById($id);
			$this->data['discount_header']['currency_id'] = $discount_header['currency_id'];
			$this->data['discount_header']['username'] = $this->model_users->getUserData($discount_header['user_id'])['username'];
			$this->data['discount_header']['id'] = $discount_header['id'];
			$this->data['discount_header']['doc_date'] =  date('Y-m-d', $discount_header['date_time']);
			$this->data['discount_header']['doc_time'] =  date('h:i a', $discount_header['date_time']);
			$this->data['discount_header']['doc_date_doc_time'] =  $discount_header['date_time'];

			$this->data['discount_header']['start_date'] =  date('Y-m-d', $discount_header['start_date']);
			$this->data['discount_header']['end_date'] =  date('Y-m-d', $discount_header['end_date']);
			$this->data['discount_header']['band_start'] =   $discount_header['band_start'];
			$this->data['discount_header']['band_end'] =   $discount_header['band_end'];
			$this->data['discount_header']['amount'] =   $discount_header['amount'];
			$this->data['discount_header']['priority_id'] =   $this->enumstypepriority->enumsStr[$discount_header['priority_id']];
			$this->data['discount_header']['name'] =   $discount_header['name'];
			$this->data['discount_header']['type_discount'] =   $this->enumstypediscount->enumsStr[$discount_header['type_discount']];

			$this->render_template('discounts/edit', $this->data);
		}
	}

	public function fetchAddProductTable()
	{
		$result = array('data' => array());
		$category = $this->input->post('category');
		if ($category) {
			$data = $this->model_products->getProductFiltrCategoryData($category);
			foreach ($data as $key => $value) {
				$result['data'][$key] = array(
					$value['id'],
					$value['name'],
				);
			} // /foreach
		}
		echo json_encode($result);
	}

	public function fillDocTableAllProducts()
	{
		$result = array('data' => array());
		$count = 0;

		$data = $this->model_products->getProductData();
		foreach ($data as $key => $value) {
			$product_data = $this->model_products_attributes->getProductsAttributesData($value['id']);

			foreach ($product_data as $product_key => $attribute_value) {
				$item_attribute  = $this->model_attributes->getAttributeValueById($attribute_value['attribute_id']);

				if(	$item_attribute ){
					$nameOption = $item_attribute['value'];
					$idOption = $attribute_value['attribute_id'];
				} else{
					$nameOption = '';
					$idOption = '';
				}

				$result['data'][$count] = array(
					'nameProduct' =>  $value['name'],
					'idProduct'  =>  $value['id'],
					'nameOption'  => $nameOption,
					'idOption'  =>$idOption ,
				);
				$count++;
			} // 
		} // /foreach
		echo json_encode($result);
	}

	public function fillDocTableByCategory()
	{
		$result = array('data' => array());
		$count = 0;
		$category_id = $this->input->post('category_id');
		if ($category_id) {
			$data = $this->model_products->getProductFiltrCategoryData($category_id);
			foreach ($data as $key => $value) {
				$product_data = $this->model_products_attributes->getProductsAttributesData($value['id']);

				foreach ($product_data as $product_key => $attribute_value) {
					$item_attribute  = $this->model_attributes->getAttributeValueById($attribute_value['attribute_id']);

					if(	$item_attribute ){
						$nameOption = $item_attribute['value'];
						$idOption = $attribute_value['attribute_id'];
					} else{
						$nameOption = '';
						$idOption = '';
					}
	
					$result['data'][$count] = array(
						'nameProduct' =>  $value['name'],
						'idProduct'  =>  $value['id'],
						'nameOption'  => $nameOption,
						'idOption'  =>$idOption ,
					);
					$count++;
				} // 
			}
		} // /foreach
		echo json_encode($result);
	}

	public function fetchAddAttributeTable()
	{
		$result = array('data' => array());
		$product_id = $this->input->post('product_id');
		if ($product_id) {
			$data = $this->model_products_attributes->getProductsAttributesData($product_id);

			foreach ($data as $key => $value) {
				$item_attribute  = $this->model_attributes->getAttributeValueById($value['attribute_id']);
				$result['data'][$key] = array(
					$value['attribute_id'],
					$item_attribute['value'],
					"Test",
				);
			} // /foreach
			echo json_encode($result);
		} else {
			echo json_encode('');
		}
	}

	public function fetchSetFillProductTable($id_discount)
	{
		$result = array('data' => array());
		$discounts_data = $this->model_discounts->getDiscountsDataById($id_discount);
		$discounts_items = $this->model_discounts->getDiscountsItemData($discounts_data['id']);

		foreach ($discounts_items as $key => $value) {
			$id  =  $value['id'];
			$product_id  =  $this->model_products->getProductData($value['product_id'])['id'];
			$name_product  =  $this->model_products->getProductData($value['product_id'])['name'];
			$name_option  =  $this->model_attributes->getAttributeValueById($value['attribute_id'])['value'];
			$it_count  =  $key;
			$id_option  =  $value['attribute_id'];

			$result['data'][$key] = array(
				'id' =>	$id,
				'idProduct' =>	$product_id,
				"nameProduct" => $name_product,
				'nameOption' =>	$name_option,
				'it_count' =>	$it_count,
				'idOption' => $id_option
			);
		}
		return $result;
	}

	/*
	* It gets the product id passed from the ajax method.
	* It checks retrieves the particular product data from the product id 
	* and return the data into the json format.
	*/
	public function getProductValueById()
	{
		$product_id = $this->input->post('product_id');
		if ($product_id) {
			$product_data = $this->model_products->getProductData($product_id);
			echo json_encode($product_data);
		}
	}

	/*
	* It removes the data from the database
	* and it returns the response into the json format
	*/
	public function remove()
	{
		if (!in_array('deleteDiscount', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$discount_id = $this->input->post('discount_id');

		$response = array();
		if ($discount_id) {
			$delete = $this->model_discounts->deactive($discount_id);
			if ($delete == true) {
				$response['success'] = true;
				$response['messages'] = "Successfully removed";
			} else {
				$response['success'] = false;
				$response['messages'] = "Error in the database while removing the product information";
			}
		} else {
			$response['success'] = false;
			$response['messages'] = "Refersh the page again!!";
		}
		echo json_encode($response);
	}
}
