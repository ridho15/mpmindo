<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
//$route['default_controller'] = 'page/home';
$route['default_controller'] = 'murdock/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

if (PATH_ADMIN !== 'admin') {
	$route['admin'] = '';
	$route['admin/(:any)'] = '';
}

$route[PATH_ADMIN] = 'admin/dashboard';
$route[PATH_ADMIN.'/([a-zA-Z0-9_-]+)'] = 'admin/$1';
$route[PATH_ADMIN.'/([a-zA-Z0-9_-]+)/(:any)'] = 'admin/$1/$2';
$route[PATH_ADMIN.'/([a-zA-Z0-9_-]+)/(:any)/(:any)'] = 'admin/$1/$2/$3';
$route[PATH_ADMIN.'/([a-zA-Z0-9_-]+)/(:any)/(:any)/(:any)'] = 'admin/$1/$2/$3/$4';

if (PATH_USER !== 'user') {
	$route['user'] = '';
	$route['user/(:any)'] = '';
}

$route[PATH_USER] = 'user/dashboard';
$route[PATH_USER.'/activation/([a-zA-Z0-9_-]+)'] = 'user/activation/index/$1';
$route[PATH_USER.'/([a-zA-Z0-9_-]+)'] = 'user/$1';
$route[PATH_USER.'/([a-zA-Z0-9_-]+)/(:any)'] = 'user/$1/$2';
$route[PATH_USER.'/([a-zA-Z0-9_-]+)/(:any)/(:any)'] = 'user/$1/$2/$3';

$route['product/review'] = 'product/review';
$route['product/comment'] = 'product/comment';
$route['product/quick_view'] = 'product/quick_view';
$route['product/(:any)'] = 'product/index';
$route['product/(:any)/(:any)'] = 'product/index';
$route['product/(:any)/(:any)/(:any)'] = 'product/index';
$route['product/(:any)/(:any)/(:any)/(:any)'] = 'product/index';
$route['category/([a-zA-Z0-9_-]+)'] = 'category/index/$1';
$route['category/([a-zA-Z0-9_-]+)/(:any)'] = 'category/index/$1/$2';
$route['category/([a-zA-Z0-9_-]+)/(:any)/(:any)'] = 'category/index/$1/$2/$3';

$route['store'] = 'store/index/$1';
$route['store/([a-zA-Z0-9_-]+)'] = 'store/index/$1';
$route['store/([a-zA-Z0-9_-]+)/(:any)'] = 'store/index/$1/$2';

$route['blog/([a-zA-Z0-9_-]+)'] = 'blog/article/$1';
$route['blog/(:num)/(:num)'] = 'blog/archive/$1/$2';
$route['page/success'] = 'page/success';
$route['page/([a-zA-Z0-9_-]+)'] = 'page/index/$1';
$route['special/([a-zA-Z0-9_-]+)'] = 'special/product/$1';
$route['sitemap.xml'] = 'sitemap/xml';
$route['captcha'] = 'captcha';
$route['provinces'] = 'provinces';

$route['payments/([a-zA-Z0-9_-]+)'] = 'payments/index/$1';
$route['payments/([a-zA-Z0-9_-]+)/(:any)'] = 'payments/index/$1/$2';

$route['compare_product/find_product'] = 'compare_product/find_product';
$route['compare_product/auto_complete'] = 'compare_product/auto_complete';
$route['compare_product/([a-zA-Z0-9_-]+)'] = 'compare_product/index/$1';
$route['compare_product/([a-zA-Z0-9_-]+)/(:any)'] = 'compare_product/index/$1/$2';
$route['compare_product/([a-zA-Z0-9_-]+)/(:any)/(:any)'] = 'compare_product/index/$1/$2/$3';
