<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Public_api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function webhook()
	{
		$post = file_get_contents("php://input");

		mail('ventas.nd.fm@gmail.com', 'webhook', $post);
	}

}

/* End of file Public_api.php */
/* Location: ./application/controllers/Public_api.php */