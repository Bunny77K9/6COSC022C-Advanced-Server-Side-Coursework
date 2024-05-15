<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logger {

	public function log_controller_method() {
		$CI =& get_instance();
		$controller = $CI->router->fetch_class();
		$method = $CI->router->fetch_method();
		log_message('info', 'Current controller: ' . $controller . ' | Current method: ' . $method);
	}

}
