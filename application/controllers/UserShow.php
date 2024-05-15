<?php

class UserShow extends CI_Controller {

//	public function __construct() {
//		parent::__construct();
//		$this->load->model('Item_model');
//	}

	public function index()
	{
		log_message('debug', 'this is debug log');
		$this->load->view('index');
	}
}
