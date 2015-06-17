<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();

	}

	public function send($subject, $msg)
	{
		$to = 'bob@example.com';

		$headers = "From: robot@bioleafy.com\r\n";
		$headers .= "Reply-To: robot@bioleafy.com\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		mail($to, $subject, $msg, $headers);
	}

	public function notify_shipment($sale_id)
	{
		$this->load->model('Sales_model');
		$sale = $this->sales_model->get($sale_id);

		$subject = 'Paquete #' . $sale['id'] . ' enviado';

		$msg = 'El paquete con el número de venta #' . $sale['id'] . ' ha sido enviado:';
		$msg .= '<table>';
		$msg .= '<tr><td>Comprador:</td><td>' . $sale['name'] . '</td></tr>';
		$msg .= '<tr><td>Código de Rastreo:</td><td>' . $sale['track_code'] . '</td></tr>';
		$msg .= '<tr><td>Paquete:</td><td>' . implode(',', $sale['package']) . '</td></tr>';
		$msg .= '</table>';

		$this->send($subject, $msg);
	}

}

/* End of file Mail_model.php */
/* Location: ./application/models/Mail_model.php */