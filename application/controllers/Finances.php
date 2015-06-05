<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Finances extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        if (!$this->authentication->is_loggedin()) {
            redirect('login/?url=' . "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        }
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
            'today' => date('Y-m-d'),
			'start_date' => date('Y-m-d', strtotime('last Friday')),
			'end_date' => date('Y-m-d', strtotime('next Thursday'))
		);

		$this->load->view('finances/expenses', $data);
	}

    public function inversions()
	{
		date_default_timezone_set('America/Mexico_City');
		$data = array(
			'today' => date('Y-m-d')
		);

		$this->load->view('finances/inversions', $data);
	}

	public function transfers()
	{
		date_default_timezone_set('America/Mexico_City');
        $data = array(
			'today' => date('Y-m-d')
		);
		$this->load->view('finances/transfers', $data);
	}

}



/* End of file Finances.php */
/* Location: ./application/controllers/Finances.php */