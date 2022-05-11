<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-07-16 16:07:19 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/ordersStatus/create.php 77
ERROR - 2021-07-16 16:08:38 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/ordersStatus/create.php 77
ERROR - 2021-07-16 16:08:49 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/ordersStatus/create.php 77
ERROR - 2021-07-16 16:09:40 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/payments/create.php 75
ERROR - 2021-07-16 16:12:20 --> Severity: Warning --> Invalid argument supplied for foreach() /Applications/MAMP/htdocs/stock/application/views/payments/create.php 75
ERROR - 2021-07-16 16:33:17 --> Query error: Expression #2 of ORDER BY clause is not in GROUP BY clause and contains nonaggregated column 'stock.m.date_time' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT 
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
                              o.`date_time`>= 1622523600 AND 
                              o.`date_time`<= 1626498000						 
				    WHERE 
							  m.`customer_id` LIKE 492 AND
							  m.`currency_id` LIKE 2 AND
							  m.`user_id` LIKE '%' AND
							  m.`active` LIKE 1 AND
							  m.`date_time`<= 1626498000 
                             	
		GROUP BY 
			m.`customer_id`
		ORDER BY customer_name ASC , m.`date_time` ASC
