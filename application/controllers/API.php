<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class API extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('sales_model');
	}

	public function sales($page, $limit)
	{
		if($page <= 0) $page = 1;
		$offset = ($page - 1) * $limit;

		$output = $this->sales_model->get_all($limit, $offset);

		echo json_encode($output);
	}

	public function shipments($page, $limit)
	{
		if($page <= 0) $page = 1;
		$offset = ($page - 1) * $limit;

		$output = $this->sales_model->get_all($limit, $offset, 'date', true, 'Enviando,En Camino');

		echo json_encode($output);
	}

	public function mark_as_paid()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if(!$params->id) {
			die(json_encode(array('error' => 'Undefined variable: id')));
		}

		$output = $this->sales_model->update_status($params->id, 'Pagado');

		echo json_encode($output);
	}

	public function mark_as_unpaid()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if(!$params->id) {
			die(json_encode(array('error' => 'Undefined variable: id')));
		}

		$output = $this->sales_model->update_status($params->id, 'Pendiente');

		echo json_encode($output);
	}

	public function request_shipment()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if(!$params->id) {
			die(json_encode(array('error' => 'Undefined variable: id')));
		}

		$output = $this->sales_model->update_status($params->id, 'Enviando', array('delivery_comments' => $params->comments));

		echo json_encode($output);
	}

	public function cancel_shipment()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if(!$params->id) {
			die(json_encode(array('error' => 'Undefined variable: id')));
		}

		$output = $this->sales_model->update_status($params->id, 'Pagado');

		echo json_encode($output);
	}

	public function mark_as_shipped()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if(!$params->id) {
			die(json_encode(array('error' => 'Undefined variable: id')));
		}

		$output = $this->sales_model->update_status($params->id, 'En Camino', array('delivery_code' => $params->code));

		echo json_encode($output);
	}

	public function mark_as_unshipped()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if(!$params->id) {
			die(json_encode(array('error' => 'Undefined variable: id')));
		}

		$output = $this->sales_model->update_status($params->id, 'Enviando');

		echo json_encode($output);
	}

	public function mark_as_finished()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if(!$params->id) {
			die(json_encode(array('error' => 'Undefined variable: id')));
		}

		$output = $this->sales_model->update_status($params->id, 'Finalizado');

		echo json_encode($output);
	}

	public function mark_as_cancelled()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if(!$params->id) {
			die(json_encode(array('error' => 'Undefined variable: id')));
		}

		$output = $this->sales_model->update_status($params->id, 'Cancelado');

		echo json_encode($output);
	}

}

/* End of file API.php */
/* Location: ./application/controllers/API.php */