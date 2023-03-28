<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2020-10-22 00:05:52 --> 404 Page Not Found: 
ERROR - 2020-10-22 00:12:23 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 00:33:06 --> 404 Page Not Found: 
ERROR - 2020-10-22 00:48:28 --> 404 Page Not Found: 
ERROR - 2020-10-22 00:52:20 --> 404 Page Not Found: 
ERROR - 2020-10-22 01:19:39 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 02:22:28 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 02:37:33 --> 404 Page Not Found: 
ERROR - 2020-10-22 02:44:48 --> 404 Page Not Found: 
ERROR - 2020-10-22 03:04:21 --> 404 Page Not Found: Homepage-6html/index
ERROR - 2020-10-22 03:06:31 --> 404 Page Not Found: 
ERROR - 2020-10-22 03:23:32 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 03:28:17 --> 404 Page Not Found: 
ERROR - 2020-10-22 04:23:52 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 05:00:23 --> 404 Page Not Found: Solr/index
ERROR - 2020-10-22 05:33:41 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 05:39:58 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 06:57:36 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 06:57:37 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 06:57:37 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 06:57:50 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 07:29:04 --> 404 Page Not Found: 
ERROR - 2020-10-22 07:35:16 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 07:52:38 --> 404 Page Not Found: Solr/index
ERROR - 2020-10-22 07:58:43 --> 404 Page Not Found: 
ERROR - 2020-10-22 07:58:43 --> 404 Page Not Found: 
ERROR - 2020-10-22 08:25:53 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 08:32:07 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 09:10:14 --> 404 Page Not Found: 
ERROR - 2020-10-22 09:18:11 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 09:20:17 --> 404 Page Not Found: 
ERROR - 2020-10-22 09:43:34 --> Query error: Expression #2 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dt_commerce.op.name' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT SUM(op.quantity) as total, op.name, op.product_id
FROM `order_product` `op`
LEFT JOIN `order` `o` ON `o`.`order_id` = `op`.`order_id`
WHERE (o.date_added BETWEEN "2020-10-19 00:00:00" AND "2020-10-25 23:59:00")
GROUP BY `op`.`product_id`
ORDER BY `total` ASC
 LIMIT 10
ERROR - 2020-10-22 09:43:34 --> Severity: Error --> Call to a member function result_array() on boolean /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 327
ERROR - 2020-10-22 09:46:54 --> 404 Page Not Found: 
ERROR - 2020-10-22 09:57:50 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 10:50:09 --> 404 Page Not Found: 
ERROR - 2020-10-22 10:57:27 --> 404 Page Not Found: 
ERROR - 2020-10-22 11:50:56 --> Query error: Expression #2 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dt_commerce.op.name' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT SUM(op.quantity) as total, op.name, op.product_id
FROM `order_product` `op`
LEFT JOIN `order` `o` ON `o`.`order_id` = `op`.`order_id`
WHERE (o.date_added BETWEEN "2020-10-19 00:00:00" AND "2020-10-25 23:59:00")
GROUP BY `op`.`product_id`
ORDER BY `total` ASC
 LIMIT 10
ERROR - 2020-10-22 11:50:56 --> Severity: Error --> Call to a member function result_array() on boolean /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 327
ERROR - 2020-10-22 11:58:30 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 12:25:23 --> 404 Page Not Found: 
ERROR - 2020-10-22 13:01:17 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 13:12:08 --> 404 Page Not Found: 
ERROR - 2020-10-22 13:20:12 --> 404 Page Not Found: 
ERROR - 2020-10-22 14:17:21 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 14:26:59 --> 404 Page Not Found: Homepage-photo-and-videohtml/index
ERROR - 2020-10-22 14:52:21 --> 404 Page Not Found: 
ERROR - 2020-10-22 14:53:40 --> Query error: Expression #2 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dt_commerce.op.name' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT SUM(op.quantity) as total, op.name, op.product_id
FROM `order_product` `op`
LEFT JOIN `order` `o` ON `o`.`order_id` = `op`.`order_id`
WHERE (o.date_added BETWEEN "2020-10-19 00:00:00" AND "2020-10-25 23:59:00")
GROUP BY `op`.`product_id`
ORDER BY `total` ASC
 LIMIT 10
