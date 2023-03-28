<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2020-10-27 00:19:41 --> 404 Page Not Found: 
ERROR - 2020-10-27 00:39:14 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-27 02:59:42 --> 404 Page Not Found: 
ERROR - 2020-10-27 03:50:14 --> 404 Page Not Found: 
ERROR - 2020-10-27 03:50:23 --> 404 Page Not Found: Product-videohtml/index
ERROR - 2020-10-27 03:50:26 --> 404 Page Not Found: 
ERROR - 2020-10-27 03:53:27 --> 404 Page Not Found: 
ERROR - 2020-10-27 03:53:46 --> 404 Page Not Found: Shop-sidebar-without-bannerhtml/index
ERROR - 2020-10-27 03:53:55 --> 404 Page Not Found: 
ERROR - 2020-10-27 03:55:54 --> 404 Page Not Found: 
ERROR - 2020-10-27 03:57:32 --> 404 Page Not Found: 
ERROR - 2020-10-27 03:59:15 --> 404 Page Not Found: 
ERROR - 2020-10-27 03:59:40 --> 404 Page Not Found: 
ERROR - 2020-10-27 04:01:46 --> 404 Page Not Found: 
ERROR - 2020-10-27 04:04:18 --> 404 Page Not Found: 
ERROR - 2020-10-27 04:20:42 --> 404 Page Not Found: 
ERROR - 2020-10-27 04:46:54 --> 404 Page Not Found: 
ERROR - 2020-10-27 05:09:12 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-27 06:22:22 --> 404 Page Not Found: 
ERROR - 2020-10-27 07:02:57 --> 404 Page Not Found: 
ERROR - 2020-10-27 07:37:11 --> 404 Page Not Found: Env/index
ERROR - 2020-10-27 08:15:08 --> 404 Page Not Found: 
ERROR - 2020-10-27 08:43:00 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-27 09:04:05 --> 404 Page Not Found: 
ERROR - 2020-10-27 09:35:21 --> 404 Page Not Found: 
ERROR - 2020-10-27 10:10:31 --> 404 Page Not Found: 
ERROR - 2020-10-27 10:14:32 --> 404 Page Not Found: 
ERROR - 2020-10-27 10:17:19 --> 404 Page Not Found: 
ERROR - 2020-10-27 10:37:49 --> Severity: Warning --> Invalid argument supplied for foreach() /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 108
ERROR - 2020-10-27 10:37:49 --> Severity: Warning --> Invalid argument supplied for foreach() /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 119
ERROR - 2020-10-27 10:37:49 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 127
ERROR - 2020-10-27 10:37:49 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 137
ERROR - 2020-10-27 10:37:49 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 147
ERROR - 2020-10-27 10:37:49 --> Query error: Expression #2 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dt_commerce.op.name' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT SUM(op.quantity) as total, op.name, op.product_id
FROM `order_product` `op`
LEFT JOIN `order` `o` ON `o`.`order_id` = `op`.`order_id`
WHERE (o.date_added BETWEEN "2020-10-26 00:00:00" AND "2020-11-01 23:59:00")
GROUP BY `op`.`product_id`
ORDER BY `total` ASC
 LIMIT 10
ERROR - 2020-10-27 10:37:49 --> Severity: Error --> Call to a member function result_array() on boolean /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 327
ERROR - 2020-10-27 10:52:34 --> 404 Page Not Found: 
ERROR - 2020-10-27 10:56:13 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-27 11:12:38 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-27 11:15:23 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-27 11:17:20 --> 404 Page Not Found: Wp-loginphp/index
ERROR - 2020-10-27 11:17:20 --> 404 Page Not Found: Wp-loginphp/index
ERROR - 2020-10-27 11:17:20 --> 404 Page Not Found: Wp-loginphp/index
ERROR - 2020-10-27 11:19:09 --> 404 Page Not Found: Owa/auth
ERROR - 2020-10-27 12:08:43 --> 404 Page Not Found: 
ERROR - 2020-10-27 13:12:26 --> Severity: Warning --> Invalid argument supplied for foreach() /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 108
ERROR - 2020-10-27 13:12:26 --> Severity: Warning --> Invalid argument supplied for foreach() /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 119
ERROR - 2020-10-27 13:12:26 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 127
ERROR - 2020-10-27 13:12:26 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 137
ERROR - 2020-10-27 13:12:26 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 147
ERROR - 2020-10-27 13:12:26 --> Query error: Expression #2 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dt_commerce.op.name' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT SUM(op.quantity) as total, op.name, op.product_id
FROM `order_product` `op`
LEFT JOIN `order` `o` ON `o`.`order_id` = `op`.`order_id`
WHERE (o.date_added BETWEEN "2020-10-26 00:00:00" AND "2020-11-01 23:59:00")
GROUP BY `op`.`product_id`
ORDER BY `total` ASC
 LIMIT 10
