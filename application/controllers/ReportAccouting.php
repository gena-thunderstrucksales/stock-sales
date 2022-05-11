<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ReportAccouting extends Admin_Controller
{
	var $data_filter = array(
		'customer_id' => '%',
		'user_id' => '%',
		'active' => '1',
		'start_date' => '',
		'end_date' => '',
		'currency_id' => '%',
	);

	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->data['page_title'] = 'Report Accouting '.date('Y-m-d');
		$this->load->model('model_report_payments_orders');
		$this->load->model('model_customers');
		$this->load->model('model_users');
		$this->load->model('model_currencies');
		$this->load->library('enumstypestatusobject');
	}

	/* 
    * It redirects to the report page
    * and based on the year, all the orders data are fetch from the database.
    */
	
	public function index()
	{

		if (!in_array('viewReportAccouting', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$active = $this->enumstypestatusobject->enumsNum['active'];
		$customer_id = $this->input->post('customer_id');

		$this->data_filter['start_date'] = date('Y-m-d h:i:s a',  strtotime("-30 days"));
		$this->data_filter['end_date'] = date('Y-m-d h:i:s a');

		$this->data['currencies'] = $this->model_currencies->getActiveCurrencies();

		$this->data['data_filter']  = $this->data_filter;

		$this->data['customers'] = $this->model_customers->getCustomerListData($active, $customer_id);
		$this->data['users'] = $this->model_users->getUserData();
		$this->render_template('reports/report-payments-orders', $this->data);
	}

	public function generateReport()
	{
		if (!in_array('viewReportAccouting', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$active = $this->enumstypestatusobject->enumsNum['active'];
		$this->data['customers'] = $this->model_customers->getCustomerListData($active);
		$this->data['users'] = $this->model_users->getUserData();
		$this->data['currencies'] = $this->model_currencies->getActiveCurrencies();

		$this->form_validation->set_rules('currency_id', 'Currency', 'trim|required');
		$this->form_validation->set_rules('customer_id', 'Customer name', 'trim|required');
		$this->form_validation->set_rules('user_id', 'User name', 'trim|required');
		$this->form_validation->set_rules('start_date', 'Start date', 'trim|required');
		$this->form_validation->set_rules('end_date', 'End date', 'trim|required');

		if ($this->form_validation->run() == TRUE) {

			$customer_id = $this->input->post('customer_id');
			$user_id = $this->input->post('user_id');
			$currency_id = $this->input->post('currency_id');

			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');

			$this->data_filter = array(
				'customer_id' => $customer_id,
				'user_id' => $user_id,
				'active' => $active,
				'start_date' => $start_date,
				'end_date' => $end_date,
				'currency_id' => $currency_id,
			);

			$this->data['page_title'] = 'Report Accouting '.$start_date.' / '.$end_date;
			$this->data['data_filter']  = $this->data_filter;
			$this->data['results'] = $this->model_report_payments_orders->getReport($this->data_filter);
			$this->render_template('reports/report-payments-orders', $this->data);
		} else {

	
			$this->data['data_filter']  = $this->data_filter;
			$this->render_template('reports/report-payments-orders', $this->data);
		}
	}
}
