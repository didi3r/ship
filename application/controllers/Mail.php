<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{

	}

	public function shipped($id)
	{
		$this->load->model('mail_model');
		$this->mail_model->notify_shipment($id);
	}

	// public function ended()
	// {
	// 	$this->load->model('mail_model');
	// 	$this->mail_model->notify_ended(202);
	// }

	public function payment($id)
	{
		$this->load->model('mail_model');
		$this->mail_model->notify_payment($id);
	}

	// public function launch()
	// {
	// 	$this->load->model('mail_model');
	// 	$this->mail_model->launch_mail();
	// }

	// public function customer_details()
	// {
	// 	$this->load->view('mails/customer/sale_details');
	// }

	// public function customer_shipped()
	// {
	// 	$this->load->view('mails/customer/shipped');
	// }

	// public function customer_ended()
	// {
	// 	$this->load->view('mails/customer/ended');
	// }


	public function sms() {
		$this->load->model('mail_model');
		$this->mail_model->sms();
	}

}

/* End of file Mail.php */
/* Location: ./application/controllers/Mail.php */