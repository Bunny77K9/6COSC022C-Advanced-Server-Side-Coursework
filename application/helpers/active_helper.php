<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('active_link'))
{
	function menu_activate($controller)
	{
		$CodeIgniter = get_instance();
		$classname = $CodeIgniter->router->fetch_class();
		// Return class active as a string.
		return ($classname == $controller) ? 'active' : '';
	}
}
