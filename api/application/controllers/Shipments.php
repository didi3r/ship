<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shipments extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('shipments/list');
	}

}

/* End of file Shipmentsphp */
/* Location: ./application/controllers/Shipmentsphp */