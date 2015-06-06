<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shipments extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        if (!$this->authentication->is_loggedin()) {
            redirect('login/?url=' . "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        }
	}

	public function index()
	{
		$this->load->view('shipments/list');
	}
}

/* End of file Shipmentsphp */
/* Location: ./application/controllers/Shipmentsphp */