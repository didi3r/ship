<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notifications extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('notifications_model');
	}

	public function index()
	{

	}

	public function shipped($id)
	{
		$this->notifications_model->notify_shipment($id);
	}

	// public function ended()
	// {
	// 	$this->load->model('notifications_model');
	// 	$this->notifications_model->notify_ended(202);
	// }

	public function payment($id)
	{
		$this->notifications_model->notify_payment($id);
	}

	// public function launch()
	// {
	// 	$this->load->model('notifications_model');
	// 	$this->notifications_model->launch_mail();
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
}

/* End of file Mail.php */
/* Location: ./application/controllers/Mail.php */