<?php

class Model_dashboard extends CI_Model
{

	public function __construct()
	{

		parent::__construct();
	}

	public function  getTopPerformingProduct($data_filter = null)
	{
		$active = $data_filter['active'];
		$start_date = strtotime($data_filter['start_date']);
		$end_date = strtotime($data_filter['end_date']);
		$currency_id = $data_filter['currency_id'];


		$sql =	"SELECT 
		SUM(`orders_item`.`total`) AS `total`,
		`products`.`name` AS `productsname`,
		`categories`.`name` AS `categoriesname`
		FROM `orders_item` 

		LEFT JOIN `products` ON (`orders_item`.`product_id` = `products`.`id`)
		LEFT JOIN `orders` ON (`orders_item`.`order_id` = `orders`.`id`)
		LEFT JOIN `categories` ON (`products`.`category_id` = `categories`.`id`)

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
		orders.`currency_id` LIKE $currency_id AND
	    orders.`active` LIKE $active AND
		orders.`date_time`>= $start_date AND 
        orders.`date_time`<= $end_date AND 
		os.`type_status_id` = 3 
   	
		GROUP BY 
		`productsname`,
		`categoriesname`
		

		ORDER BY 'total'
		DESC LIMIT 5
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function  getSelesByBrand($data_filter = null)
	{
		$active = $data_filter['active'];
		$start_date = strtotime($data_filter['start_date']);
		$end_date = strtotime($data_filter['end_date']);
		$currency_id = $data_filter['currency_id'];

		$sql =	"SELECT 
		SUM(`orders_item`.`total`) AS `total`,
		`brands`.`name` AS `brandsname`
		FROM `orders_item` 

		LEFT JOIN `products` ON (`orders_item`.`product_id` = `products`.`id`)
		LEFT JOIN `orders` ON (`orders_item`.`order_id` = `orders`.`id`)
		LEFT JOIN `brands` ON (`products`.`brand_id` = `brands`.`id`)

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
		orders.`currency_id` LIKE $currency_id AND
	    orders.`active` LIKE $active AND
		orders.`date_time`>= $start_date AND 
        orders.`date_time`<= $end_date AND 
		os.`type_status_id` = 3 
   	
		GROUP BY 
		`brandsname`
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function  getTopPerformingMembers($data_filter = null)
	{
		$active = $data_filter['active'];
		$start_date = strtotime($data_filter['start_date']);
		$end_date = strtotime($data_filter['end_date']);
		$currency_id = $data_filter['currency_id'];

		$sql =	"SELECT 
		SUM(`orders_item`.`total`) AS `total`,
		`users`.`username` AS `username`
		FROM `orders_item` 

		LEFT JOIN `orders` ON (`orders_item`.`order_id` = `orders`.`id`)
		LEFT JOIN `users` ON (`orders`.`user_id` = `users`.`id`)

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
		orders.`currency_id` LIKE $currency_id AND
	    orders.`active` LIKE $active AND
		orders.`date_time`>= $start_date AND 
        orders.`date_time`<= $end_date AND
		os.`type_status_id` = 3 
   	
		GROUP BY 
		`username`
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}


	public function  getTableTotalSales($data_filter = null)
	{
		$active = $data_filter['active'];
		$start_date = strtotime($data_filter['start_date']);
		$end_date = strtotime($data_filter['end_date']);
		$currency_id = $data_filter['currency_id'];
		

		$sql =	"SELECT SUM(m.`total`) AS `total` FROM
		(
		SELECT 
        o.`total_table_order` AS `total`,
		os.`type_status_id` as `type_status_id`
        FROM `orders` o

		left join (
			SELECT `id`,`order_id`,`type_status_id`
            FROM orders_status
            WHERE `id` IN (
            SELECT MAX(`id`)
            FROM orders_status
            GROUP BY `order_id`
           )
      
        ) os ON os.order_id = o.id

        WHERE 
        o.`currency_id` LIKE  $currency_id  AND
        o.`active` LIKE  $active AND
        o.`date_time`>= $start_date  AND 
        o.`date_time`<= $end_date 
	
		
		GROUP BY
		`total`) as m
		WHERE 	m.`type_status_id` = 3
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}
}