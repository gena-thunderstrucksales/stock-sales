<?php

class Dashboard extends Admin_Controller
{

	var $data_filter = array(
		'active' => '1',
		'start_date' => '',
		'end_date' => '',
		'currency_id' => '%',
	);

	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Dashboard';
		$this->load->model('model_dashboard');
		$this->load->library('enumstypestatusobject');
		$this->load->model('model_currencies');
		$this->load->model('model_company');

		$company = $this->model_company->getCompanyData();

		$this->data_filter['start_date'] = date('M j, Y', strtotime("-30 days"));
		$this->data_filter['end_date'] = date('M j, Y');

		$this->data_filter['currency_id'] = $company['currency_id'];

		$this->data['data_filter']  = $this->data_filter;
		$this->data['currencies'] = $this->model_currencies->getActiveCurrencies();

	}

	/* 
	* It only redirects to the manage category page
	* It passes the total product, total paid orders, total users, and total stores information
	into the frontend.
	*/
	public function index()
	{
		if (!in_array('viewDashboard', $this->permission)) {
			redirect('orders', 'refresh');
		}
		$this->render_template('dashboard', $this->data);
	}

	public function onChangeCurrency(){
		$currency_id = $this->input->post('currency_id');
		$this->add_temp_currency($currency_id);
		echo json_encode('');
	}


    public function add_temp_currency($currency_id = null)
    {
        if (!$this->session->userdata('temp_currency_id')) {
            $temp_currency_id = '';
        } else {
            $temp_currency_id = $this->session->userdata('temp_currency_id');
        }
        $temp_currency_id =  $currency_id;
        $this->session->set_userdata('temp_currency_id',  $temp_currency_id);
    }

	public function getTopPerformingProduct()
	{

		if($this->session->userdata('temp_currency_id')){
			$this->data_filter['currency_id'] =   $this->session->userdata('temp_currency_id');
		} else{
			$company = $this->model_company->getCompanyData();
			$this->data_filter['currency_id'] = $company['currency_id'];
		}
		$start_date =  $this->data_filter['start_date'];
		$end_date = $this->data_filter['end_date'];
		$current_period = $start_date.' - '.$end_date ;

		$result = array('data' => array());
		$result_request = $this->model_dashboard->getTopPerformingProduct($this->data_filter);

		foreach ($result_request as $key => $value) {
			$result['data'][$key] = array(
			$value['productsname'],
			$value['categoriesname'],
			$current_period,
			'<sup>$</sup>'.number_format($value['total'],2),
		);
		}
		echo json_encode($result);
	}

	public function getSelesByBrand()
	{
		if($this->session->userdata('temp_currency_id')){
			$this->data_filter['currency_id'] =   $this->session->userdata('temp_currency_id');
		} else{
			$company = $this->model_company->getCompanyData();
			$this->data_filter['currency_id'] = $company['currency_id'];
		}
		$start_date =  $this->data_filter['start_date'];
		$end_date = $this->data_filter['end_date'];
		$current_period = $start_date.' - '.$end_date ;

		$result = array('data' => array());
		$result_request = $this->model_dashboard->getSelesByBrand($this->data_filter);

		foreach ($result_request as $key => $value) {
			$result['data'][$key] = array(
			$value['brandsname'],
			$current_period,
			'<sup>$</sup>'.number_format($value['total'],2),
		);
		}
		echo json_encode($result);
	}


	public function getTopPerformingMembers()
	{
		
		if($this->session->userdata('temp_currency_id')){
			$this->data_filter['currency_id'] =   $this->session->userdata('temp_currency_id');
		} else{
			$company = $this->model_company->getCompanyData();
			$this->data_filter['currency_id'] = $company['currency_id'];
		}
		
		$result = array('data' => array());
		$result_request = $this->model_dashboard->getTopPerformingMembers($this->data_filter);
		$start_date =  $this->data_filter['start_date'];
		$end_date = $this->data_filter['end_date'];
		$current_period = $start_date.' - '.$end_date ;
		
		foreach ($result_request as $key => $value) {
			$result['data'][$key] = array(
			$value['username'],
			$current_period,
			'<sup>$</sup>'.number_format($value['total'],2),
		);
		}
		echo json_encode($result);
	}


	public function getTableTotalSales()
	{

		if($this->session->userdata('temp_currency_id')){
			$this->data_filter['currency_id'] =   $this->session->userdata('temp_currency_id');
		} else{
			$company = $this->model_company->getCompanyData();
			$this->data_filter['currency_id'] = $company['currency_id'];
		}
	
		$result = array('data' => array());
		$result_request = $this->model_dashboard->getTableTotalSales($this->data_filter);

		foreach ($result_request as $key => $value) {
			$result['data'][$key] = array(
				'<sup>$</sup>'.number_format($value['total'],2),
		);
		}
		echo json_encode($result);
	}

}
