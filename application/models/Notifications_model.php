<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('MAILGUN_DOMAIN', 'moringa-michoacana.com.mx');
define('MAILGUN_SANDBOX_DOMAIN', 'sandboxb1dca6b16b5b44ffbbf5b78bdbecb697.mailgun.org');
define('MAILGUN_API', 'key-1c635ed3f984f6760a9af3057813366f');

define('PLIVO_ID', 'MAZGQ2M2FHMJYYZMM4MW');
define('PLIVO_TOKEN', 'YzFmNDc2NDUxMGViOTRmNWU0NmViMWJkOWIzNTU3');

define('SMS_NEWORDER_TEXT', 'Moringa-Michoacana.com.mx: Tu pedido ha sido registrado. Revisa tu correo para mayor informacion (Revisa el correo no deseado o spam). ¿Dudas? 4432678843');
define('SMS_PAYMENT_TEXT', 'Moringa-Michoacana.com.mx: Tu pago ha sido confirmado. Cuando tu paquete este en camino te enviaremos tu codigo de rastreo. ¿Dudas? 4432678843');
define('SMS_SHIPPED_TEXT', 'Moringa-Michoacana.com.mx: ¡Tu paquete ya esta en camino!. Tu codigo de rastreo es: *. ¿Dudas? 4432678843');

class Notifications_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();

	}

	private function mailgun($to, $subject, $message) {
		if(ENVIRONMENT == 'development') {
			$domain = MAILGUN_SANDBOX_DOMAIN;
			$to = 'ventas.nd.fm@gmail.com';
		} else {
			$domain = MAILGUN_DOMAIN;
		}

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, 'api:'.MAILGUN_API);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$plain = strip_tags(preg_replace('/\<br(\s*)?\/?\>/i', PHP_EOL, $message));

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v3/'. $domain .'/messages');
		curl_setopt($ch, CURLOPT_POSTFIELDS, array(
			'from' => 'Bioleafy <postmaster@'.MAILGUN_DOMAIN.'>',
			'h:Reply-To' => 'Bioleafy <ventas.nd.fm@gmail.com>',
			'to' => $to,
			'subject' => '[Bioleafy] ' . $subject,
			'html' => $message,
			'text' => $plain));

		$j = json_decode(curl_exec($ch));

		$info = curl_getinfo($ch);

		if($info['http_code'] != 200)
			die("HTTP ".$info['http_code'].": Error al enviar correo mediante mailgun - " . curl_error($ch));

		curl_close($ch);

		echo $j;
		return $j;
	}

	private function plivo($to, $msg) {
		if(ENVIRONMENT == 'development') return false;

		$to = preg_replace('/\D/', '', $to);

		if(strlen($to) == 10) {
			require_once APPPATH.'third_party/plivo.php';

		    $p = new RestAPI(PLIVO_ID, PLIVO_TOKEN);
		    // Send a message
		    $params = array(
	            'src' => '5214432678843',
	            'dst' => '521' . $to,
	            'text' => $msg,
	            'type' => 'sms',
	        );

	    	return $p->send_message($params);
		}
	}

	public function send_to_admin($subject, $msg)
	{
		$to = 'ventas.nd.fm@gmail.com';
		$this->mailgun($to, $subject, $msg);
	}

	public function paypal_request($sale_id) {
		$this->load->model('sales_model');
		$sale = $this->sales_model->get($sale_id);

		if($sale) {
			$email = urlencode('ventas@bioleafy.com');
			$desc = urlencode('Solicitud_de_Pago_Moringa_Michoacana');
			$total = (float) $sale['delivery']['cost'] + $sale['payment']['total'];
			$return = urlencode('moringa-michoacana.com.mx');
			$paypal_url = 'https://secure.paypal.com/xclick/business='. $email .'&item_name='. $desc .'&amount='. $total .'&page_style=bioleafy&return='. $return .'&currency_code=MXN';

			$data = array(
				'id' => $sale['id'],
				'name' => $sale['name'],
				'courier' => $sale['delivery']['courier'],
				'package' => $sale['package'],
				'subtotal' => $sale['payment']['total'],
				'shipment_cost' => $sale['delivery']['cost'],
				'total' => $total,
				'paypal_url' => $paypal_url
			);

			$subject = 'Solicitud de Pago Paypal';
			$msg = $this->load->view('mails/customer/paypal_request', $data, true);

			//die($msg);
			if($this->sale_has_email($sale)) {
				$this->mailgun($sale['email'], $subject, $msg);
			}
		}
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

		$subject = 'Confirmación de Pago';
		$msg = $this->load->view('mails/customer/sale_details', $data, true);

		if($this->sale_has_email($sale)) {
			$this->mailgun($sale['email'], $subject, $msg);
		}

		if($this->sale_sms_notifications($sale)) {
			$this->plivo($sale['phone'], SMS_PAYMENT_TEXT);
		}
		// $this->send_to_admin($subject, $msg);
	}

	public function notify_created($sale_id) {
		$this->load->model('sales_model');
		$sale = $this->sales_model->get($sale_id);

		if($this->sale_sms_notifications($sale)) {
			$this->plivo($sale['phone'], SMS_NEWORDER_TEXT);
		}
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
			$this->mailgun($sale['email'], $subject, $msg);
			// $this->send_to_admin($subject, $msg);
		}

		if($this->sale_sms_notifications($sale)) {
			$msg = SMS_SHIPPED_TEXT;
			$msg = str_replace('*', $data['track_code'], $msg);
			$this->plivo($sale['phone'], $msg);
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
			//'is_mercado_libre' => $sale['wc_id'] ? false : true
			'is_mercado_libre' => false
		);

		$subject = '¡Fue un placer atenderte!';
		$msg = $this->load->view('mails/customer/ended', $data, true);

		if($this->sale_has_email($sale)) {
			$this->mailgun($sale['email'], $subject, $msg);
		}
		// $this->send_to_admin($subject, $msg);
	}

	private function sale_has_email($sale) {
		return isset($sale['email']) && $sale['email'] && $sale['email'] != '';
	}

	private function sale_sms_notifications($sale) {
		return $sale['sms_notifications'] && isset($sale['phone']);
	}

	public function sms($number, $msg) {
		$this->plivo('521' + $number, $msg);
	}

}

/* End of file Mail_model.php */
/* Location: ./application/models/Mail_model.php */