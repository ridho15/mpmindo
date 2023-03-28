<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2020-10-20 00:58:42 --> 404 Page Not Found: 
ERROR - 2020-10-20 01:01:57 --> 404 Page Not Found: 
ERROR - 2020-10-20 01:05:27 --> 404 Page Not Found: 
ERROR - 2020-10-20 01:06:12 --> 404 Page Not Found: 
ERROR - 2020-10-20 01:07:31 --> 404 Page Not Found: 
ERROR - 2020-10-20 01:08:50 --> 404 Page Not Found: 
ERROR - 2020-10-20 01:08:50 --> 404 Page Not Found: 
ERROR - 2020-10-20 01:11:23 --> 404 Page Not Found: 
ERROR - 2020-10-20 01:17:30 --> 404 Page Not Found: 
ERROR - 2020-10-20 01:37:23 --> 404 Page Not Found: 
ERROR - 2020-10-20 01:40:35 --> 404 Page Not Found: 
ERROR - 2020-10-20 01:56:10 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 02:20:44 --> 404 Page Not Found: 
ERROR - 2020-10-20 02:36:07 --> 404 Page Not Found: 
ERROR - 2020-10-20 02:36:08 --> 404 Page Not Found: 
ERROR - 2020-10-20 03:11:56 --> 404 Page Not Found: 
ERROR - 2020-10-20 03:54:09 --> 404 Page Not Found: 
ERROR - 2020-10-20 03:58:59 --> 404 Page Not Found: 
ERROR - 2020-10-20 04:17:48 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 04:30:29 --> 404 Page Not Found: 
ERROR - 2020-10-20 05:13:35 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 05:13:48 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 05:37:50 --> 404 Page Not Found: 
ERROR - 2020-10-20 05:40:50 --> 404 Page Not Found: 
ERROR - 2020-10-20 06:01:52 --> 404 Page Not Found: 
ERROR - 2020-10-20 06:14:46 --> 404 Page Not Found: 
ERROR - 2020-10-20 06:14:46 --> 404 Page Not Found: 
ERROR - 2020-10-20 06:15:31 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 06:29:18 --> 404 Page Not Found: 
ERROR - 2020-10-20 06:57:25 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 06:57:26 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 06:57:27 --> 404 Page Not Found: Lxfjigwfrthtml/index
ERROR - 2020-10-20 06:57:50 --> 404 Page Not Found: TelerikWebUIWebResourceaxd/index
ERROR - 2020-10-20 06:58:36 --> 404 Page Not Found: 
ERROR - 2020-10-20 07:01:53 --> 404 Page Not Found: 
ERROR - 2020-10-20 07:41:12 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 07:50:38 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 08:13:25 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 08:13:27 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 08:13:56 --> 404 Page Not Found: 
ERROR - 2020-10-20 08:19:26 --> 404 Page Not Found: Api/v1
ERROR - 2020-10-20 09:02:08 --> 404 Page Not Found: 
ERROR - 2020-10-20 09:04:28 --> 404 Page Not Found: 
ERROR - 2020-10-20 09:34:13 --> 404 Page Not Found: 
ERROR - 2020-10-20 09:39:00 --> 404 Page Not Found: 
ERROR - 2020-10-20 09:49:01 --> 404 Page Not Found: 
ERROR - 2020-10-20 10:00:14 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 10:13:11 --> 404 Page Not Found: 
ERROR - 2020-10-20 10:31:22 --> 404 Page Not Found: 
ERROR - 2020-10-20 11:24:50 --> 404 Page Not Found: 
ERROR - 2020-10-20 11:31:49 --> 404 Page Not Found: My-accounthtml/index
ERROR - 2020-10-20 11:57:02 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 13:04:42 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 13:05:16 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 13:42:17 --> 404 Page Not Found: TelerikWebUIWebResourceaxd/index
ERROR - 2020-10-20 14:38:03 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 14:38:05 --> 404 Page Not Found: 
ERROR - 2020-10-20 15:08:24 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 15:48:51 --> 404 Page Not Found: 
ERROR - 2020-10-20 16:11:40 --> 404 Page Not Found: Remote/login
ERROR - 2020-10-20 16:21:50 --> 404 Page Not Found: 
ERROR - 2020-10-20 16:27:14 --> 404 Page Not Found: 
ERROR - 2020-10-20 16:27:14 --> 404 Page Not Found: 
ERROR - 2020-10-20 16:37:09 --> Query error: Expression #2 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dt_commerce.op.name' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT SUM(op.quantity) as total, op.name, op.product_id
FROM `order_product` `op`
LEFT JOIN `order` `o` ON `o`.`order_id` = `op`.`order_id`
WHERE (o.date_added BETWEEN "2020-10-19 00:00:00" AND "2020-10-25 23:59:00")
GROUP BY `op`.`product_id`
ORDER BY `total` ASC
 LIMIT 10
