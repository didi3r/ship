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

        $output['most_active_buyers'] = $this->sales_model->get_most_active_customers();

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
		$output = $this->sales_model->get_all(0, 0, 'date', true, $startDate, $endDate, 'Finalizado,En Camino');
		unset($output['total_rows']);

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

		if(!isset($params->id)) {
			die(json_encode(array('error' => 'Undefined variable: id')));
		}

		$output = $this->sales_model->update_status($params->id, 'Pagado');

		echo json_encode($output);
	}

	public function mark_as_unpaid()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if(!isset($params->id)) {
			die(json_encode(array('error' => 'Undefined variable: id')));
		}

		$output = $this->sales_model->update_status($params->id, 'Pendiente');

		echo json_encode($output);
	}

	public function request_shipment()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if(!isset($params->id)) {
			die(json_encode(array('error' => 'Undefined variable: id')));
		}

		$output = $this->sales_model->update_status($params->id, 'Enviando', array('delivery_comments' => $params->comments));

		echo json_encode($output);
	}

	public function cancel_shipment()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if(!isset($params->id)) {
			die(json_encode(array('error' => 'Undefined variable: id')));
		}

		$output = $this->sales_model->update_status($params->id, 'Pagado');

		echo json_encode($output);
	}

	public function mark_as_shipped()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if(!isset($params->id)) {
			die(json_encode(array('error' => 'Undefined variable: id')));
		}

		$output = $this->sales_model->update_status($params->id, 'En Camino', array('delivery_code' => $params->code, 'date' => $params->date));

		echo json_encode($output);
	}

	public function mark_as_unshipped()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if(!isset($params->id)) {
			die(json_encode(array('error' => 'Undefined variable: id')));
		}

		$output = $this->sales_model->update_status($params->id, 'Enviando');

		echo json_encode($output);
	}

	public function mark_as_finished()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if(!isset($params->id)) {
			die(json_encode(array('error' => 'Undefined variable: id')));
		}

		$output = $this->sales_model->update_status($params->id, 'Finalizado');

		echo json_encode($output);
	}

	public function mark_as_cancelled()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if(!isset($params->id)) {
			die(json_encode(array('error' => 'Undefined variable: id')));
		}

		$output = $this->sales_model->update_status($params->id, 'Cancelado');

		echo json_encode($output);
	}

	public function customer_search()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if($params && isset($params->search)) {
			$output = $this->sales_model->search_for_customer($params->search);
		} else {
			$output = $this->sales_model->search_for_customer();
		}

		echo json_encode($output);
	}

	public function estafeta_status($code)
	{
		require 'simple_html_dom.php';

		$url = 'http://rastreo3.estafeta.com/RastreoWebInternet/consultaEnvio.do';
		$params = array(
            'idioma' => 'es',
            'dispatch' => 'doRastreoInternet',
            'tipoGuia' => 'REFERENCE',
            'guias' => $code
        );

		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, TRUE);

        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

        $reponse = curl_exec($curl);
        $header = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $html = substr($reponse, $header);
        curl_close($curl);

        $parser = str_get_html($html);

        $imgs = array();
		foreach ($parser->find('img') as $element) {
			$imgs[] = $element->src;
		}

		if(array_search('images/noInformacion.png', $imgs)) {
			die('No hay información disponible');
		}

		if(array_search('images/pendiente.png', $imgs)) {
			die('Pendiente en Tránsito');
		}

		if(array_search('images/palomita.png', $imgs)) {
			die('Entregado');
		}

		die('Error al consultar status');
	}

	public function upload_sale_file()
	{
		if(isset($_POST['id']) && $_FILES['file']) {
			$this->load->model('files_model');

			$output = $this->files_model->save_file($_POST['id'], $_FILES['file']);

			echo json_encode($output);
		}
	}

	public function get_sale_files($sale_id)
	{
		$this->load->model('files_model');

		$output = $this->files_model->get_files($sale_id);

		echo json_encode($output);
	}

	public function delete_sale_file()
	{
		$this->load->model('files_model');

		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if($params && isset($params->sale_id) && isset($params->file_id)) {
			$output = $this->files_model->delete_file($params->sale_id, $params->file_id);

			echo $output ? json_encode($output) : '';
		}
	}

}

/* End of file API.php */
/* Location: ./application/controllers/API.php */