ERROR - 2020-10-27 13:12:26 --> Severity: Error --> Call to a member function result_array() on boolean /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 327
ERROR - 2020-10-27 13:38:43 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-27 13:49:02 --> 404 Page Not Found: Wp-loginphp/index
ERROR - 2020-10-27 13:49:02 --> 404 Page Not Found: Wp-loginphp/index
ERROR - 2020-10-27 13:49:02 --> 404 Page Not Found: Wp-loginphp/index
ERROR - 2020-10-27 14:42:00 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-27 15:53:14 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-27 16:20:49 --> 404 Page Not Found: 
ERROR - 2020-10-27 16:56:46 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-27 16:57:03 --> 404 Page Not Found: 
ERROR - 2020-10-27 17:06:49 --> 404 Page Not Found: 
ERROR - 2020-10-27 17:06:49 --> 404 Page Not Found: 
ERROR - 2020-10-27 17:09:11 --> 404 Page Not Found: 
ERROR - 2020-10-27 17:09:11 --> 404 Page Not Found: 
ERROR - 2020-10-27 17:42:50 --> Severity: Warning --> Illegal string offset 'name' /home/dutatunggal/public_html/application/controllers/Product.php 129
ERROR - 2020-10-27 17:42:50 --> Severity: Warning --> Illegal string offset 'name' /home/dutatunggal/public_html/application/controllers/Product.php 129
ERROR - 2020-10-27 17:42:50 --> Severity: Warning --> Illegal string offset 'name' /home/dutatunggal/public_html/application/controllers/Product.php 129
ERROR - 2020-10-27 18:04:50 --> Severity: Warning --> Illegal string offset 'name' /home/dutatunggal/public_html/application/controllers/Product.php 129
ERROR - 2020-10-27 18:13:53 --> 404 Page Not Found: 
ERROR - 2020-10-27 18:19:57 --> Severity: Warning --> Illegal string offset 'name' /home/dutatunggal/public_html/application/controllers/Product.php 129
ERROR - 2020-10-27 18:20:00 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-27 18:27:10 --> Severity: Warning --> Illegal string offset 'name' /home/dutatunggal/public_html/application/controllers/Product.php 129
ERROR - 2020-10-27 18:48:37 --> 404 Page Not Found: 
ERROR - 2020-10-27 20:09:05 --> 404 Page Not Found: 
ERROR - 2020-10-27 20:33:03 --> 404 Page Not Found: 
ERROR - 2020-10-27 21:43:14 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-27 22:15:12 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-27 22:51:15 --> 404 Page Not Found: 
ERROR - 2020-10-27 22:57:53 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-27 22:58:32 --> 404 Page Not Found: 
ERROR - 2020-10-27 23:43:54 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-27 23:48:43 --> Severity: Warning --> Illegal string offset 'name' /home/dutatunggal/public_html/application/controllers/Product.php 129
ERROR - 2020-10-27 23:48:43 --> Severity: Warning --> Illegal string offset 'name' /home/dutatunggal/public_html/application/controllers/Product.php 129
ERROR - 2020-10-27 23:48:43 --> Severity: Warning --> Illegal string offset 'name' /home/dutatunggal/public_html/application/controllers/Product.php 129