ERROR - 2020-10-22 14:53:40 --> Severity: Error --> Call to a member function result_array() on boolean /home/dutatunggal/public_html/application/controllers/admin/Dashboard.php 327
ERROR - 2020-10-22 16:13:10 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 16:39:11 --> 404 Page Not Found: Client_area/index
ERROR - 2020-10-22 16:39:11 --> 404 Page Not Found: Client_area/index
ERROR - 2020-10-22 16:39:12 --> 404 Page Not Found: System_apiphp/index
ERROR - 2020-10-22 16:39:12 --> 404 Page Not Found: System_apiphp/index
ERROR - 2020-10-22 16:39:12 --> 404 Page Not Found: Streaming/clients_live.php
ERROR - 2020-10-22 16:39:12 --> 404 Page Not Found: Streaming/clients_live.php
ERROR - 2020-10-22 16:39:12 --> 404 Page Not Found: Stalker_portal/c
ERROR - 2020-10-22 16:39:12 --> 404 Page Not Found: Stalker_portal/c
ERROR - 2020-10-22 16:39:12 --> 404 Page Not Found: Apiphp/index
ERROR - 2020-10-22 16:39:12 --> 404 Page Not Found: Apiphp/index
ERROR - 2020-10-22 16:39:13 --> 404 Page Not Found: Loginphp/index
ERROR - 2020-10-22 16:39:13 --> 404 Page Not Found: Loginphp/index
ERROR - 2020-10-22 16:39:13 --> 404 Page Not Found: Streaming/index
ERROR - 2020-10-22 16:39:13 --> 404 Page Not Found: Streaming/clients_live.php
ERROR - 2020-10-22 16:39:13 --> 404 Page Not Found: Streaming/6JL4nSPiQ.php
ERROR - 2020-10-22 16:39:13 --> 404 Page Not Found: Streaming/index
ERROR - 2020-10-22 16:39:13 --> 404 Page Not Found: Streaming/clients_live.php
ERROR - 2020-10-22 16:39:14 --> 404 Page Not Found: Streaming/6JL4nSPiQ.php
ERROR - 2020-10-22 17:36:38 --> 404 Page Not Found: 404-pagehtml/index
ERROR - 2020-10-22 17:36:39 --> 404 Page Not Found: Product-extendhtml/index
ERROR - 2020-10-22 17:36:47 --> 404 Page Not Found: Shop-carouselhtml/index
ERROR - 2020-10-22 17:36:52 --> 404 Page Not Found: Indexhtml/index
ERROR - 2020-10-22 17:36:58 --> 404 Page Not Found: Comparehtml/index
ERROR - 2020-10-22 17:37:03 --> 404 Page Not Found: About-ushtml/index
ERROR - 2020-10-22 17:37:08 --> 404 Page Not Found: Blog-gridhtml/index
ERROR - 2020-10-22 17:37:13 --> 404 Page Not Found: Blog-listhtml/index
ERROR - 2020-10-22 17:37:19 --> 404 Page Not Found: Faqshtml/index
ERROR - 2020-10-22 17:37:25 --> 404 Page Not Found: Homepage-5html/index
ERROR - 2020-10-22 17:37:34 --> 404 Page Not Found: Whishlisthtml/index
ERROR - 2020-10-22 17:37:44 --> 404 Page Not Found: Homepage-7html/index
ERROR - 2020-10-22 17:37:53 --> 404 Page Not Found: Homepage-8html/index
ERROR - 2020-10-22 17:38:03 --> 404 Page Not Found: Homepage-3html/index
ERROR - 2020-10-22 17:38:12 --> 404 Page Not Found: Homepage-4html/index
ERROR - 2020-10-22 17:38:22 --> 404 Page Not Found: Homepage-6html/index
ERROR - 2020-10-22 17:38:31 --> 404 Page Not Found: Product-boxhtml/index
ERROR - 2020-10-22 17:38:41 --> 404 Page Not Found: Blog-detailhtml/index
ERROR - 2020-10-22 17:38:50 --> 404 Page Not Found: Homepage-10html/index
ERROR - 2020-10-22 17:39:00 --> 404 Page Not Found: Shop-sidebarhtml/index
ERROR - 2020-10-22 17:39:09 --> 404 Page Not Found: Vendor-storehtml/index
ERROR - 2020-10-22 17:39:19 --> 404 Page Not Found: Comming-soonhtml/index
ERROR - 2020-10-22 17:39:28 --> 404 Page Not Found: Shop-defaulthtml/index
ERROR - 2020-10-22 17:39:38 --> 404 Page Not Found: Blog-detail-4html/index
ERROR - 2020-10-22 17:39:47 --> 404 Page Not Found: Homepage-kidshtml/index
ERROR - 2020-10-22 17:39:57 --> 404 Page Not Found: Blog-detail-2html/index
ERROR - 2020-10-22 17:40:06 --> 404 Page Not Found: Order-trackinghtml/index
ERROR - 2020-10-22 17:40:16 --> 404 Page Not Found: Shop-categorieshtml/index
ERROR - 2020-10-22 17:40:25 --> 404 Page Not Found: Product-sidebarhtml/index
ERROR - 2020-10-22 17:40:35 --> 404 Page Not Found: Become-a-vendorhtml/index
ERROR - 2020-10-22 17:40:44 --> 404 Page Not Found: Product-defaulthtml/index
ERROR - 2020-10-22 17:40:54 --> 404 Page Not Found: Product-on-salehtml/index
ERROR - 2020-10-22 17:41:04 --> 404 Page Not Found: Blog-small-thumbhtml/index
ERROR - 2020-10-22 17:41:13 --> 404 Page Not Found: Product-grouppedhtml/index
ERROR - 2020-10-22 17:41:22 --> 404 Page Not Found: Product-instagramhtml/index
ERROR - 2020-10-22 17:41:32 --> 404 Page Not Found: Product-countdownhtml/index
ERROR - 2020-10-22 17:41:42 --> 404 Page Not Found: Product-out-stockhtml/index
ERROR - 2020-10-22 17:41:51 --> 404 Page Not Found: Product-affiliatehtml/index
ERROR - 2020-10-22 17:42:01 --> 404 Page Not Found: Blog-left-sidebarhtml/index
ERROR - 2020-10-22 17:42:10 --> 404 Page Not Found: Product-full-contenthtml/index
ERROR - 2020-10-22 17:42:20 --> 404 Page Not Found: Vendor-dashboard-prohtml/index
ERROR - 2020-10-22 17:42:29 --> 404 Page Not Found: Vendor-dashboard-freehtml/index
ERROR - 2020-10-22 17:42:39 --> 404 Page Not Found: Product-image-swatcheshtml/index
ERROR - 2020-10-22 17:42:49 --> 404 Page Not Found: Homepage-photo-and-videohtml/index
ERROR - 2020-10-22 18:31:11 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 18:49:44 --> 404 Page Not Found: Owa/auth
ERROR - 2020-10-22 19:10:29 --> 404 Page Not Found: Well-known/security.txt
ERROR - 2020-10-22 19:43:38 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 19:46:04 --> 404 Page Not Found: 
ERROR - 2020-10-22 20:45:12 --> 404 Page Not Found: Wp-loginphp/index
ERROR - 2020-10-22 21:06:26 --> 404 Page Not Found: 
ERROR - 2020-10-22 21:06:38 --> 404 Page Not Found: 
ERROR - 2020-10-22 21:09:12 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 21:45:50 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 21:47:03 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-10-22 22:41:05 --> 404 Page Not Found: 
ERROR - 2020-10-22 23:46:43 --> 404 Page Not Found: 
