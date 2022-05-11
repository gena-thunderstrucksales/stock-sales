<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Taxes extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Taxes';

		$this->load->model('model_taxes');
		$this->load->library('helper');
	}

	/* 
	* It only redirects to the manage product page and
	*/
	public function index()
	{
		if(!in_array('viewTax', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$result = $this->model_taxes->getActiveTaxes();

		$this->data['results'] = $result;

		$this->render_template('taxes/index', $this->data);
	}

	/*
	* Fetches the tax data from the tax table 
	* this function is called from the datatable ajax function
	*/
	public function fetchTaxData()
	{
		$result = array('data' => array());

	$data = $this->model_taxes->getActiveTaxes();
		foreach ($data as $key => $value) {

			// button
			$buttons = '';

			if(in_array('viewTax', $this->permission)) {
				$buttons .= '<button type="button" class="label-base-icon-doc edit-doc" onclick="editTax(' . $value['id'] . ')" data-toggle="modal"  data-toggle="modal" data-target="#editTaxModal"></button>';	
			}
			
			if(in_array('deleteTax', $this->permission)) {
				$buttons .= ' <button type="button" class="label-base-icon-doc remove-doc" onclick="removeTax(' . $value['id'] . ')" data-toggle="modal"  data-toggle="modal" data-target="#removeTaxModal"></button>
				';
			}	
		
			$result['data'][$key] = array(
				$value['id'],
				$value['name'],
				$value['rate'],
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	/*
	* It checks if it gets the tax id and retreives
	* the tax information from the tax model and 
	* returns the data into json format. 
	* This function is invoked from the view page.
	*/
	public function fetchTaxesDataById($id)
	{
		if($id) {
			$data = $this->model_taxes->getActiveTaxes($id);
			echo json_encode($data);
		}

		return false;
	}

	/*
	* Its checks the tax form validation 
	* and if the validation is successfully then it inserts the data into the database 
	* and returns the json format operation messages
	*/
	public function create()
	{

		if(!in_array('createTax', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		$this->form_validation->set_rules('tax_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('tax_rate', 'Rate', 'trim|required');
		$this->form_validation->set_rules('active', 'Active', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
        	$data = array(
        		'name' => $this->input->post('tax_name'),
				'rate' => $this->input->post('tax_rate'),
        		'active' => $this->input->post('active'),	
        	);

        	$create = $this->model_taxes->create($data);
        	if($create == true) {
        		$response['success'] = true;
        		$response['messages'] = 'Succesfully created';
        	}
        	else {
        		$response['success'] = false;
        		$response['messages'] = 'Error in the database while creating the tax information';			
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
	* Its checks the tax form validation 
	* and if the validation is successfully then it updates the data into the database 
	* and returns the json format operation messages
	*/
	public function update($id)
	{
		if(!in_array('updateTax', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		if($id) {

			$this->form_validation->set_rules('edit_tax_name', 'Name', 'trim|required');
			$this->form_validation->set_rules('edit_rate', 'Rate', 'trim|required');
			$this->form_validation->set_rules('edit_active', 'Active', 'trim|required');

			//$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

	        if ($this->form_validation->run() == TRUE) {
	        	$data = array(
	        	'name' => $this->input->post('edit_tax_name'),
				'rate' => $this->input->post('edit_rate'),
        		'active' => $this->input->post('edit_active'),	
	        	);

	        	$update = $this->model_taxes->update($data, $id);
	        	if($update == true) {
	        		$response['success'] = true;
	        		$response['messages'] = 'Succesfully updated';
	        	}
	        	else {
	        		$response['success'] = false;
	        		$response['messages'] = 'Error in the database while updated the tax information';			
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
	* It removes the tax information from the database 
	* and returns the json format operation messages
	*/
	public function remove()
	{
		if(!in_array('deleteTax', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		
		$tax_id = $this->input->post('tax_id');
		$response = array();
		if($tax_id) {
			$arr_check = $this->helper->parse_answer_links($this->model_taxes->exist_links($tax_id));
			if ($arr_check ) {
				$response['success'] = false;
				$response['messages'] = "This item has some links for " . $arr_check . ' cannot be removed now!';
			} else {
				$delete = $this->model_taxes->deactive($tax_id);
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