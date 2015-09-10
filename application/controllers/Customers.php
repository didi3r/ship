<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customers extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('customers/list');
	}

}

/* End of file Customers.php */
/* Location: ./application/controllers/Customers.php */