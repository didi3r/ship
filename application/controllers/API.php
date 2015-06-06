<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        if (!$this->authentication->is_loggedin()) {
            redirect('login/?url=' . "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        }

		$this->load->model('sales_model');
	}

    public function sales_resume()
    {
        $output = array();
        $output['total_ended'] = $this->sales_model->get_total_sales('Finalizado');
        $output['total_cancelled'] = $this->sales_model->get_total_sales('Cancelado');
        $output['sales_this_week'] = $this->sales_model->get_sales_this_week(null, true);
        $output['sales_last_week'] = $this->sales_model->get_sales_last_week(null, true);
        $output['total_sales_this_week'] = $this->sales_model->get_sales_this_week();
        $output['total_pending_shipments'] = $this->sales_model->get_total_sales('Enviando');

        $output['most_active_buyers'] = $this->sales_model->get_most_active_buyers();

        echo json_encode($output);
    }

	public function sales($page, $limit)
	{
		if($page <= 0) $page = 1;
		$offset = ($page - 1) * $limit;

		$output = $this->sales_model->get_all($limit, $offset);

		echo json_encode($output);
	}

    public function history($startDate, $endDate)
	{
		$output = $this->sales_model->get_all(0, 0, 'date', true, $startDate, $endDate, 'Finalizado');

		echo json_encode($output);
	}

    public function expenses($startDate, $endDate)
	{
        $this->load->model('expenses_model');

		$output = $this->expenses_model->get_all($startDate, $endDate);

		echo json_encode($output);
	}


    public function inversions()
	{
        $this->load->model('inversions_model');

		$output = $this->inversions_model->get_all();

		echo json_encode($output);
	}

	public function transfers()
	{
        $this->load->model('transfers_model');

		$output = $this->transfers_model->get_all();

        $transfers_totals = $this->transfers_model->get_raw_material_total();
        $output['total_raw_material'] = $transfers_totals['total'];
        $output['payed_raw_material'] = $transfers_totals['payed'];
        $output['transfered_raw_material'] = $transfers_totals['transfered'];
        $output['pending_raw_material'] = $transfers_totals['pending'];

        $transfers_totals = $this->transfers_model->get_splittings_total();
        $output['total_splittings'] = $transfers_totals['total'];
        $output['expenses_splittings'] = $transfers_totals['expenses'];
        $output['transfered_splittings'] = $transfers_totals['transfered'];
        $output['pending_splittings'] = $transfers_totals['pending'];

		echo json_encode($output);
	}

    public function sale($id = null)
	{
        $post = file_get_contents("php://input");
		$params = json_decode($post);

		// Create request
        if(!$id && $params) {
            $sale = (array) $params;
            $sale['delivery'] = (array) $sale['delivery'];
            $sale['payment'] = (array) $sale['payment'];
            $output = $this->sales_model->create($sale);
        } elseif($id && $params) {
			// Update request
            $sale = (array) $params;
            $sale['delivery'] = (array) $sale['delivery'];
            $sale['payment'] = (array) $sale['payment'];
            $output = $this->sales_model->update($sale);
        } else{
        	// Get request
        	$output = $this->sales_model->get($id);
        }


		echo json_encode($output);
	}

    public function expense($id = null)
	{
        $this->load->model('expenses_model');

        $post = file_get_contents("php://input");
		$params = json_decode($post);

		// Create request
        if(!$id && $params) {
            $expense = (array) $params;
            $output = $this->expenses_model->create($expense);
        } elseif($id && $params) {
			// Update request
            $expense = (array) $params;
            $output = $this->expenses_model->update($expense);
        } else{
        	// Get request
        	$output = $this->expenses_model->get($id);
        }

		echo json_encode($output);
	}

    public function inversion($id = null)
	{
        $this->load->model('inversions_model');

        $post = file_get_contents("php://input");
		$params = json_decode($post);

		// Create request
        if(!$id && $params) {
            $inversion = (array) $params;
            $output = $this->inversions_model->create($inversion);
        } elseif($id && $params) {
			// Update request
            $inversion = (array) $params;
            $output = $this->inversions_model->update($inversion);
        } else{
        	// Get request
        	$output = $this->inversions_model->get($id);
        }

		echo json_encode($output);
	}

	public function transfer($id = null)
	{
        $this->load->model('transfers_model');

        $post = file_get_contents("php://input");
		$params = json_decode($post);

		// Create request
        if(!$id && $params) {
            $transfer = (array) $params;
            $output = $this->transfers_model->create($transfer);
        } elseif($id && $params) {
			// Update request
            $transfer = (array) $params;
            $output = $this->transfers_model->update($transfer);
        } else{
        	// Get request
        	$output = $this->transfers_model->get($id);
        }

		echo json_encode($output);
	}

	public function shipments($page, $limit)
	{
		if($page <= 0) $page = 1;
		$offset = ($page - 1) * $limit;

		$output = $this->sales_model->get_all($limit, $offset, 'date', true, null, null, 'Enviando,En Camino');

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