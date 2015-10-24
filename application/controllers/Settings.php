<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->authentication->is_loggedin()) {
            redirect('login/?url=' . "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        }

	}

	public function index()
	{
		$this->load->view('settings/index');
	}

	public function get_users()
	{
		if($this->authentication->is_admin()) {
			$this->load->model('users_model');
			$output = $this->users_model->get_all();

			header('Content-Type: application/json');
        	echo json_encode($output);
		}
	}

}

/* End of file Settings.php */
/* Location: ./application/controllers/Settings.php */