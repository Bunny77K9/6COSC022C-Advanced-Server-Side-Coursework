<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['api/User/login'] = 'api/User/login';
$route['api/User/registration'] = 'api/User/registration';
