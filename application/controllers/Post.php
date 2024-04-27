<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 *  Post Controller
 */
class Post extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('crud');
	}

	public function index()
	{
		$data['data'] = $this->crud->get_records('posts');
		$this->load->view('post/list', $data);
	}

	public function create()
	{
		$this->load->view('post/create');
	}

	public function store()
	{
		$data['title'] = $this->input->post('title');
		$data['description'] = $this->input->post('description');

		$this->crud->insert('posts', $data);
		$this->session->set_flashdata('message', '<div class="alert alert-success">Record has been saved successfully.</div>');
		redirect(base_url());
	}

	public function edit($id)
	{
		$data['data'] = $this->crud->find_record_by_id('posts', $id);
		$this->load->view('post/edit', $data);
	}

	public function update($id)
	{
		$data['title'] = $this->input->post('title');
		$data['description'] = $this->input->post('description');

		$this->crud->update('posts', $data, $id);
		$this->session->set_flashdata('message', '<div class="alert alert-success">Record has been updated successfully.</div>');
		redirect(base_url());
	}

	public function delete($id)
	{
		$this->crud->delete('posts', $id);
		$this->session->set_flashdata('message', '<div class="alert alert-success">Record has been deleted successfully.</div>');
		redirect(base_url());
	}
}