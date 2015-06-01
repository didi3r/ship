<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index($page = 1)
	{
		$this->load->view('sales/list');
	}

	public function add($id = null)
	{
		if($id) {
			$this->load->model('sales_model');
			$sale = $this->sales_model->get($id);
			$this->load->view('sales/add', $sale['response'][0]);
		} else {
			$this->load->view('sales/add');
		}
	}

	public function listall()
	{
		$this->load->view('sales/list');
	}

}
