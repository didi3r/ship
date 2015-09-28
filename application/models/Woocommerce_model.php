<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( APPPATH.'third_party/woocommerce-api.php' );

define('CUSTOMER_KEY', 'ck_368afd4827dc64de72ace4238782efb96c5ca2f3');
define('CUSTOMER_SECRET', 'cs_c7e5155d16b474d1d96a47d450969a724308f37b');

class Woocommerce_model extends CI_Model {
	private $options;
	private $client;

	public function __construct()
	{
		parent::__construct();

		$this->options = array(
		    'ssl_verify'      => false,
		);

		try {

		    $this->client = new WC_API_Client( 'http://moringa-michoacana.com.mx/', CUSTOMER_KEY, CUSTOMER_SECRET, $this->options );

		} catch ( WC_API_Client_Exception $e ) {

		    echo $e->getMessage() . PHP_EOL;
		    echo $e->getCode() . PHP_EOL;

		    if ( $e instanceof WC_API_Client_HTTP_Exception ) {

		        print_r( $e->get_request() );
		        print_r( $e->get_response() );
		    }
		}
	}

	public function update_status($wc_id, $status) {
		$this->client->orders->update_status($wc_id, $status);
	}

	public function mark_as_paid($wc_id) {
		$this->update_status($wc_id, 'processing');
	}

	public function mark_as_finished($wc_id) {
		$this->update_status($wc_id, 'completed');
	}

	public function mark_as_cancelledd($wc_id) {
		$this->update_status($wc_id, 'cancelled');
	}

}

/* End of file Woocommerce_model.php */
/* Location: ./application/models/Woocommerce_model.php */