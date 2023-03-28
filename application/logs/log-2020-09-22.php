<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2020-09-22 04:06:46 --> Severity: Warning --> mysqli::real_connect(): (HY000/1044): Access denied for user 'dt_user'@'localhost' to database 'dt_ecommerce' /home/dutatunggal/public_html/system/database/drivers/mysqli/mysqli_driver.php 203
ERROR - 2020-09-22 04:06:46 --> Unable to connect to the database
ERROR - 2020-09-22 04:06:46 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/dutatunggal/public_html/system/core/Exceptions.php:271) /home/dutatunggal/public_html/system/core/Common.php 564
ERROR - 2020-09-22 04:09:22 --> Query error: Expression #2 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dt_commerce.op.name' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT SUM(op.quantity) as total, op.name, op.product_id
FROM `order_product` `op`
LEFT JOIN `order` `o` ON `o`.`order_id` = `op`.`order_id`
WHERE (o.date_added BETWEEN "2020-09-21 00:00:00" AND "2020-09-27 23:59:00")
GROUP BY `op`.`product_id`
ORDER BY `total` ASC
 LIMIT 10
ERROR - 2020-09-22 04:09:37 --> Query error: Expression #2 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dt_commerce.op.name' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT SUM(op.quantity) as total, op.name, op.product_id
FROM `order_product` `op`
LEFT JOIN `order` `o` ON `o`.`order_id` = `op`.`order_id`
WHERE (o.date_added BETWEEN "2020-09-21 00:00:00" AND "2020-09-27 23:59:00")
GROUP BY `op`.`product_id`
ORDER BY `total` ASC
 LIMIT 10
ERROR - 2020-09-22 04:09:48 --> Query error: Expression #2 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dt_commerce.op.name' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT SUM(op.quantity) as total, op.name, op.product_id
FROM `order_product` `op`
LEFT JOIN `order` `o` ON `o`.`order_id` = `op`.`order_id`
WHERE (o.date_added BETWEEN "2020-09-21 00:00:00" AND "2020-09-27 23:59:00")
GROUP BY `op`.`product_id`
ORDER BY `total` ASC
 LIMIT 10
ERROR - 2020-09-22 04:16:04 --> Could not find the language line "text_admins"
ERROR - 2020-09-22 04:16:38 --> Query error: Expression #7 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dt_commerce.l3.name' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT sc.*, l3.name as province, CONCAT_WS(" ", l2.type, l2.name) as city, l.name as subdistrict
FROM `shipping_cost` `sc`
LEFT JOIN `location` `l` ON `l`.`subdistrict_id` = `sc`.`subdistrict_id`
LEFT JOIN `location` `l2` ON `l2`.`city_id` = `sc`.`city_id` and `l2`.`subdistrict_id` = 0
LEFT JOIN `location` `l3` ON `l3`.`province_id` = `sc`.`province_id` and `l3`.`city_id` = 0
GROUP BY `sc`.`shipping_cost_id`
ORDER BY `shipping_cost_id` ASC
 LIMIT 10
ERROR - 2020-09-22 04:17:09 --> Query error: Expression #7 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dt_commerce.l3.name' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT sc.*, l3.name as province, CONCAT_WS(" ", l2.type, l2.name) as city, l.name as subdistrict
FROM `shipping_cost` `sc`
LEFT JOIN `location` `l` ON `l`.`subdistrict_id` = `sc`.`subdistrict_id`
LEFT JOIN `location` `l2` ON `l2`.`city_id` = `sc`.`city_id` and `l2`.`subdistrict_id` = 0
LEFT JOIN `location` `l3` ON `l3`.`province_id` = `sc`.`province_id` and `l3`.`city_id` = 0
GROUP BY `sc`.`shipping_cost_id`
ORDER BY `shipping_cost_id` ASC
 LIMIT 10
