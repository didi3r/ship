<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('DOMAIN', 'moringa-michoacana.com.mx');
define('MAILGUN_API', 'key-1c635ed3f984f6760a9af3057813366f');

class Mail_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();

	}

	private function mailgun($to, $subject, $message) {

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, 'api:'.MAILGUN_API);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$plain = strip_tags(preg_replace('/\<br(\s*)?\/?\>/i', PHP_EOL, $message));

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v3/'.DOMAIN.'/messages');
		curl_setopt($ch, CURLOPT_POSTFIELDS, array(
			'from' => 'VENTAS ND <postmaster@'.DOMAIN.'>',
			'h:Reply-To' => 'VENTAS ND <ventas.nd.fm@gmail.com>',
			'to' => $to,
			'subject' => '[Bioleafy] ' . $subject,
			'html' => $message,
			'text' => $plain));

		$j = json_decode(curl_exec($ch));

		$info = curl_getinfo($ch);

		if($info['http_code'] != 200)
			die("HTTP ".$info['http_code'].": Error al enviar correo mediante mailgun");

		curl_close($ch);

		echo $j;
		return $j;
	}

	private function plivo($to, $msg) {
		require_once APPPATH.'third_party/plivo.php';

		$auth_id = "MAZGQ2M2FHMJYYZMM4MW";
	    $auth_token = "YzFmNDc2NDUxMGViOTRmNWU0NmViMWJkOWIzNTU3";
	    $p = new RestAPI($auth_id, $auth_token);
	    // Send a message
	    $params = array(
	            'src' => '5214431454951',
	            'dst' => $to,
	            'text' => $msg,
	            'type' => 'sms',
	        );
	    $response = $p->send_message($params);
	}

	public function send($to, $subject, $msg)
	{
		// $headers = "From: Ventas ND <robot@moringa-michoacana.com.mx>\r\n";
		// if($to != 'ventas.nd.fm@gmail.com') {
		// 	$headers .= 'Bcc: ventas.nd.fm@gmail.com' . "\r\n";
		// }
		// $headers .= "Reply-To: Ventas ND <ventas.nd.fm@gmail.com>\r\n";
		// $headers .= "MIME-Version: 1.0\r\n";
		// $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		// mail($to, $subject, $msg, $headers);
		$this->mailgun($to, $subject, $msg);
	}

	public function send_to_admin($subject, $msg)
	{
		$to = 'ventas.nd.fm@gmail.com';

		$this->send($to, $subject, $msg);
	}

	public function notify_payment($sale_id)
	{
		$this->load->model('sales_model');
		$sale = $this->sales_model->get($sale_id);


		$data = array(
			'id' => $sale['id'],
			'name' => $sale['name'],
			'courier' => $sale['delivery']['courier'],
			'package' => $sale['package'],
			'addressee' => !empty($sale['delivery']['addressee']) ? $sale['delivery']['addressee'] : $sale['name'],
			'address' => nl2br($sale['delivery']['address']),
			'subtotal' => $sale['payment']['total'],
			'shipment_cost' => $sale['delivery']['cost'],
			'total' => $sale['delivery']['cost'] + $sale['payment']['total']
		);

		$subject = 'Detalles de tu envío';
		$msg = $this->load->view('mails/customer/sale_details', $data, true);

		if($this->sale_has_email($sale)) {
			$this->send($sale['email'], $subject, $msg);
		}
		// $this->send_to_admin($subject, $msg);
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

		if($this->sale_has_email($sale)) {
			$subject = '¡Tu paquete ha sido enviado!';
			$msg = $this->load->view('mails/customer/shipped', $data, true);
			$this->send($sale['email'], $subject, $msg);
			// $this->send_to_admin($subject, $msg);
		}

		$subject = 'Paquete #' . $sale['id'] . ' enviado';
		$msg = $this->load->view('mails/admin/shipped', $data, true);
		$this->send_to_admin($subject, $msg);
	}

	public function notify_ended($sale_id)
	{
		$this->load->model('sales_model');
		$sale = $this->sales_model->get($sale_id);

		$data = array(
			'is_mercado_libre' => $sale['wc_id'] ? false : true
		);

		$subject = '¡Fue un placer atenderte!';
		$msg = $this->load->view('mails/customer/ended', $data, true);

		if($this->sale_has_email($sale)) {
			$this->send($sale['email'], $subject, $msg);
		}
		// $this->send_to_admin($subject, $msg);
	}

	// public function launch_mail()
	// {
	// 	$subject = '10% de Descuento en Moringa Michoacana';
	// 	$msg = $this->load->view('mails/customer/launch', '', true);
	// 	// $this->send('customers@moringa-michoacana.com.mx', $subject, $msg);
	// 	// $this->send_to_admin($subject, $msg);
	// }

	private function sale_has_email($sale) {
		return isset($sale['email']) && $sale['email'] && $sale['email'] != '';
	}

	public function sms() {
		$this->plivo('5214431454951', 'Moringa Michoacana. Tu paquete ha sido enviado, tu codigo es: MN531977262MX');
		$this->plivo('5214433365183', 'Moringa Michoacana. Tu paquete ha sido enviado, tu codigo es: MN531977262MX');
	}

}

/* End of file Mail_model.php */
/* Location: ./application/models/Mail_model.php */