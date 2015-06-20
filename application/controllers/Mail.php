<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{

	}

	public function shipped()
	{
		$this->load->model('mail_model');
		$this->mail_model->notify_shipment(99);
	}

	public function admin_shipped()
	{
		$this->load->view('mails/admin/shipped');
	}

	public function customer_shipped()
	{
		$this->load->view('mails/customer/shipped');
	}

}

/* End of file Mail.php */
/* Location: ./application/controllers/Mail.php */