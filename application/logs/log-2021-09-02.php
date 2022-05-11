<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-09-02 10:15:17 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/payments/create.php 75
ERROR - 2021-09-02 11:08:16 --> Query error: Column 'customer_id' cannot be null - Invalid query: INSERT INTO `orders_status` (`user_id`, `date_time`, `order_id`, `customer_id`, `type_status_id`, `table_type_id`) VALUES ('9', 1630598881, '205', NULL, '1', 4)
ERROR - 2021-09-02 11:25:02 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/payments/create.php 75
ERROR - 2021-09-02 13:39:31 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/payments/create.php 75
ERROR - 2021-09-02 14:13:35 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/ordersStatus/create.php 77
ERROR - 2021-09-02 14:15:20 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/ordersStatus/create.php 77
ERROR - 2021-09-02 14:15:33 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/ordersStatus/create.php 77
ERROR - 2021-09-02 14:16:07 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/ordersStatus/create.php 77
ERROR - 2021-09-02 14:16:26 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/ordersStatus/create.php 77
ERROR - 2021-09-02 14:16:48 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/ordersStatus/create.php 77
ERROR - 2021-09-02 14:17:05 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/ordersStatus/create.php 77
ERROR - 2021-09-02 14:18:26 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/ordersStatus/create.php 77
ERROR - 2021-09-02 14:18:30 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/payments/create.php 75
ERROR - 2021-09-02 14:20:18 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/payments/create.php 75
ERROR - 2021-09-02 14:20:46 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/payments/create.php 75
ERROR - 2021-09-02 14:21:11 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/payments/create.php 75
ERROR - 2021-09-02 14:21:32 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/ordersStatus/create.php 77
ERROR - 2021-09-02 14:23:45 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/payments/create.php 75
ERROR - 2021-09-02 14:24:28 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/ordersStatus/create.php 77
ERROR - 2021-09-02 14:25:15 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/ordersStatus/create.php 77
ERROR - 2021-09-02 14:25:48 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/ordersStatus/create.php 77
ERROR - 2021-09-02 14:25:52 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/ordersStatus/create.php 77
ERROR - 2021-09-02 14:26:02 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/payments/create.php 75
ERROR - 2021-09-02 14:26:59 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/ordersStatus/create.php 77
ERROR - 2021-09-02 14:27:13 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/payments/create.php 75
ERROR - 2021-09-02 14:29:39 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/payments/create.php 75
ERROR - 2021-09-02 14:36:43 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/ordersStatus/create.php 77
ERROR - 2021-09-02 14:37:12 --> Query error: Expression #2 of ORDER BY clause is not in GROUP BY clause and contains nonaggregated column 'stock.m.date_time' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT 
		   m.`customer_id` AS customer_id,
		   t.`balance` as balance,
		   c.name as customer_name,
		   SUM(COALESCE(CASE WHEN o.balance > 0 THEN o.balance END,0)) AS total_order,
           SUM(COALESCE(CASE WHEN o.balance < 0 THEN o.balance END,0)) AS total_payment
		FROM `report_payments_orders` m
			JOIN (
					SELECT 
						`customer_id`,
						SUM(`balance`) AS `balance`
					FROM 
						`report_payments_orders`
				GROUP BY 
			`customer_id`
				) t ON t.`customer_id` = m.`customer_id` 
				    LEFT JOIN `customers` c ON 
				              c.id = m.customer_id
				    LEFT JOIN `report_payments_orders` o ON o.id = m.id AND 
                              o.`date_time`>= 1627966800 AND 
                              o.`date_time`<= 1630645200						 
				    WHERE 
							  m.`customer_id` LIKE '%' AND
							  m.`currency_id` LIKE 1 AND
							  m.`user_id` LIKE 9 AND
							  m.`active` LIKE 1 AND
							  m.`date_time`<= 1630645200 
                             	
		GROUP BY 
			m.`customer_id`
		ORDER BY customer_name ASC , m.`date_time` ASC
