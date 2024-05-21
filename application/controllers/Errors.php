<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function show_404() {
		$this->output->set_status_header('404');
		$this->load->view('custom_errors/custom_404');
	}
}
