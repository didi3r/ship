<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Public_api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function webhook()
	{
		$post = file_get_contents("php://input");

		if($post) {
			$params = json_decode($post);

			$params['total_shipping'] = (float) $params['total_shipping'];
			$params['subtotal'] = (float) $params['subtotal'];
			$params['total_discount'] = (float) $params['total_shipping'];

			$package = array();
			foreach ($params['line_items'] as $product) {
				if(!is_array($product)) $product = (array) $product;
				$package[] = $product['quantity'] . ' ' . $product['name'];
			}

			$shiping_address = $params['shipping_address'];
			if(!is_array($shipping_address)) $shipping_address = (array) $shipping_address;
			$address = $shipping_address['address_1'] . '\n';
			$address .= $shipping_address['address_2'] . '\n';
			$address .= $shipping_address['city'] . ', ' . $shipping_address['state'] . '\n';
			$address .= $shipping_address['postcode'] . '\n';

			$courier = 'Estafeta';
			if(preg_match("|\bestafeta\b|i", $params['shipping_methods'])) {
				$courier = 'Estafeta';
			} elseif(preg_match("|\bcorreos\b|i", $params['shipping_methods'])) {
				$courier = 'Correos de México';
			}

			$data = array(
		        'date' => $params['created_at'],
		        'name' => $params['first_name'] . ' ' . $params['last_name'],
		        'user' => null,
		        'email' => $params['email'],
		        'phone' => $params['phone'],
		        'package' => implode(',', $package),
		        'addressee' => $shipping_address['first_name'] . ' ' . $shipping_address['last_name'],
		        'addressee_phone' => $shipping_address['phone'],
		        'address' => $address,
		        'courier' => $courier,
		        'shipping_cost' => $params['total_shipping'],
		        'total' => $params['subtotal'] - $params['total_discount'],
		        'commission' => 0,
		        'raw_material' => 0,
		        'split_earnings' => true,
		        'from_inversions' => false,
	    	);

	    	$this->load->model('sales_model');
	    	$this->sales_model->create($data);

	    	$data = json_encode($data);
	    	echo $data;
			mail('ventas.nd.fm@gmail.com', 'webhook', $data);
	    	return $data;
		}
	}

}

/* End of file Public_api.php */
/* Location: ./application/controllers/Public_api.php */