ERROR - 2021-09-02 14:42:51 --> Query error: Expression #2 of ORDER BY clause is not in GROUP BY clause and contains nonaggregated column 'stock.m.date_time' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT 
		   m.`customer_id` AS customer_id,
		   t.`balance` as balance,
		   c.name as customer_name,
		   SUM(COALESCE(CASE WHEN o.balance > 0 THEN o.balance END,0)) AS total_order,
           SUM(COALESCE(CASE WHEN o.balance < 0 THEN o.balance END,0)) AS total_payment
		FROM `report_payments_orders` m
			JOIN (
					SELECT 
						`customer_id`,
						SUM(`balance`) AS `balance`
					FROM 
						`report_payments_orders`
				GROUP BY 
			`customer_id`
				) t ON t.`customer_id` = m.`customer_id` 
				    LEFT JOIN `customers` c ON 
				              c.id = m.customer_id
				    LEFT JOIN `report_payments_orders` o ON o.id = m.id AND 
                              o.`date_time`>= 1627966800 AND 
                              o.`date_time`<= 1630645200						 
				    WHERE 
							  m.`customer_id` LIKE '%' AND
							  m.`currency_id` LIKE 2 AND
							  m.`user_id` LIKE '%' AND
							  m.`active` LIKE 1 AND
							  m.`date_time`<= 1630645200 
                             	
		GROUP BY 
			m.`customer_id`
		ORDER BY customer_name ASC , m.`date_time` ASC
ERROR - 2021-09-02 15:40:10 --> Severity: Notice --> Undefined variable: product_id /Applications/MAMP/htdocs/stock/application/views/products/create.php 127
ERROR - 2021-09-02 15:40:10 --> Severity: Notice --> Undefined variable: product_data /Applications/MAMP/htdocs/stock/application/views/products/create.php 128
ERROR - 2021-09-02 15:40:10 --> Severity: Notice --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/stock/application/views/products/create.php 128
ERROR - 2021-09-02 15:40:10 --> Severity: Notice --> Undefined variable: product_id /Applications/MAMP/htdocs/stock/application/views/products/create.php 383
ERROR - 2021-09-02 15:40:26 --> Severity: Notice --> Undefined variable: product_id /Applications/MAMP/htdocs/stock/application/views/products/create.php 127
ERROR - 2021-09-02 15:40:26 --> Severity: Notice --> Undefined variable: product_data /Applications/MAMP/htdocs/stock/application/views/products/create.php 128
ERROR - 2021-09-02 15:40:26 --> Severity: Notice --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/stock/application/views/products/create.php 128
ERROR - 2021-09-02 15:40:26 --> Severity: Notice --> Undefined variable: product_id /Applications/MAMP/htdocs/stock/application/views/products/create.php 383
ERROR - 2021-09-02 15:48:48 --> Severity: Notice --> Undefined variable: product_id /Applications/MAMP/htdocs/stock/application/views/products/create.php 127
ERROR - 2021-09-02 15:48:48 --> Severity: Notice --> Undefined variable: product_data /Applications/MAMP/htdocs/stock/application/views/products/create.php 128
ERROR - 2021-09-02 15:48:48 --> Severity: Notice --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/stock/application/views/products/create.php 128
ERROR - 2021-09-02 16:24:25 --> Severity: Notice --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/stock/application/controllers/Products.php 82
ERROR - 2021-09-02 16:24:25 --> Severity: Notice --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/stock/application/controllers/Products.php 92
ERROR - 2021-09-02 16:53:25 --> Severity: Notice --> Undefined variable: product_id /Applications/MAMP/htdocs/stock/application/views/products/create.php 395
ERROR - 2021-09-02 16:53:33 --> Severity: Notice --> Undefined variable: product_id /Applications/MAMP/htdocs/stock/application/views/products/create.php 395
ERROR - 2021-09-02 16:53:36 --> Severity: Notice --> Undefined variable: product_id /Applications/MAMP/htdocs/stock/application/views/products/create.php 395
ERROR - 2021-09-02 16:54:39 --> Severity: Notice --> Undefined variable: product_id /Applications/MAMP/htdocs/stock/application/views/products/create.php 340
ERROR - 2021-09-02 16:56:23 --> Severity: Notice --> Undefined variable: product_id /Applications/MAMP/htdocs/stock/application/views/products/create.php 339
