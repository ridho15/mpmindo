<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2020-11-26 00:46:36 --> 404 Page Not Found: 
ERROR - 2020-11-26 00:58:19 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 01:49:13 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 03:10:20 --> 404 Page Not Found: 
ERROR - 2020-11-26 05:14:22 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 05:33:32 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 06:57:33 --> 404 Page Not Found: 
ERROR - 2020-11-26 07:09:54 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 07:54:44 --> 404 Page Not Found: 
ERROR - 2020-11-26 08:24:44 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 08:24:46 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 08:53:34 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 09:02:15 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 09:52:34 --> 404 Page Not Found: 
ERROR - 2020-11-26 09:54:14 --> Query error: Table 'dt_commerce.blog_category' doesn't exist - Invalid query: SELECT (SELECT COUNT(blog_category_id) FROM blog_category_article bca LEFT JOIN blog_article ba ON(ba.blog_article_id = bca.blog_article_id) WHERE bca.blog_category_id = bc.blog_category_id AND ba.active = '1') as article_num, bc.blog_category_id, bc.name, bc.slug
FROM `blog_category` `bc`
WHERE `bc`.`active` = 1
ORDER BY `bc`.`sort_order` ASC
ERROR - 2020-11-26 09:54:16 --> 404 Page Not Found: Wp/index
ERROR - 2020-11-26 09:54:18 --> 404 Page Not Found: Wordpress/index
ERROR - 2020-11-26 09:54:20 --> 404 Page Not Found: New/index
ERROR - 2020-11-26 09:54:22 --> 404 Page Not Found: Old/index
ERROR - 2020-11-26 09:54:23 --> 404 Page Not Found: Test/index
ERROR - 2020-11-26 09:54:25 --> 404 Page Not Found: Main/index
ERROR - 2020-11-26 09:54:27 --> 404 Page Not Found: Site/index
ERROR - 2020-11-26 09:54:30 --> 404 Page Not Found: Backup/index
ERROR - 2020-11-26 09:54:32 --> 404 Page Not Found: Demo/index
ERROR - 2020-11-26 09:54:34 --> 404 Page Not Found: Home/index
ERROR - 2020-11-26 09:54:35 --> 404 Page Not Found: Tmp/index
ERROR - 2020-11-26 09:54:37 --> 404 Page Not Found: Cms/index
ERROR - 2020-11-26 09:54:39 --> 404 Page Not Found: Dev/index
ERROR - 2020-11-26 09:54:41 --> 404 Page Not Found: Old-wp/index
ERROR - 2020-11-26 09:54:42 --> 404 Page Not Found: Web/index
ERROR - 2020-11-26 09:54:44 --> 404 Page Not Found: Old-site/index
ERROR - 2020-11-26 09:54:45 --> 404 Page Not Found: Temp/index
ERROR - 2020-11-26 09:54:49 --> 404 Page Not Found: 2018/index
ERROR - 2020-11-26 09:54:50 --> 404 Page Not Found: 2019/index
ERROR - 2020-11-26 09:54:52 --> 404 Page Not Found: Bk/index
ERROR - 2020-11-26 09:54:53 --> 404 Page Not Found: Wp1/index
ERROR - 2020-11-26 09:54:56 --> 404 Page Not Found: Wp2/index
ERROR - 2020-11-26 09:54:58 --> 404 Page Not Found: V1/index
ERROR - 2020-11-26 09:55:00 --> 404 Page Not Found: V2/index
ERROR - 2020-11-26 09:55:02 --> 404 Page Not Found: Bak/index
ERROR - 2020-11-26 09:55:03 --> 404 Page Not Found: Install/index
ERROR - 2020-11-26 09:55:06 --> 404 Page Not Found: 2020/index
ERROR - 2020-11-26 09:55:07 --> 404 Page Not Found: New-site/index
ERROR - 2020-11-26 10:29:29 --> 404 Page Not Found: 
ERROR - 2020-11-26 10:29:30 --> 404 Page Not Found: 
ERROR - 2020-11-26 11:14:30 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 12:04:41 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 12:28:43 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 12:38:16 --> 404 Page Not Found: C99php/index
ERROR - 2020-11-26 12:38:16 --> 404 Page Not Found: Env/index
ERROR - 2020-11-26 12:38:16 --> 404 Page Not Found: Backupsql/index
ERROR - 2020-11-26 12:38:16 --> 404 Page Not Found: Git/HEAD
ERROR - 2020-11-26 12:38:16 --> 404 Page Not Found: Phpinfophp/index
ERROR - 2020-11-26 12:38:16 --> 404 Page Not Found: Configphp/index
ERROR - 2020-11-26 13:26:05 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 14:10:34 --> Query error: Expression #2 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dt_commerce.op.name' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT SUM(op.quantity) as total, op.name, op.product_id
FROM `order_product` `op`
LEFT JOIN `order` `o` ON `o`.`order_id` = `op`.`order_id`
WHERE (o.date_added BETWEEN "2020-11-23 00:00:00" AND "2020-11-29 23:59:00")
GROUP BY `op`.`product_id`
ORDER BY `total` ASC
 LIMIT 10
ERROR - 2020-11-26 14:12:04 --> 404 Page Not Found: 
ERROR - 2020-11-26 14:12:22 --> 404 Page Not Found: 
ERROR - 2020-11-26 14:13:04 --> 404 Page Not Found: 
ERROR - 2020-11-26 14:57:10 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 14:57:19 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 17:02:11 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 17:31:07 --> 404 Page Not Found: Actuator/configprops
ERROR - 2020-11-26 17:39:00 --> 404 Page Not Found: 
ERROR - 2020-11-26 20:04:24 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 20:12:22 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 21:38:16 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 22:14:40 --> 404 Page Not Found: 
ERROR - 2020-11-26 22:43:11 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 23:04:43 --> 404 Page Not Found: 
ERROR - 2020-11-26 23:24:34 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 23:39:01 --> 404 Page Not Found: Robotstxt/index
ERROR - 2020-11-26 23:48:24 --> 404 Page Not Found: Login_sidlua/index
ERROR - 2020-11-26 23:55:21 --> 404 Page Not Found: 
