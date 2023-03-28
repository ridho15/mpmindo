<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// inventories
$config['permission']['inventory'] = ['index'];

// catalog
$config['permission']['product'] = ['index', 'create', 'edit', 'delete'];
$config['permission']['category'] = ['index', 'create', 'edit', 'delete'];

// page
$config['permission']['page'] = ['index', 'create', 'edit', 'delete'];

// marketing
$config['permission']['banner'] = ['index', 'create', 'edit', 'delete'];
$config['permission']['home_widget'] = ['index', 'edit'];

// orders
$config['permission']['order'] = ['index', 'edit', 'delete'];
$config['permission']['payment_confirmations'] = ['index', 'delete'];

// users
$config['permission']['users'] = ['index', 'create', 'edit', 'delete'];

// reports
$config['permission']['report'] = ['index'];

// administrator
$config['permission']['admin'] = ['index', 'create', 'edit', 'delete'];
$config['permission']['admin_group'] = ['index', 'create', 'edit', 'delete'];

// settings
$config['permission']['settings'] = ['index', 'edit'];
$config['permission']['order_status'] = ['index', 'create', 'edit', 'delete'];
$config['permission']['weight_class'] = ['index', 'create', 'edit', 'delete'];
$config['permission']['unit_class'] = ['index', 'create', 'edit', 'delete'];
$config['permission']['bank'] = ['index', 'create', 'edit', 'delete'];
$config['permission']['bank_account'] = ['index', 'create', 'edit', 'delete'];
$config['permission']['payment_modules'] = ['index', 'edit', 'delete'];
$config['permission']['shipping_cost'] = ['index', 'create', 'edit', 'delete'];