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

	public function add()
	{
		$this->load->view('sales/add');
	}

	public function listall()
	{
		$this->load->view('sales/list');
	}

}