ERROR - 2020-09-22 04:21:28 --> Severity: Warning --> Division by zero /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 68
ERROR - 2020-09-22 04:21:33 --> Severity: Warning --> Division by zero /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 68
ERROR - 2020-09-22 04:22:06 --> Severity: Warning --> Division by zero /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 68
ERROR - 2020-09-22 04:22:25 --> Severity: Warning --> Division by zero /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 68
ERROR - 2020-09-22 04:23:50 --> Severity: Notice --> Undefined variable: conversion_rate /home/dutatunggal/public_html/application/views/admin/dashboard.php 58
ERROR - 2020-09-22 04:37:47 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-09-22 04:38:23 --> 404 Page Not Found: 
ERROR - 2020-09-22 04:47:20 --> 404 Page Not Found: 
ERROR - 2020-09-22 04:56:38 --> 404 Page Not Found: 
ERROR - 2020-09-22 04:58:58 --> 404 Page Not Found: 
ERROR - 2020-09-22 05:10:55 --> 404 Page Not Found: Product-boxhtml/index
ERROR - 2020-09-22 05:31:56 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-09-22 05:31:58 --> 404 Page Not Found: 
ERROR - 2020-09-22 05:41:19 --> 404 Page Not Found: 
ERROR - 2020-09-22 05:55:21 --> 404 Page Not Found: TelerikWebUIWebResourceaxd/index
ERROR - 2020-09-22 06:46:32 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-09-22 07:08:03 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-09-22 07:08:05 --> 404 Page Not Found: 
ERROR - 2020-09-22 08:09:33 --> 404 Page Not Found: 
ERROR - 2020-09-22 08:54:48 --> 404 Page Not Found: Sales/add
ERROR - 2020-09-22 08:56:14 --> 404 Page Not Found: TelerikWebUIWebResourceaxd/index
ERROR - 2020-09-22 09:06:00 --> 404 Page Not Found: Login/index
ERROR - 2020-09-22 09:21:36 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-09-22 09:31:18 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-09-22 10:25:27 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-09-22 10:27:41 --> 404 Page Not Found: 
ERROR - 2020-09-22 10:28:09 --> 404 Page Not Found: 
ERROR - 2020-09-22 10:28:41 --> 404 Page Not Found: 
ERROR - 2020-09-22 10:29:01 --> 404 Page Not Found: 
ERROR - 2020-09-22 10:29:01 --> 404 Page Not Found: 
ERROR - 2020-09-22 10:29:44 --> 404 Page Not Found: 
ERROR - 2020-09-22 10:30:42 --> 404 Page Not Found: 
ERROR - 2020-09-22 10:31:42 --> 404 Page Not Found: 
ERROR - 2020-09-22 10:32:41 --> 404 Page Not Found: 
ERROR - 2020-09-22 10:33:41 --> 404 Page Not Found: 
ERROR - 2020-09-22 10:34:42 --> 404 Page Not Found: 
ERROR - 2020-09-22 10:35:45 --> 404 Page Not Found: 
ERROR - 2020-09-22 11:03:20 --> 404 Page Not Found: Client_area/index
ERROR - 2020-09-22 11:03:21 --> 404 Page Not Found: System_apiphp/index
ERROR - 2020-09-22 11:03:23 --> 404 Page Not Found: Streaming/clients_live.php
ERROR - 2020-09-22 11:03:24 --> 404 Page Not Found: Stalker_portal/c
ERROR - 2020-09-22 11:03:26 --> 404 Page Not Found: Apiphp/index
ERROR - 2020-09-22 11:03:28 --> 404 Page Not Found: Loginphp/index
ERROR - 2020-09-22 11:03:29 --> 404 Page Not Found: Streaming/index
ERROR - 2020-09-22 11:03:31 --> 404 Page Not Found: Streaming/clients_live.php
ERROR - 2020-09-22 11:03:32 --> 404 Page Not Found: Streaming/IsGOzDSvRO.php
ERROR - 2020-09-22 12:02:46 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-09-22 13:06:01 --> 404 Page Not Found: 
ERROR - 2020-09-22 13:22:14 --> 404 Page Not Found: 
ERROR - 2020-09-22 13:27:12 --> 404 Page Not Found: 
ERROR - 2020-09-22 13:45:59 --> 404 Page Not Found: 
ERROR - 2020-09-22 14:09:33 --> 404 Page Not Found: 
ERROR - 2020-09-22 14:11:44 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-09-22 14:11:48 --> 404 Page Not Found: 
ERROR - 2020-09-22 14:30:44 --> 404 Page Not Found: 
ERROR - 2020-09-22 14:31:56 --> 404 Page Not Found: Blog-left-sidebarhtml/index
ERROR - 2020-09-22 15:12:42 --> 404 Page Not Found: Sales/add
ERROR - 2020-09-22 15:17:59 --> 404 Page Not Found: 
ERROR - 2020-09-22 15:37:18 --> 404 Page Not Found: Product-on-salehtml/index
ERROR - 2020-09-22 15:55:26 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-09-22 15:59:57 --> 404 Page Not Found: 
ERROR - 2020-09-22 16:48:53 --> 404 Page Not Found: Cpanel/index
ERROR - 2020-09-22 16:53:01 --> 404 Page Not Found: Remote/login
ERROR - 2020-09-22 16:58:57 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-09-22 17:15:57 --> 404 Page Not Found: Owa/auth
ERROR - 2020-09-22 17:59:33 --> Severity: error --> Exception: Midtrans Error (404): Transaction doesn't exist. /home/dutatunggal/public_html/application/vendor/midtrans/midtrans-php/Midtrans/ApiRequestor.php 111
ERROR - 2020-09-22 18:00:48 --> Severity: error --> Exception: Midtrans Error (404): Transaction doesn't exist. /home/dutatunggal/public_html/application/vendor/midtrans/midtrans-php/Midtrans/ApiRequestor.php 111
ERROR - 2020-09-22 18:32:54 --> Severity: error --> Exception: Midtrans Error (404): Transaction doesn't exist. /home/dutatunggal/public_html/application/vendor/midtrans/midtrans-php/Midtrans/ApiRequestor.php 111
ERROR - 2020-09-22 18:34:21 --> Severity: error --> Exception: Midtrans Error (404): Transaction doesn't exist. /home/dutatunggal/public_html/application/vendor/midtrans/midtrans-php/Midtrans/ApiRequestor.php 111
ERROR - 2020-09-22 20:05:56 --> 404 Page Not Found: Faqshtml/index
ERROR - 2020-09-22 20:20:07 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-09-22 20:20:27 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-09-22 20:30:41 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-09-22 20:30:43 --> 404 Page Not Found: 
ERROR - 2020-09-22 20:43:06 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-09-22 20:43:45 --> 404 Page Not Found: 
ERROR - 2020-09-22 20:51:46 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-09-22 21:25:45 --> 404 Page Not Found: 
ERROR - 2020-09-22 21:56:26 --> 404 Page Not Found: HNAP1/index
ERROR - 2020-09-22 22:22:35 --> 404 Page Not Found: 
ERROR - 2020-09-22 23:30:45 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-09-22 23:30:46 --> 404 Page Not Found: 
ERROR - 2020-09-22 23:59:45 --> 404 Page Not Found: 
