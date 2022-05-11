<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ReportSales extends Admin_Controller
{
	var $data_filter = array(
		'brand_id' => '%',
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
		$this->data['page_title'] = 'Report Sales ' . date('Y-m-d');
		$this->load->model('model_report_sales');
		$this->load->model('model_brands');
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
		$brand_id = $this->input->post('brand_id');

		$this->data_filter['start_date'] = date('Y-m-d h:i:s a',  strtotime("-30 days"));
		$this->data_filter['end_date'] = date('Y-m-d h:i:s a');

		$this->data['currencies'] = $this->model_currencies->getActiveCurrencies();

		$this->data['data_filter']  = $this->data_filter;

		$this->data['brands'] = $this->model_brands->getActiveBrands($active, $brand_id);
		$this->data['users'] = $this->model_users->getUserData();
		$this->render_template('reports/report-sales', $this->data);
	}

	public function generateReport()
	{
		if (!in_array('viewReportAccouting', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$active = $this->enumstypestatusobject->enumsNum['active'];
		$this->data['brands'] = $this->model_brands->getActiveBrands($active);
		$this->data['users'] = $this->model_users->getUserData();
		$this->data['currencies'] = $this->model_currencies->getActiveCurrencies();

		$this->form_validation->set_rules('currency_id', 'Currency', 'trim|required');
		$this->form_validation->set_rules('brand_id', 'Brand name', 'trim|required');
		$this->form_validation->set_rules('user_id', 'User name', 'trim|required');
		$this->form_validation->set_rules('start_date', 'Start date', 'trim|required');
		$this->form_validation->set_rules('end_date', 'End date', 'trim|required');

		if ($this->form_validation->run() == TRUE) {

			$brand_id = $this->input->post('brand_id');
			$user_id = $this->input->post('user_id');
			$currency_id = $this->input->post('currency_id');

			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');

			$this->data_filter = array(
				'brand_id' => $brand_id,
				'user_id' => $user_id,
				'active' => $active,
				'start_date' => $start_date,
				'end_date' => $end_date,
				'currency_id' => $currency_id,
			);


			$this->data['page_title'] = 'Report Accouting ' . $start_date . ' / ' . $end_date;
			$this->data['data_filter']  = $this->data_filter;

			$results_temp = $this->model_report_sales->getReport($this->data_filter);
			$results_temp_user = $results_temp['data_users'];
			$results_temp_data_users_brands = $results_temp['data_users_brands'];


			$result_body = [];
			$idAnderson = $this->data['brands'][0]['id'];
			$idEss = $this->data['brands'][1]['id'];

			//$commisionAnderson = $this->data['brands'][0]['commission'];
			//$commisionEss = $this->data['brands'][1]['commission'];


			$row_total = array(
				'username' => '',
				'ess_sales_total' => 0,
				'anderson_sales_total' => 0,
				'total_sales_total' => 0,
				'ess_commissions_total' => 0,
				'anderson_commissions_total' => 0,
				'total_commissions_total' => 0
			);

			foreach ($results_temp_user as $key => $value_user) {
				$total_by_user_sales = 0;
				$total_by_user_commissions = 0;

				$row = array(
					'username' => $value_user['username'],
					'ess_sales' => 0,
					'anderson_sales' => 0,
					'total_sales' => 0,
					'ess_commissions' => 0,
					'anderson_commissions' => 0,
					'total_commissions' => 0
				);

				foreach ($results_temp_data_users_brands as $key => $value) {
					if ($value_user['username'] == $value['username']) {

						if ($value['brandid'] == $idEss) {
							$row['ess_sales'] = $value['total'];
							$row['ess_commissions'] = $value['total']  *  $value['commission_ess'] / 100;
							$total_by_user_commissions += $row['ess_commissions'];
							//totaly
							$row_total['ess_sales_total'] += $value['total'];
							$row_total['ess_commissions_total'] += $total_by_user_commissions;
						}

						if ($value['brandid'] == $idAnderson) {
							$row['anderson_sales'] = $value['total'];
							$row['anderson_commissions'] = $value['total']  * $value['commisionAnderson'] / 100;
							$total_by_user_commissions += $row['anderson_commissions'];
							//totaly
							$row_total['anderson_sales_total'] += $value['total'];
							$row_total['anderson_commissions_total'] += $total_by_user_commissions;
						}

						if ($value['total']) {
							$total_by_user_sales +=  $value['total'];
							$row_total['total_sales_total'] += $value['total'];
						}

						//totaly
						$row_total['total_commissions_total'] = $row_total['anderson_commissions_total'] + $row_total['ess_commissions_total'];

						$row['total_sales'] = $total_by_user_sales;
						$row['total_commissions'] = $total_by_user_commissions;
					}
				}
				$result_body[] = $row;
			}
			$result_total[] = $row_total;

			$this->data['results']['data_total'] = $result_total;
			$this->data['results']['data_body'] = $result_body;
			$this->render_template('reports/report-sales', $this->data);
		} else {


			$this->data['data_filter']  = $this->data_filter;
			$this->render_template('reports/report-sales', $this->data);
		}
	}
}
