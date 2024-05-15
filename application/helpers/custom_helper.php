<?php

if (!function_exists('log_info')) {
	function log_info($message) {
		$CI =& get_instance();
		$CI->load->library('logger');
		$CI->logger->info($message);
	}
}

