<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
    
    public function __construct()
	{
		parent::__construct();
        if (!$this->authentication->is_loggedin()) {
            redirect('login/?url=' . "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        }
	}

	public function index()
	{
		$this->load->view('dashboard');
	}
}

