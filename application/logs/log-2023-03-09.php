<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2023-03-09 01:03:40 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 01:17:57 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 01:36:37 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 02:27:38 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 02:34:23 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 03:35:16 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 05:23:07 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 05:54:30 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 06:15:07 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 06:56:41 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 08:23:56 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 09:44:01 --> 404 Page Not Found: Apple-touch-icon-120x120-precomposedpng/index
ERROR - 2023-03-09 09:44:03 --> 404 Page Not Found: Apple-touch-icon-120x120png/index
ERROR - 2023-03-09 09:44:06 --> 404 Page Not Found: Apple-touch-icon-precomposedpng/index
ERROR - 2023-03-09 09:44:22 --> 404 Page Not Found: Apple-touch-iconpng/index
ERROR - 2023-03-09 09:47:24 --> Severity: Notice --> Undefined property: stdClass::$id /home/u9620284/public_html/mpmindo/application/controllers/Checkout.php 482
ERROR - 2023-03-09 09:47:25 --> Severity: Notice --> Undefined property: stdClass::$id /home/u9620284/public_html/mpmindo/application/controllers/Checkout.php 482
ERROR - 2023-03-09 09:47:45 --> Severity: Notice --> Undefined property: stdClass::$id /home/u9620284/public_html/mpmindo/application/controllers/Checkout.php 482
ERROR - 2023-03-09 09:47:58 --> Severity: Notice --> Undefined property: stdClass::$id /home/u9620284/public_html/mpmindo/application/controllers/Checkout.php 482
ERROR - 2023-03-09 09:48:28 --> Severity: Notice --> Undefined property: stdClass::$id /home/u9620284/public_html/mpmindo/application/controllers/Checkout.php 482
ERROR - 2023-03-09 09:48:32 --> Severity: Notice --> Undefined property: stdClass::$id /home/u9620284/public_html/mpmindo/application/controllers/Checkout.php 482
ERROR - 2023-03-09 10:12:01 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 10:12:20 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 10:18:25 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 11:19:14 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 12:35:22 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 13:51:24 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 14:03:48 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 14:08:19 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 14:59:44 --> 404 Page Not Found: Well-known/traffic-advice
ERROR - 2023-03-09 15:00:15 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 16:07:06 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 16:08:34 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 16:09:17 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 17:49:01 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 17:51:24 --> Severity: Warning --> mysqli::real_connect(): (HY000/1203): User u9620284_u_mpmwahl_test already has more than 'max_user_connections' active connections /home/u9620284/public_html/mpmindo/system/database/drivers/mysqli/mysqli_driver.php 203
ERROR - 2023-03-09 17:51:24 --> Unable to connect to the database
ERROR - 2023-03-09 18:59:27 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 20:05:43 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 21:45:43 --> 404 Page Not Found: Robotstxt/index
ERROR - 2023-03-09 21:46:18 --> Query error: MySQL server has gone away - Invalid query: SELECT distinct *, (select group_concat(c1.name order by level separator ' &raquo; ') from category_path cp left join category c1 on (cp.path_id = c1.category_id and cp.category_id != cp.path_id) where cp.category_id = c.category_id group by cp.category_id) as path, (select group_concat(c1.category_id order by level separator '-') from category_path cp left join category c1 on (cp.path_id = c1.category_id and cp.category_id != cp.path_id) where cp.category_id = c.category_id group by cp.category_id) as path_id
FROM `category` `c`
WHERE `c`.`slug` = 'compare_product'
ERROR - 2023-03-09 21:46:18 --> Query error: MySQL server has gone away - Invalid query: INSERT INTO `knr_session` (`id`, `ip_address`, `timestamp`, `data`) VALUES ('1d2c90676e42b4003424319e1fe4265b95fa2660', '185.191.171.17', 1678373178, '__ci_last_regenerate|i:1678373146;currency|s:3:\"IDR\";')
ERROR - 2023-03-09 21:46:18 --> Severity: Warning --> Unknown: Cannot call session save handler in a recursive manner Unknown 0
ERROR - 2023-03-09 21:46:18 --> Severity: Warning --> Unknown: Failed to write session data using user defined save handler. (session.save_path: /opt/alt/php74/var/lib/php/session) Unknown 0
ERROR - 2023-03-09 21:46:18 --> Query error: MySQL server has gone away - Invalid query: SELECT RELEASE_LOCK('b358a6d9d70465f973f48e9ce9e4892b') AS ci_session_lock
ERROR - 2023-03-09 21:46:18 --> Severity: Warning --> Cannot modify header information - headers already sent /home/u9620284/public_html/mpmindo/system/core/Common.php 570
ERROR - 2023-03-09 21:53:51 --> 404 Page Not Found: Storage/images
ERROR - 2023-03-09 21:54:09 --> Severity: Warning --> mysqli::real_connect(): (HY000/1203): User u9620284_u_mpmwahl_test already has more than 'max_user_connections' active connections /home/u9620284/public_html/mpmindo/system/database/drivers/mysqli/mysqli_driver.php 203
ERROR - 2023-03-09 21:54:09 --> Unable to connect to the database
ERROR - 2023-03-09 21:54:09 --> Severity: Warning --> mysqli::real_connect(): (HY000/1203): User u9620284_u_mpmwahl_test already has more than 'max_user_connections' active connections /home/u9620284/public_html/mpmindo/system/database/drivers/mysqli/mysqli_driver.php 203
ERROR - 2023-03-09 21:54:09 --> Unable to connect to the database
ERROR - 2023-03-09 23:06:16 --> 404 Page Not Found: Robotstxt/index
