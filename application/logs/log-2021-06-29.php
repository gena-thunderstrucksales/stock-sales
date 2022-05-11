<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-06-29 08:08:35 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'AND discounts.priority_id = 0
		ORDER BY discounts.date_time DESC limit 1' at line 10 - Invalid query: SELECT * FROM discounts_item 
		RIght JOIN  discounts ON discounts_item.discount_id = discounts.id 
		WHERE product_id = '27' AND attribute_id = '14' AND discounts.date_time <= 1624972110 
		AND discounts.start_date <= 1624972110 AND 
		discounts.end_date >= 1624972110 AND
		discounts.band_start <= 1 AND 
		discounts.band_end >= 1 AND 
		discounts.active = 1 AND 
		discounts.currency_id = '2' AND
		AND discounts.priority_id = 0
		ORDER BY discounts.date_time DESC limit 1
ERROR - 2021-06-29 08:09:54 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'AND discounts.priority_id = 0
		ORDER BY discounts.date_time DESC limit 1' at line 10 - Invalid query: SELECT * FROM discounts_item 
		RIght JOIN  discounts ON discounts_item.discount_id = discounts.id 
		WHERE product_id = '27' AND attribute_id = '14' AND discounts.date_time <= 1624972185 
		AND discounts.start_date <= 1624972185 AND 
		discounts.end_date >= 1624972185 AND
		discounts.band_start <= 1 AND 
		discounts.band_end >= 1 AND 
		discounts.active = 1 AND 
		discounts.currency_id = '2' AND
		AND discounts.priority_id = 0
		ORDER BY discounts.date_time DESC limit 1
