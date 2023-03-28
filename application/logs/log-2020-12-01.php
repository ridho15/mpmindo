<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2020-12-01 00:31:51 --> 404 Page Not Found: 
ERROR - 2020-12-01 00:38:43 --> 404 Page Not Found: 
ERROR - 2020-12-01 00:57:06 --> 404 Page Not Found: 
ERROR - 2020-12-01 01:36:22 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 01:52:20 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 01:52:20 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 01:52:45 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 01:52:56 --> 404 Page Not Found: 
ERROR - 2020-12-01 01:52:58 --> 404 Page Not Found: 
ERROR - 2020-12-01 01:57:22 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 01:57:24 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 01:57:42 --> 404 Page Not Found: 
ERROR - 2020-12-01 01:57:43 --> 404 Page Not Found: 
ERROR - 2020-12-01 02:17:24 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 02:37:32 --> 404 Page Not Found: 
ERROR - 2020-12-01 03:39:26 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 03:43:54 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 03:47:54 --> 404 Page Not Found: Login_sidlua/index
ERROR - 2020-12-01 03:48:01 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 04:33:25 --> 404 Page Not Found: Env/index
ERROR - 2020-12-01 04:38:31 --> 404 Page Not Found: 
ERROR - 2020-12-01 04:38:31 --> 404 Page Not Found: 
ERROR - 2020-12-01 05:08:45 --> 404 Page Not Found: 
ERROR - 2020-12-01 05:14:18 --> 404 Page Not Found: 
ERROR - 2020-12-01 05:48:36 --> 404 Page Not Found: 
ERROR - 2020-12-01 06:58:53 --> 404 Page Not Found: 
ERROR - 2020-12-01 07:15:07 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 07:30:06 --> 404 Page Not Found: 
ERROR - 2020-12-01 07:30:07 --> 404 Page Not Found: 
ERROR - 2020-12-01 07:49:05 --> 404 Page Not Found: 
ERROR - 2020-12-01 07:59:05 --> 404 Page Not Found: 
ERROR - 2020-12-01 08:38:53 --> 404 Page Not Found: 
ERROR - 2020-12-01 08:58:47 --> 404 Page Not Found: 
ERROR - 2020-12-01 08:58:50 --> 404 Page Not Found: 
ERROR - 2020-12-01 09:08:44 --> 404 Page Not Found: 
ERROR - 2020-12-01 09:18:49 --> 404 Page Not Found: 
ERROR - 2020-12-01 09:18:49 --> 404 Page Not Found: 
ERROR - 2020-12-01 09:27:02 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 10:13:17 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 10:19:51 --> 404 Page Not Found: 
ERROR - 2020-12-01 10:25:03 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 10:25:05 --> 404 Page Not Found: 
ERROR - 2020-12-01 10:29:26 --> 404 Page Not Found: 
ERROR - 2020-12-01 10:54:21 --> 404 Page Not Found: Epa/scripts
ERROR - 2020-12-01 11:17:53 --> 404 Page Not Found: 
ERROR - 2020-12-01 11:44:30 --> Severity: Notice --> Undefined index: labels /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-01 11:44:30 --> Severity: Warning --> Invalid argument supplied for foreach() /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-01 11:44:30 --> Severity: Notice --> Undefined index: labels /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-01 11:44:30 --> Severity: Warning --> Invalid argument supplied for foreach() /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-01 11:44:30 --> Severity: Notice --> Undefined index: labels /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-01 11:44:30 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-01 11:44:30 --> Severity: Notice --> Undefined variable: sales /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-01 11:44:30 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-01 11:44:30 --> Severity: Notice --> Undefined variable: sales2 /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-01 11:44:30 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-01 11:44:30 --> Query error: Expression #2 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dt_commerce.op.name' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT SUM(op.quantity) as total, op.name, op.product_id
FROM `order_product` `op`
LEFT JOIN `order` `o` ON `o`.`order_id` = `op`.`order_id`
WHERE (o.date_added BETWEEN "2020-11-30 00:00:00" AND "2020-12-06 23:59:00")
GROUP BY `op`.`product_id`
ORDER BY `total` ASC
 LIMIT 10
ERROR - 2020-12-01 11:45:20 --> Severity: Notice --> Undefined index: labels /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-01 11:45:20 --> Severity: Warning --> Invalid argument supplied for foreach() /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-01 11:45:20 --> Severity: Notice --> Undefined index: labels /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-01 11:45:20 --> Severity: Warning --> Invalid argument supplied for foreach() /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-01 11:45:20 --> Severity: Notice --> Undefined index: labels /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-01 11:45:20 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-01 11:45:20 --> Severity: Notice --> Undefined variable: sales /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-01 11:45:20 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-01 11:45:20 --> Severity: Notice --> Undefined variable: sales2 /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-01 11:45:20 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-01 11:45:20 --> Query error: Expression #2 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dt_commerce.op.name' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT SUM(op.quantity) as total, op.name, op.product_id
FROM `order_product` `op`
LEFT JOIN `order` `o` ON `o`.`order_id` = `op`.`order_id`
WHERE (o.date_added BETWEEN "2020-11-30 00:00:00" AND "2020-12-06 23:59:00")
GROUP BY `op`.`product_id`
ORDER BY `total` ASC
 LIMIT 10
ERROR - 2020-12-01 12:49:29 --> 404 Page Not Found: Sitemapsxml/index
ERROR - 2020-12-01 13:04:23 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 13:22:15 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 13:45:08 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 15:35:36 --> 404 Page Not Found: Env/index
ERROR - 2020-12-01 16:06:56 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 16:21:13 --> 404 Page Not Found: 
ERROR - 2020-12-01 18:08:25 --> 404 Page Not Found: 
ERROR - 2020-12-01 18:49:52 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 19:07:51 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 19:16:33 --> 404 Page Not Found: Cpanel/index
ERROR - 2020-12-01 19:41:16 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 20:12:17 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 20:12:19 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 20:12:36 --> 404 Page Not Found: 
ERROR - 2020-12-01 20:12:36 --> 404 Page Not Found: 
ERROR - 2020-12-01 20:12:38 --> 404 Page Not Found: 
ERROR - 2020-12-01 20:22:07 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 20:24:00 --> 404 Page Not Found: 
ERROR - 2020-12-01 20:24:01 --> 404 Page Not Found: 
ERROR - 2020-12-01 20:24:03 --> 404 Page Not Found: 
ERROR - 2020-12-01 21:01:59 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 21:02:12 --> 404 Page Not Found: 
ERROR - 2020-12-01 21:04:46 --> 404 Page Not Found: Cpanel/index
ERROR - 2020-12-01 23:04:39 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 23:27:41 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 23:27:43 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-12-01 23:57:04 --> 404 Page Not Found: Robotstxt/index
