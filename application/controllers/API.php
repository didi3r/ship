<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class API extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function sales()
	{
		$output = array(
			'items' => array(
				array(
					"id" => 1,
		            "date" => "27/May/2015",
		            "name" => "Jhon Doe",
		            "user" => "MLJD67",
		            "email" => "jhon.doe@gmail.com",
		            "package" => ["500gr Hoja Seca", "1kg Polvo"],
		            "status" => "Finalizado",
		            "delivery" => array(
		            	"addressee" => "Jhon Doe",
		                "address" => "Azul Marino #124 \r\nColores \r\nMonterrey, Nuevo León \r\nC.P. 58000",
		                "phone" => "443 312 4578",
		                "courier" => "Estafeta",
		                "trackCode" => "1234560007",
		                "cost" => 100,
		                "status" => "Enviado"
		            ),
		            "payment" => array(
		            	"commission" => 15,
		                "rawMaterial" => 400,
		                "total" => 950,
		                "status" => "Pagado"
		            )
		        ),
			),
		);

		echo json_encode($output);
	}

}

/* End of file API.php */
/* Location: ./application/controllers/API.php */