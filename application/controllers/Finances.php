<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Finances extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('finances/history');
	}

}

/* End of file Finances.php */
/* Location: ./application/controllers/Finances.php */