ERROR - 2020-10-20 16:37:09 --> Severity: Error --> Call to a member function result_array() on boolean /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 327
ERROR - 2020-10-20 16:37:21 --> Query error: Expression #2 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dt_commerce.op.name' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT SUM(op.quantity) as total, op.name, op.product_id
FROM `order_product` `op`
LEFT JOIN `order` `o` ON `o`.`order_id` = `op`.`order_id`
WHERE (o.date_added BETWEEN "2020-10-19 00:00:00" AND "2020-10-25 23:59:00")
GROUP BY `op`.`product_id`
ORDER BY `total` ASC
 LIMIT 10
ERROR - 2020-10-20 16:37:21 --> Severity: Error --> Call to a member function result_array() on boolean /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 327
ERROR - 2020-10-20 16:37:48 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 16:47:10 --> Query error: Expression #2 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dt_commerce.op.name' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT SUM(op.quantity) as total, op.name, op.product_id
FROM `order_product` `op`
LEFT JOIN `order` `o` ON `o`.`order_id` = `op`.`order_id`
WHERE (o.date_added BETWEEN "2020-10-19 00:00:00" AND "2020-10-25 23:59:00")
GROUP BY `op`.`product_id`
ORDER BY `total` ASC
 LIMIT 10
ERROR - 2020-10-20 16:47:10 --> Severity: Error --> Call to a member function result_array() on boolean /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 327
ERROR - 2020-10-20 17:39:16 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 17:40:34 --> 404 Page Not Found: 
ERROR - 2020-10-20 17:42:13 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 17:42:14 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 17:42:19 --> 404 Page Not Found: 
ERROR - 2020-10-20 17:42:19 --> 404 Page Not Found: 
ERROR - 2020-10-20 17:42:20 --> 404 Page Not Found: 
ERROR - 2020-10-20 17:58:59 --> 404 Page Not Found: 
ERROR - 2020-10-20 18:23:16 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 18:23:17 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 18:24:31 --> 404 Page Not Found: 
ERROR - 2020-10-20 19:03:01 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 19:03:01 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 19:05:05 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 19:09:04 --> 404 Page Not Found: 
ERROR - 2020-10-20 19:25:10 --> 404 Page Not Found: 
ERROR - 2020-10-20 19:36:18 --> 404 Page Not Found: 
ERROR - 2020-10-20 20:04:30 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 20:04:30 --> 404 Page Not Found: Sitemap/xml
ERROR - 2020-10-20 20:04:31 --> 404 Page Not Found: Well-known/security.txt
ERROR - 2020-10-20 20:13:11 --> 404 Page Not Found: 
ERROR - 2020-10-20 21:26:50 --> 404 Page Not Found: Owa/auth
ERROR - 2020-10-20 22:33:56 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 23:00:11 --> 404 Page Not Found: 
ERROR - 2020-10-20 23:45:12 --> 404 Page Not Found: 
ERROR - 2020-10-20 23:47:56 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-20 23:51:42 --> 404 Page Not Found: Reports/sales_items
ERROR - 2020-10-20 23:51:42 --> 404 Page Not Found: Reports/purchases_items
ERROR - 2020-10-20 23:51:42 --> 404 Page Not Found: Login/index
ERROR - 2020-10-20 23:51:42 --> 404 Page Not Found: Reports/purchases_items
ERROR - 2020-10-20 23:57:14 --> 404 Page Not Found: 
