<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ReportPricesList extends Admin_Controller
{
	var $data_filter = array(
		'product_id' => '%',
		'active' => '1',
		'end_date' => '',
		'currency_id' => '%',
	);

	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->data['page_title'] = 'Report Prices List '.date('Y-m-d');
		$this->load->model('model_prices');
		$this->load->model('model_products');
		$this->load->model('model_currencies');
		$this->load->library('enumstypestatusobject');
	}

	/* 
    * It redirects to the report page
    * and based on the year, all the orders data are fetch from the database.
    */
	
	public function index()
	{
		if (!in_array('viewReportPricesList', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$this->data_filter['end_date'] = date('Y-m-d h:i:s a');
		$this->data['data_filter']  = $this->data_filter;

		$this->data['currencies'] = $this->model_currencies->getActiveCurrencies();
		$this->data['products'] = $this->model_products->getProductData();
		$this->render_template('reports/report-prices-list', $this->data);
	}

	public function generateReport()
	{
		if (!in_array('viewReportPricesList', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$active = $this->enumstypestatusobject->enumsNum['active'];
		$this->data['products'] = $this->model_products->getProductData();
		$this->data['currencies'] = $this->model_currencies->getActiveCurrencies();

		$this->form_validation->set_rules('product_id', 'Product name', 'trim|required');
		$this->form_validation->set_rules('end_date', 'End date', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$product_id = $this->input->post('product_id');
			$end_date = $this->input->post('end_date');
			$currency_id = $this->input->post('currency_id');

			$this->data_filter = array(
				'product_id' => $product_id,
				'active' => $active,
				'end_date' => $end_date,
				'currency_id' => $currency_id,
			);

			$this->data['page_title'] = 'Report Price List '.$end_date;
			$this->data['data_filter']  = $this->data_filter;
			$this->data['results'] = $this->model_prices->getLastPrices($this->data_filter);
			$this->render_template('reports/report-prices-list', $this->data);
		} else {
			$this->data['data_filter']  = $this->data_filter;
			$this->render_template('reports/report-prices-list', $this->data);
		}
	}
}
