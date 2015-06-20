<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();

	}

	public function send($to, $subject, $msg)
	{
		$headers = "From: robot@bioleafy.com\r\n";
		$headers .= "Reply-To: robot@bioleafy.com\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		mail($to, $subject, $msg, $headers);
	}

	public function send_to_admin($subject, $msg)
	{
		$to = 'ventas.nd.fm@gmail.com';

		$this->send($to, $subject, $msg);
	}

	public function notify_shipment($sale_id)
	{
		$this->load->model('sales_model');
		$sale = $this->sales_model->get($sale_id);

		$data = array(
			'id' => $sale['id'],
			'name' => $sale['name'],
			'courier' => $sale['delivery']['courier'],
			'track_code' => $sale['delivery']['trackCode'],
			'package' => implode(',', $sale['package'])
		);

		$subject = '¡Tu paquete ha sido enviado!';
		$msg = $this->load->view('mails/customer/shipped', $data, true);
		// $this->send($sale['email'], $subject, $msg);
		$this->send_to_admin($subject, $msg);

		$subject = 'Paquete #' . $sale['id'] . ' enviado';
		$msg = $this->load->view('mails/admin/shipped', $data, true);
		$this->send_to_admin($subject, $msg);
	}

}

/* End of file Mail_model.php */
/* Location: ./application/models/Mail_model.php */