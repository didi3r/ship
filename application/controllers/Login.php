<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
    
    public function __construct()
	{
		parent::__construct();
	}

	public function index($error = false)
	{
        if ($this->authentication->is_loggedin()) {
            redirect('welcome');
        }
        $data = array();
        $data['error'] = $error;
        $data['url'] = isset($_GET['url']) ? $_GET['url'] : null;
		
        $this->load->view('login', $data);
	}
    
    public function auth()
    {
        if ($this->authentication->is_loggedin()) {
            redirect('welcome');
        }
        
        // Read the username
        $username = $this->input->post('username');

        // Read the password
        $password = $this->input->post('password');

        // Read the url
        $url = $this->input->post('url');
        // Try to log the user in
        if ($this->authentication->login($username, $password))
        {
            if($url) {
                header('Location: ' . $url);
            } else {
                redirect('welcome');
            }
        } else {
            $url = $url ? '?url=' . $url : '';
            redirect('login/index/true' . $url);
        }
    }
    
    public function logout()
    {
        $this->authentication->logout();
        redirect('login');
    }
    
//    public function create_users()
//    {
//        $this->authentication->create_user('ventas.nd.fm@gmail.com', 'dorian066');
//        $this->authentication->create_user('ddr2002@prodigy.net.mx', 'abc123!');
//        echo 'Usuarios creados exitosamente';
//    }
    
    
}

