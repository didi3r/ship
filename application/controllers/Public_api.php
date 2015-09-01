<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Public_api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function webhook()
	{
		$post = file_get_contents("php://input");
		$post = str_replace('\\', '', $post);

		if($post) {
	    	header('Content-Type: application/json');

			$post = html_entity_decode(preg_replace("/u([0-9A-F]{4})/i", "&#x\\1;", $post), ENT_NOQUOTES, 'UTF-8');
			$params = (array) json_decode($post);

			$order = (array) $params['order'];

			if(!$order) {
				die(json_encode(array('error' => 'Invalid JSON structure')));
			}


			$billing_address = (array) $order['billing_address'];

			$order['total_shipping'] = (float) $order['total_shipping'];
			$order['subtotal'] = (float) $order['subtotal'];
			$order['total_discount'] = (float) $order['total_discount'];

			$payment_details = (array) $order['payment_details'];
			$commission = 0;
			if($payment_details['method_id'] == 'paypal') {
				$commission = (float) ((($order['subtotal'] - $order['total_discount']) * 0.0395) + 4) * 1.16;
			}

			$package = array();
			$raw_material = 0;
			foreach ($order['line_items'] as $product) {
				$product = (array) $product;
				$package[] = $product['quantity'] . ' ' . $product['name'];
				if(isset($product['meta'])) {
					$meta = (array) $product['meta'];
					foreach ($meta as $item) {
						$item = (array) $item;
						if($item['label'] == 'raw_material_cost') {
							$raw_material += (float) $item['value'];
						}
					}
				}
			}

			$shipping_address = (array) $order['shipping_address'];
			$address = $shipping_address['address_1'] . "\r\n";
			$address .= $shipping_address['address_2'] . "\r\n";
			$address .= $shipping_address['city'] . ', ' . $shipping_address['state'] . "\r\n";
			$address .= 'C.P. ' . $shipping_address['postcode'] . "\r\n";

			$courier = 'Estafeta';
			if(preg_match("|\bestafeta\b|i", $order['shipping_methods'])) {
				$courier = 'Estafeta';
			} elseif(preg_match("|\bcorreos\b|i", $order['shipping_methods'])) {
				$courier = 'Correos de México';
			}

			date_default_timezone_set('America/Mexico_City');
			$data = array(
		        'date' => date('Y-m-d', strtotime($order['created_at'])),
		        'name' => $billing_address['first_name'] . ' ' . $billing_address['last_name'],
		        'user' => null,
		        'email' => $billing_address['email'],
		        'phone' => $billing_address['phone'],
		        'package' => $package,
		        'split_earnings' => 0,
		        'from_inversions' => 0,
	    	);

	    	$data['delivery']['addressee'] = $shipping_address['first_name'] . ' ' . $shipping_address['last_name'];
		    $data['delivery']['phone'] = $billing_address['phone'];
		    $data['delivery']['address'] = $address;
		    $data['delivery']['courier'] = $courier;
		    $data['delivery']['cost'] = $order['total_shipping'];
		    $data['payment']['total'] = $order['subtotal'] - $order['total_discount'];
		    $data['payment']['commission'] = $commission;
		    $data['payment']['rawMaterial'] = $raw_material;

	    	$this->load->model('sales_model');
	    	$this->sales_model->create($data);

        	echo json_encode($data);
		}
	}
}

/* End of file Public_api.php */
/* Location: ./application/controllers/Public_api.php */
