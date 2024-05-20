<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		log_message('debug', 'this is debug log');
		$this->load->view('index');
	}
}
