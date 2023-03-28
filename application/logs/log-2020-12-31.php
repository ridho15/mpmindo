<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2020-12-31 00:09:45 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 00:09:45 --> 404 Page Not Found: Faviconico/index
ERROR - 2020-12-31 00:11:00 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 00:11:00 --> 404 Page Not Found: Faviconico/index
ERROR - 2020-12-31 08:48:34 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 08:48:59 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 09:54:45 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 09:54:45 --> 404 Page Not Found: Faviconico/index
ERROR - 2020-12-31 10:28:28 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 10:28:28 --> 404 Page Not Found: Faviconico/index
ERROR - 2020-12-31 10:28:34 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 10:28:35 --> 404 Page Not Found: Faviconico/index
ERROR - 2020-12-31 10:34:38 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 10:40:30 --> 404 Page Not Found: Apple-touch-icon-120x120-precomposedpng/index
ERROR - 2020-12-31 10:40:31 --> 404 Page Not Found: Apple-touch-icon-120x120png/index
ERROR - 2020-12-31 10:40:31 --> 404 Page Not Found: Apple-touch-icon-precomposedpng/index
ERROR - 2020-12-31 10:40:31 --> 404 Page Not Found: Apple-touch-iconpng/index
ERROR - 2020-12-31 10:40:31 --> 404 Page Not Found: Faviconico/index
ERROR - 2020-12-31 10:40:31 --> 404 Page Not Found: Apple-touch-icon-120x120-precomposedpng/index
ERROR - 2020-12-31 10:40:31 --> 404 Page Not Found: Apple-touch-icon-120x120png/index
ERROR - 2020-12-31 10:40:31 --> 404 Page Not Found: Apple-touch-icon-precomposedpng/index
ERROR - 2020-12-31 10:40:31 --> 404 Page Not Found: Apple-touch-iconpng/index
ERROR - 2020-12-31 10:40:31 --> 404 Page Not Found: Faviconico/index
ERROR - 2020-12-31 10:40:32 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 10:40:32 --> 404 Page Not Found: Faviconico/index
ERROR - 2020-12-31 10:47:05 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 10:53:32 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 11:18:20 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 11:31:29 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 11:31:30 --> 404 Page Not Found: Faviconico/index
ERROR - 2020-12-31 11:34:22 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 11:49:08 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 11:49:08 --> 404 Page Not Found: Faviconico/index
ERROR - 2020-12-31 11:50:51 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 11:54:51 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 12:02:57 --> 404 Page Not Found: Faviconico/index
ERROR - 2020-12-31 12:18:49 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 12:26:02 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 12:26:33 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:26:33 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:26:33 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:26:33 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:26:33 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:26:33 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:26:33 --> Severity: Notice --> Undefined variable: sales /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:26:33 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:26:33 --> Severity: Notice --> Undefined variable: sales2 /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:26:33 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:27:30 --> Query error: SELECT command denied to user 'gethasse_invento'@'localhost' for table 'category' - Invalid query: SELECT `c`.`category_id`, `pc`.`id` as `inventory_category_id`, `pc`.`category_name` as `name`, `c`.`parent_id`, (CASE WHEN c.description IS NULL THEN pc.category_name ELSE c.description END) AS description, (CASE WHEN c.meta_description IS NULL THEN pc.category_name ELSE c.meta_description END) AS meta_description, (CASE WHEN c.meta_title IS NULL THEN pc.category_name ELSE c.meta_title END) AS meta_title
FROM `knr_product_categories` `pc`
LEFT JOIN `gethasse_mpmwahl`.`category` `c` ON `c`.`inventory_category_id` = `pc`.`id`
ERROR - 2020-12-31 12:27:38 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:27:38 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:27:38 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:27:38 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:27:38 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:27:38 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:27:38 --> Severity: Notice --> Undefined variable: sales /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:27:38 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:27:38 --> Severity: Notice --> Undefined variable: sales2 /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:27:38 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:27:42 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:27:42 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:27:42 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:27:42 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:27:42 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:27:42 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:27:42 --> Severity: Notice --> Undefined variable: sales /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:27:42 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:27:42 --> Severity: Notice --> Undefined variable: sales2 /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:27:42 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:27:44 --> Query error: SELECT command denied to user 'gethasse_invento'@'localhost' for table 'category' - Invalid query: SELECT `c`.`category_id`, `pc`.`id` as `inventory_category_id`, `pc`.`category_name` as `name`, `c`.`parent_id`, (CASE WHEN c.description IS NULL THEN pc.category_name ELSE c.description END) AS description, (CASE WHEN c.meta_description IS NULL THEN pc.category_name ELSE c.meta_description END) AS meta_description, (CASE WHEN c.meta_title IS NULL THEN pc.category_name ELSE c.meta_title END) AS meta_title
FROM `knr_product_categories` `pc`
LEFT JOIN `gethasse_mpmwahl`.`category` `c` ON `c`.`inventory_category_id` = `pc`.`id`
ERROR - 2020-12-31 12:27:46 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:27:46 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:27:46 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:27:46 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:27:46 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:27:46 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:27:46 --> Severity: Notice --> Undefined variable: sales /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:27:46 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:27:46 --> Severity: Notice --> Undefined variable: sales2 /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:27:46 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:28:10 --> Query error: SELECT command denied to user 'gethasse_invento'@'localhost' for table 'category' - Invalid query: SELECT `c`.`category_id`, `pc`.`id` as `inventory_category_id`, `pc`.`category_name` as `name`, `c`.`parent_id`, (CASE WHEN c.description IS NULL THEN pc.category_name ELSE c.description END) AS description, (CASE WHEN c.meta_description IS NULL THEN pc.category_name ELSE c.meta_description END) AS meta_description, (CASE WHEN c.meta_title IS NULL THEN pc.category_name ELSE c.meta_title END) AS meta_title
FROM `knr_product_categories` `pc`
LEFT JOIN `gethasse_mpmwahl`.`category` `c` ON `c`.`inventory_category_id` = `pc`.`id`
ERROR - 2020-12-31 12:28:54 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:28:54 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:28:54 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:28:54 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:28:54 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:28:54 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:28:54 --> Severity: Notice --> Undefined variable: sales /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:28:54 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:28:54 --> Severity: Notice --> Undefined variable: sales2 /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:28:54 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:29:01 --> 404 Page Not Found: Assets/js
ERROR - 2020-12-31 12:29:10 --> Could not find the language line "text_admins"
ERROR - 2020-12-31 12:29:10 --> 404 Page Not Found: Assets/js
ERROR - 2020-12-31 12:29:11 --> 404 Page Not Found: Assets/js
ERROR - 2020-12-31 12:30:55 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:30:55 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:30:55 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:30:55 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:30:55 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:30:55 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:30:55 --> Severity: Notice --> Undefined variable: sales /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:30:55 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:30:55 --> Severity: Notice --> Undefined variable: sales2 /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:30:55 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:31:05 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 12:31:21 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:31:21 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:31:21 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:31:21 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:31:21 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:31:21 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:31:21 --> Severity: Notice --> Undefined variable: sales /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:31:21 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:31:21 --> Severity: Notice --> Undefined variable: sales2 /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:31:21 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:33:24 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:33:24 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:33:24 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:33:24 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:33:24 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:33:24 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:33:24 --> Severity: Notice --> Undefined variable: sales /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:33:24 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:33:24 --> Severity: Notice --> Undefined variable: sales2 /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:33:24 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:33:28 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:33:28 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:33:28 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:33:28 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:33:28 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:33:28 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:33:28 --> Severity: Notice --> Undefined variable: sales /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:33:28 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:33:28 --> Severity: Notice --> Undefined variable: sales2 /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:33:28 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:35:29 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:35:29 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:35:29 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:35:29 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:35:29 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:35:29 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:35:29 --> Severity: Notice --> Undefined variable: sales /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:35:29 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:35:29 --> Severity: Notice --> Undefined variable: sales2 /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:35:29 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:37:25 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:37:25 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:37:25 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:37:25 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:37:25 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:37:25 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:37:25 --> Severity: Notice --> Undefined variable: sales /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:37:25 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:37:25 --> Severity: Notice --> Undefined variable: sales2 /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:37:25 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:37:35 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 12:37:35 --> 404 Page Not Found: Faviconico/index
ERROR - 2020-12-31 12:50:37 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:50:37 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:50:37 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:50:37 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:50:37 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:50:37 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:50:37 --> Severity: Notice --> Undefined variable: sales /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:50:37 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:50:37 --> Severity: Notice --> Undefined variable: sales2 /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:50:37 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:52:37 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 12:52:38 --> 404 Page Not Found: Faviconico/index
ERROR - 2020-12-31 12:52:46 --> 404 Page Not Found: Assets/js
ERROR - 2020-12-31 12:54:53 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:54:53 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 12:54:53 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:54:53 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 12:54:53 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:54:53 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 12:54:53 --> Severity: Notice --> Undefined variable: sales /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:54:53 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 12:54:53 --> Severity: Notice --> Undefined variable: sales2 /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:54:53 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 12:55:51 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 13:01:32 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 13:01:38 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 13:01:38 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 13:01:38 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 13:01:38 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 13:01:38 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 13:01:38 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 13:01:38 --> Severity: Notice --> Undefined variable: sales /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 13:01:38 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 13:01:38 --> Severity: Notice --> Undefined variable: sales2 /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 13:01:38 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 13:03:46 --> 404 Page Not Found: Apple-touch-icon-120x120-precomposedpng/index
ERROR - 2020-12-31 13:03:46 --> 404 Page Not Found: Apple-touch-icon-120x120png/index
ERROR - 2020-12-31 13:03:46 --> 404 Page Not Found: Apple-touch-icon-precomposedpng/index
ERROR - 2020-12-31 13:03:46 --> 404 Page Not Found: Apple-touch-iconpng/index
ERROR - 2020-12-31 13:03:47 --> 404 Page Not Found: Faviconico/index
ERROR - 2020-12-31 13:04:02 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 13:10:31 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 13:16:09 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 13:43:25 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 14:23:45 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 14:23:47 --> 404 Page Not Found: Faviconico/index
ERROR - 2020-12-31 14:33:12 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 14:33:13 --> 404 Page Not Found: Faviconico/index
ERROR - 2020-12-31 14:33:24 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 14:33:24 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 14:33:24 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 14:33:24 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 14:33:24 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 14:33:24 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 14:33:24 --> Severity: Notice --> Undefined variable: sales /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 14:33:24 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 14:33:24 --> Severity: Notice --> Undefined variable: sales2 /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 14:33:24 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 14:33:31 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 14:33:31 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 14:33:31 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 14:33:31 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 14:33:31 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 14:33:31 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 14:33:31 --> Severity: Notice --> Undefined variable: sales /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 14:33:31 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 14:33:31 --> Severity: Notice --> Undefined variable: sales2 /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 14:33:31 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 14:54:19 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 15:10:17 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 15:10:48 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 15:10:48 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 108
ERROR - 2020-12-31 15:10:48 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 15:10:48 --> Severity: Warning --> Invalid argument supplied for foreach() /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 119
ERROR - 2020-12-31 15:10:48 --> Severity: Notice --> Undefined index: labels /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 15:10:48 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 127
ERROR - 2020-12-31 15:10:48 --> Severity: Notice --> Undefined variable: sales /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 15:10:48 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 137
ERROR - 2020-12-31 15:10:48 --> Severity: Notice --> Undefined variable: sales2 /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 15:10:48 --> Severity: Warning --> array_values() expects parameter 1 to be array, null given /home/gethasse/public_html/mpmwahl/application/controllers/admin/Dashboard.php 147
ERROR - 2020-12-31 15:15:32 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 15:21:15 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 15:36:58 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 15:50:13 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 16:08:11 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 16:08:12 --> 404 Page Not Found: Faviconico/index
ERROR - 2020-12-31 16:14:06 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 17:32:19 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 17:32:19 --> 404 Page Not Found: Faviconico/index
ERROR - 2020-12-31 18:15:42 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 18:15:43 --> 404 Page Not Found: Faviconico/index
ERROR - 2020-12-31 18:15:48 --> 404 Page Not Found: Images/demo
ERROR - 2020-12-31 18:15:49 --> 404 Page Not Found: Faviconico/index
