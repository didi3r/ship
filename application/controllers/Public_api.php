<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Public_api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function webhook()
	{
		$webhookContent = "";

		$webhook = fopen('php://input' , 'rb');
		while (!feof($webhook)) {
		    $webhookContent .= fread($webhook, 4096);
		}
		fclose($webhook);
		mail('ventas.nd.fm@gmail.com', 'webhook', $webhookContent);
	}

}

/* End of file Public_api.php */
/* Location: ./application/controllers/Public_api.php */