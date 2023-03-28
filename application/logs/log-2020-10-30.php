<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2020-10-30 00:09:58 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 00:10:00 --> 404 Page Not Found: Sitemap/xml
ERROR - 2020-10-30 00:10:02 --> 404 Page Not Found: Well-known/security.txt
ERROR - 2020-10-30 00:10:05 --> 404 Page Not Found: 
ERROR - 2020-10-30 00:13:38 --> 404 Page Not Found: 
ERROR - 2020-10-30 00:49:54 --> 404 Page Not Found: Product-full-contenthtml/index
ERROR - 2020-10-30 00:54:31 --> 404 Page Not Found: 
ERROR - 2020-10-30 01:13:08 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 01:32:29 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 01:52:24 --> 404 Page Not Found: Wp-loginphp/index
ERROR - 2020-10-30 01:55:49 --> 404 Page Not Found: 
ERROR - 2020-10-30 02:05:32 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 02:14:05 --> 404 Page Not Found: 
ERROR - 2020-10-30 02:14:05 --> 404 Page Not Found: 
ERROR - 2020-10-30 02:36:01 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 03:34:11 --> 404 Page Not Found: 
ERROR - 2020-10-30 03:34:14 --> 404 Page Not Found: Bag2/index
ERROR - 2020-10-30 03:47:14 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 04:09:12 --> 404 Page Not Found: 
ERROR - 2020-10-30 04:25:58 --> 404 Page Not Found: 
ERROR - 2020-10-30 04:45:33 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 05:00:13 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 05:26:53 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 06:31:38 --> 404 Page Not Found: 
ERROR - 2020-10-30 07:53:56 --> 404 Page Not Found: 
ERROR - 2020-10-30 08:54:13 --> Query error: Expression #2 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dt_commerce.op.name' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT SUM(op.quantity) as total, op.name, op.product_id
FROM `order_product` `op`
LEFT JOIN `order` `o` ON `o`.`order_id` = `op`.`order_id`
WHERE (o.date_added BETWEEN "2020-10-26 00:00:00" AND "2020-11-01 23:59:00")
GROUP BY `op`.`product_id`
ORDER BY `total` ASC
 LIMIT 10
ERROR - 2020-10-30 08:54:13 --> Severity: Error --> Call to a member function result_array() on boolean /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 327
ERROR - 2020-10-30 08:54:13 --> Severity: Warning --> Invalid argument supplied for foreach() /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 108
ERROR - 2020-10-30 08:54:13 --> Severity: Warning --> Invalid argument supplied for foreach() /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 119
ERROR - 2020-10-30 08:54:13 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 127
ERROR - 2020-10-30 08:54:13 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 137
ERROR - 2020-10-30 08:54:13 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 147
ERROR - 2020-10-30 09:03:00 --> Query error: Expression #2 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dt_commerce.op.name' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT SUM(op.quantity) as total, op.name, op.product_id
FROM `order_product` `op`
LEFT JOIN `order` `o` ON `o`.`order_id` = `op`.`order_id`
WHERE (o.date_added BETWEEN "2020-10-26 00:00:00" AND "2020-11-01 23:59:00")
GROUP BY `op`.`product_id`
ORDER BY `total` ASC
 LIMIT 10
ERROR - 2020-10-30 09:03:00 --> Severity: Error --> Call to a member function result_array() on boolean /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 327
ERROR - 2020-10-30 09:03:00 --> Severity: Warning --> Invalid argument supplied for foreach() /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 108
ERROR - 2020-10-30 09:03:00 --> Severity: Warning --> Invalid argument supplied for foreach() /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 119
ERROR - 2020-10-30 09:03:00 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 127
ERROR - 2020-10-30 09:03:00 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 137
ERROR - 2020-10-30 09:03:00 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 147
ERROR - 2020-10-30 09:10:31 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 09:12:07 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 09:12:09 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 09:14:30 --> 404 Page Not Found: 
ERROR - 2020-10-30 09:14:31 --> 404 Page Not Found: 
ERROR - 2020-10-30 09:31:50 --> Severity: Warning --> Illegal string offset 'name' /home/dutatunggal/public_html/application/controllers/Product.php 129
ERROR - 2020-10-30 09:31:50 --> Severity: Warning --> Illegal string offset 'name' /home/dutatunggal/public_html/application/controllers/Product.php 129
ERROR - 2020-10-30 09:31:50 --> Severity: Warning --> Illegal string offset 'name' /home/dutatunggal/public_html/application/controllers/Product.php 129
ERROR - 2020-10-30 09:34:51 --> Severity: Warning --> Illegal string offset 'name' /home/dutatunggal/public_html/application/controllers/Product.php 129
ERROR - 2020-10-30 09:36:30 --> Severity: Warning --> Illegal string offset 'name' /home/dutatunggal/public_html/application/controllers/Product.php 129
ERROR - 2020-10-30 09:36:56 --> Severity: Warning --> Illegal string offset 'name' /home/dutatunggal/public_html/application/controllers/Product.php 129
ERROR - 2020-10-30 10:22:33 --> 404 Page Not Found: 
ERROR - 2020-10-30 10:42:37 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 11:36:56 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 11:37:06 --> 404 Page Not Found: Xmlrpcphp/index
ERROR - 2020-10-30 11:37:06 --> 404 Page Not Found: Xmlrpcphp/index
ERROR - 2020-10-30 11:44:48 --> 404 Page Not Found: 
ERROR - 2020-10-30 12:26:08 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 13:31:05 --> 404 Page Not Found: 
ERROR - 2020-10-30 13:34:46 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 14:30:56 --> 404 Page Not Found: 
ERROR - 2020-10-30 15:23:59 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 15:24:09 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 15:38:48 --> 404 Page Not Found: 
ERROR - 2020-10-30 15:41:28 --> 404 Page Not Found: 
ERROR - 2020-10-30 15:51:20 --> 404 Page Not Found: 
ERROR - 2020-10-30 16:01:22 --> 404 Page Not Found: 
ERROR - 2020-10-30 16:11:33 --> 404 Page Not Found: 
ERROR - 2020-10-30 16:41:38 --> 404 Page Not Found: 
ERROR - 2020-10-30 16:45:46 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 17:00:26 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 17:01:58 --> 404 Page Not Found: 
ERROR - 2020-10-30 17:31:11 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 18:22:31 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 18:23:28 --> 404 Page Not Found: 
ERROR - 2020-10-30 18:44:17 --> 404 Page Not Found: 
ERROR - 2020-10-30 18:48:22 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 19:36:28 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-30 19:47:02 --> 404 Page Not Found: 
ERROR - 2020-10-30 20:30:14 --> 404 Page Not Found: 
ERROR - 2020-10-30 23:00:12 --> 404 Page Not Found: Robotstxt/index
