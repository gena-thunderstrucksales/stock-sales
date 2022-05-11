<?php

defined('BASEPATH') or exit('No direct script access allowed');


class Prices extends Admin_Controller
{
	var $id_price;
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->data['page_title'] = 'Prices';

		$this->load->model('model_prices');
		$this->load->model('model_products');
		$this->load->model('model_company');
		$this->load->model('model_brands');
		$this->load->model('model_products_attributes');
		$this->load->model('model_attributes');
		$this->load->model('model_users');
		$this->load->model('model_currencies');
		$this->load->model('model_category');

		$this->load->library('enumstypestatusobject');
	}

	/* 
	* It only redirects to the manage price page
	*/
	public function index()
	{
		if (!in_array('viewPrice', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->data['page_title'] = 'ALL PRICES';
		$this->render_template('prices/index', $this->data);
	}

	/*
	* Fetches the prices data from the prices table 
	* this function is called from the datatable ajax function
	*/
	public function fetchPricesData()
	{
		$result = array('data' => array());
		$active = $this->enumstypestatusobject->enumsNum['active'];
		$data = $this->model_prices->getPricesData($active);

		foreach ($data as $key => $value) {
			$date = date('Y-m-d', $value['date_time']);
			$time = date('h:i a', $value['date_time']);
			$id =  $value['id'];
			$date_time = $date . ' ' . $time;
			$currency_name = $this->model_currencies->getCurrencyData($value['currency_id'])['name'];
			$name =  $value['name'];

			// button
			$buttons = '';
			if (in_array('updatePrice', $this->permission)) {
				$buttons .= ' <button type="button"  onclick=window.location.href="' . base_url('prices/update/' . $id) . '" class="label-base-icon-doc edit-doc"></button>';
			}
			if (in_array('deletePrice', $this->permission)) {
				$buttons .= ' <button type="button" class="label-base-icon-doc remove-doc" onclick="removeFunc(' . $id . ')" data-toggle="modal" data-target="#removeModal"></button>';
			}

			$result['data'][$key] = array(
				$id,
				$date_time,
				$name,
				$currency_name,
				$buttons
			);
		} // /foreach
		echo json_encode($result);
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchAddProductTable()
	{
		$result = array('data' => array());
		$brand_id = $this->input->post('category');
		if ($brand_id) {
			$data = $this->model_products->getProductFiltrCategoryData($brand_id);
			foreach ($data as $key => $value) {
				$result['data'][$key] = array(
					$value['id'],
					$value['name'],
				);
			} // /foreach

			echo json_encode($result);
		} else {
			echo json_encode('');
		}
	}

	public function fetchSetFillProductTable($id_price)
	{
		$result = array('data' => array());
		$prices_data = $this->model_prices->getPricesDataById($id_price);

		$prices_items = $this->model_prices->getPricesItemData($prices_data['id']);

		foreach ($prices_items as $key => $value) {
			$id  =  $value['id'];
			$product_id  =  $this->model_products->getProductData($value['product_id'])['id'];
			$name_product  =  $this->model_products->getProductData($value['product_id'])['name'];
			$name_option  =  $this->model_attributes->getAttributeValueById($value['attribute_id'])['value'];
			$price  =  $value['price'];
			$it_count  =  $key;
			$id_option  =  $value['attribute_id'];

			$result['data'][$key] = array(
				'id' =>	$id,
				'idProduct' =>	$product_id,
				"nameProduct" => $name_product,
				'nameOption' =>	$name_option,
				'price' =>	$price,
				'it_count' =>	$it_count,
				'idOption' => $id_option
			);
		}
		return $result;
	}

	public function fetchAddAttributeTable()
	{
		$result = array('data' => array());
		$product_id = $this->input->post('product_id');
		if ($product_id) {
			$data = $this->model_products_attributes->getProductsAttributesData($product_id);
			foreach ($data as $key => $value) {
				$name_attributes  = $this->model_attributes->getAttributeValueById($value['attribute_id']);
				$result['data'][$key] = array(
					$value['attribute_id'],
					$name_attributes['value'],
					"",
				);
			} // /foreach
			echo json_encode($result);
		} else {
			echo json_encode('');
		}
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
			$product_data = $this->model_prices->getProductData($product_id);
			echo json_encode($product_data);
		}
	}

	/*
	* If the validation is not valid, then it redirects to the create page.
	* If the validation for each input field is valid then it inserts the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function create()
	{
		if (!in_array('createPrice', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->data['page_title'] = 'Add Price';

		$this->form_validation->set_rules('currency_id', 'Currency', 'trim|required');
		$this->form_validation->set_rules('name', 'Price Name', 'trim|required');
		$this->form_validation->set_rules('product[]', 'Products', 'trim|required');
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

		if ($this->form_validation->run() == TRUE) {
			$user_id = $this->session->userdata('id');
			$data_header = array(
				'user_id' => $user_id,
				'date_time' => strtotime(date('Y-m-d h:i:s a')),
				'currency_id' => $this->input->post('currency_id'),
				'name' => $this->input->post('name'),
			);
			$data =  array(
				'data_header' => $data_header,
			);
			$create = $this->model_prices->create($data);

			if ($create) {
				$this->session->set_flashdata('success', 'Successfully created');
				redirect('prices/', 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('prices/create/', 'refresh');
			}
		} else {
			$active = $this->enumstypestatusobject->enumsNum['active'];
			$this->data['username'] = $this->session->userdata('username');
			$this->data['currencies'] = $this->model_currencies->getActiveCurrencies();
			// false case
			$company = $this->model_company->getCompanyData($active);
			$this->data['company_data'] = $company;
			$this->data['brands'] = $this->model_brands->getActiveBrands();
			$this->data['category'] = $this->model_category->getActiveCategroy();

			$this->render_template('prices/create', $this->data);
		}
	}

	/*
	* If the validation is not valid, then it redirects to the edit prices page 
	* If the validation is successfully then it updates the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function update($id)
	{
		if (!in_array('updatePrice', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		if (!$id) {
			redirect('dashboard', 'refresh');
		}

		$this->data['page_title'] = 'Update Price';
		$this->form_validation->set_rules('currency_id', 'Currency', 'trim|required');
		$this->form_validation->set_rules('name', 'Price Name', 'trim|required');
		$this->form_validation->set_rules('product[]', 'Products', 'trim|required');
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

		if ($this->form_validation->run() == TRUE) {
			$user_id = $this->session->userdata('id');
			$data_header = array(
				'user_id' => $user_id,
				'date_time' => $this->input->post('doc_date_doc_time'),
				'currency_id' => $this->input->post('currency_id'),
				'name' => $this->input->post('name'),
			);
			$data =  array(
				'data_header' => $data_header,
			);

			$update = $this->model_prices->update($id, $data);
			if ($update == true) {
				$this->session->set_flashdata('success', 'Successfully updated');
				redirect('prices/update/' . $id, 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('prices/update/' . $id, 'refresh');
			}
		} else {
			$this->data['list_products'] = $this->fetchSetFillProductTable($id);
			$this->data['products'] = $this->model_products->getActiveProductData();
			$this->data['brands'] = $this->model_brands->getActiveBrands();
			$this->data['currencies'] = $this->model_currencies->getActiveCurrencies();
			$this->data['category'] = $this->model_category->getActiveCategroy();

			$price_header = $this->model_prices->getPricesDataById($id);
			$this->data['price_header']['currency_id'] = $price_header['currency_id'];
			$this->data['price_header']['username'] = $this->model_users->getUserData($price_header['user_id'])['username'];
			$this->data['price_header']['id'] = $price_header['id'];
			$this->data['price_header']['name'] = $price_header['name'];
			$this->data['price_header']['doc_date'] =  date('d-m-Y', $price_header['date_time']);
			$this->data['price_header']['doc_time'] =  date('h:i a', $price_header['date_time']);
			$this->data['price_header']['doc_date_doc_time'] =  $price_header['date_time'];

			$this->render_template('prices/edit', $this->data);
		}
	}

	/*
	* It removes the data from the database
	* and it returns the response into the json format
	*/
	public function remove()
	{
		if (!in_array('deletePrice', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$price_id = $this->input->post('price_id');
		$response = array();
		if ($price_id) {
			$delete = $this->model_prices->deactive($price_id);
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
