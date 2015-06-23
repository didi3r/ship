<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{

	}

	// public function shipped()
	// {
	// 	$this->load->model('mail_model');
	// 	$this->mail_model->notify_shipment(99);
	// }

	// public function ended()
	// {
	// 	$this->load->model('mail_model');
	// 	$this->mail_model->notify_ended(99);
	// }

	// public function payment()
	// {
	// 	$this->load->model('mail_model');
	// 	$this->mail_model->notify_payment(99);
	// }

	public function admin_shipped()
	{
		$this->load->view('mails/admin/shipped');
	}

	public function customer_details()
	{
		$this->load->view('mails/customer/sale_details');
	}

	public function customer_shipped()
	{
		$this->load->view('mails/customer/shipped');
	}

	public function customer_ended()
	{
		$this->load->view('mails/customer/ended');
	}

}

/* End of file Mail.php */
/* Location: ./application/controllers/Mail.php */