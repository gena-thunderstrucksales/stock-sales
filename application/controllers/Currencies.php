<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Currencies extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Currencys';

		$this->load->model('model_currencies');
		$this->load->library('helper');
	}

	/* 
	* It only redirects to the manage product page and
	*/
	public function index()
	{
		if(!in_array('viewCurrency', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$result = $this->model_currencies->getCurrencyData();

		$this->data['results'] = $result;

		$this->render_template('currencies/index', $this->data);
	}

	/*
	* Fetches the currencie data from the currencie table 
	* this function is called from the datatable ajax function
	*/
	public function fetchCurrencyData()
	{
		$result = array('data' => array());

		$data = $this->model_currencies->getCurrencyData();
		foreach ($data as $key => $value) {

			// button
			$buttons = '';

			if(in_array('viewCurrency', $this->permission)) {
				$buttons .= '<button type="button" class="label-base-icon-doc edit-doc" onclick="editCurrency(' . $value['id'] . ')" data-toggle="modal"  data-toggle="modal" data-target="#editCurrencyModal"></button>';	
			}
			
			if(in_array('deleteCurrency', $this->permission)) {
				$buttons .= ' <button type="button" class="label-base-icon-doc remove-doc" onclick="removeCurrency(' . $value['id'] . ')" data-toggle="modal"  data-toggle="modal" data-target="#removeCurrencyModal"></button>
				';
			}	
		
			$result['data'][$key] = array(
				$value['id'],
				$value['name'],
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	/*
	* It checks if it gets the currencie id and retreives
	* the currencie information from the currencie model and 
	* returns the data into json format. 
	* This function is invoked from the view page.
	*/
	public function fetchCurrencyDataById($id)
	{
		if($id) {
			$data = $this->model_currencies->getCurrencyData($id);
			echo json_encode($data);
		}
		return false;
	}

	/*
	* Its checks the currencie form validation 
	* and if the validation is successfully then it inserts the data into the database 
	* and returns the json format operation messages
	*/
	public function create()
	{

		if(!in_array('createCurrency', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		$this->form_validation->set_rules('currency_name', 'currency name', 'trim|required');
		$this->form_validation->set_rules('active', 'Active', 'trim|required');

		//$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

        if ($this->form_validation->run() == TRUE) {
        	$data = array(
        		'name' => $this->input->post('currency_name'),
        		'active' => $this->input->post('active'),	
        	);

        	$create = $this->model_currencies->create($data);
        	if($create == true) {
        		$response['success'] = true;
        		$response['messages'] = 'Succesfully created';
        	}
        	else {
        		$response['success'] = false;
        		$response['messages'] = 'Error in the database while creating the currencie information';			
        	}
        }
        else {
        	$response['success'] = false;
        	foreach ($_POST as $key => $value) {
        		$response['messages'][$key] = form_error($key);
        	}
        }

        echo json_encode($response);

	}

	/*
	* Its checks the currencie form validation 
	* and if the validation is successfully then it updates the data into the database 
	* and returns the json format operation messages
	*/
	public function update($id)
	{
		if(!in_array('updateCurrency', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		if($id) {
			$this->form_validation->set_rules('edit_currency_name', 'Currency name', 'trim|required');
			$this->form_validation->set_rules('edit_active', 'Active', 'trim|required');

			$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

	        if ($this->form_validation->run() == TRUE) {
	        	$data = array(
	        		'name' => $this->input->post('edit_currency_name'),
	        		'active' => $this->input->post('edit_active'),	
	        	);

	        	$update = $this->model_currencies->update($data, $id);
	        	if($update == true) {
	        		$response['success'] = true;
	        		$response['messages'] = 'Succesfully updated';
	        	}
	        	else {
	        		$response['success'] = false;
	        		$response['messages'] = 'Error in the database while updated the currencie information';			
	        	}
	        }
	        else {
	        	$response['success'] = false;
	        	foreach ($_POST as $key => $value) {
	        		$response['messages'][$key] = form_error($key);
	        	}
	        }
		}
		else {
			$response['success'] = false;
    		$response['messages'] = 'Error please refresh the page again!!';
		}

		echo json_encode($response);
	}

	/*
	* It removes the currencie information from the database 
	* and returns the json format operation messages
	*/
	public function remove()
	{
		if(!in_array('deleteCurrency', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		
		$currencie_id = $this->input->post('currencie_id');
		$response = array();
		if($currencie_id) {
			$arr_check = $this->helper->parse_answer_links($this->model_currencies->exist_links($currencie_id));
			if ($arr_check) {
				$response['success'] = false;
				$response['messages'] = "This item has some links for " . $arr_check . ' cannot be removed now!';
			} else {
				$delete = $this->model_currencies->deactive($currencie_id);
				if ($delete == true) {
					$response['success'] = true;
					$response['messages'] = "Successfully removed";
				} else {
					$response['success'] = false;
					$response['messages'] = "Error in the database while removing the item";
				}
			}
		}
		else {
			$response['success'] = false;
			$response['messages'] = "Refersh the page again!!";
		}

		echo json_encode($response);
	}

}