<?php

class Model_report_sales extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the report_payments_orders  data */
	public function getReport($data_filter = null)
	{
		$result  = array();
		if ($data_filter) {
			$result['data_users_brands'] = $this->getBalanceByDetailsUsersBrands($data_filter);
			$result['data_users'] = $this->getBalanceByDetailsUsers($data_filter);
		}
		return $result;
	}

	public function  getBalanceByDetailsUsersBrands($data_filter = null)
	{
		$brand_id = $data_filter['brand_id'];
		$user_id = $data_filter['user_id'];
		$active = $data_filter['active'];
		$start_date = strtotime($data_filter['start_date']);
		$end_date = strtotime($data_filter['end_date'] . '+1 day');
		$currency_id = $data_filter['currency_id'];
		$onlyDealer = 1;

		$sql =	"SELECT 
		SUM(`orders`.`total_order`) AS `total`,
		`brands`.`name` AS `brandname`,
		`brands`.`id` AS `brandid`,
		`users`.`username`,
		`users`.`commission_ess`,
		`users`.`commission_andersons`
		FROM `orders_item` 

		INNER JOIN `products` ON (`orders_item`.`product_id` = `products`.`id`)
		INNER JOIN `brands` ON (`products`.`brand_id` = `brands`.`id`)
		INNER JOIN `orders` ON (`orders_item`.`order_id` = `orders`.`id`)
		INNER JOIN `users` ON (`orders`.`user_id` = `users`.`id`)
	
		left join (
			SELECT `id`,`order_id`,`type_status_id`
            FROM orders_status
            WHERE `id` IN (
            SELECT MAX(`id`)
            FROM orders_status
            GROUP BY `order_id`
           )
        ) os ON os.order_id = orders.id

		WHERE 
		brands.`id` LIKE $brand_id AND
		orders.`currency_id` LIKE $currency_id AND
		orders.`user_id` LIKE $user_id AND
	    orders.`active` LIKE $active AND
		orders.`date_time`>= $start_date AND 
        orders.`date_time`<= $end_date AND
		orders.`type_customer_id` = $onlyDealer and
		os.`type_status_id` = 3 
		    	
		GROUP BY 
		`brandname`,
		`brandid`,
		`users`.`username`,
		`users`.`commission_ess`,
		`users`.`commission_andersons`
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function  getBalanceByDetailsUsers($data_filter = null)
	{
		$brand_id = $data_filter['brand_id'];
		$user_id = $data_filter['user_id'];
		$active = $data_filter['active'];
		$start_date = strtotime($data_filter['start_date']);
		$end_date = strtotime($data_filter['end_date'] . '+1 day');
		$currency_id = $data_filter['currency_id'];
		$onlyDealer = 1;

		$sql =	"SELECT 
		SUM(`orders`.`total_order`) AS `total`,
		`users`.`username`
		FROM `orders_item` 

		INNER JOIN `products` ON (`orders_item`.`product_id` = `products`.`id`)
		INNER JOIN `brands` ON (`products`.`brand_id` = `brands`.`id`)
		INNER JOIN `orders` ON (`orders_item`.`order_id` = `orders`.`id`)
		INNER JOIN `users` ON (`orders`.`user_id` = `users`.`id`)

		left join (
			SELECT `id`,`order_id`,`type_status_id`
            FROM orders_status
            WHERE `id` IN (
            SELECT MAX(`id`)
            FROM orders_status
            GROUP BY `order_id`
           )
        ) os ON os.order_id = orders.id

		WHERE 
		brands.`id` LIKE $brand_id AND
		orders.`currency_id` LIKE $currency_id AND
		orders.`user_id` LIKE $user_id AND
	    orders.`active` LIKE $active AND
		orders.`date_time`>= $start_date AND 
        orders.`date_time`<= $end_date AND
		orders.`type_customer_id` = $onlyDealer and
		os.`type_status_id` = 3 
                             	
		GROUP BY 
		`users`.`username`";

		$query = $this->db->query($sql);
		return $query->result_array();
	}
}
