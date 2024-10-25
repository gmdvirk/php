<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin/customers/edit-json/(:num)'] = 'customers/edit_json/$1';
$route['admin/managers/edit-json/(:num)'] = 'managers/edit_json/$1';

$route['admin/managers/upload-firmware'] = 'managers/uploadFirmware';