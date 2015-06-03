<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Finances extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		date_default_timezone_set('America/Mexico_City');
		$data = array(
			'start_date' => date('Y-m-d', strtotime('last Friday')),
			'end_date' => date('Y-m-d', strtotime('next Thursday'))
		);

		$this->load->view('finances/history', $data);
	}

    public function expenses()
	{
		date_default_timezone_set('America/Mexico_City');
		$data = array(
			'start_date' => date('Y-m-d', strtotime('last Friday')),
			'end_date' => date('Y-m-d', strtotime('next Thursday'))
		);

		$this->load->view('finances/expenses', $data);
	}

}

/* End of file Finances.php */
/* Location: ./application/controllers/Finances.php */