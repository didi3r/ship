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
        $status = 'Pagado,Enviando,En Camino,Finalizado';
        $output['total_ended'] = $this->sales_model->get_total_sales('Finalizado');
        $output['total_cancelled'] = $this->sales_model->get_total_sales('Cancelado');
        $output['sales_this_week'] = $this->sales_model->get_sales_this_week($status, true);
        $output['sales_last_week'] = $this->sales_model->get_sales_last_week($status, true);
        $output['total_sales_this_week'] = $this->sales_model->get_sales_this_week($status);
        $output['sales_this_month'] = $this->sales_model->get_sales_this_month($status, true);
        $output['sales_this_year'] = $this->sales_model->get_sales_this_year($status, true);
        $output['total_pending_shipments'] = $this->sales_model->get_total_sales('Enviando');

        $output['most_active_buyers'] = $this->sales_model->get_most_active_customers(10);

        header('Content-Type: application/json');
        echo json_encode($output);
    }

	public function sales($page, $limit, $order = null, $status = null, $courier = null, $search = null)
	{
		if($page <= 0) $page = 1;
		$offset = ($page - 1) * $limit;

		$status = $status ? urldecode($status) : '';
		$courier = $courier ? urldecode($courier) : '';
		$search = $search ? urldecode($search) : '';

		$output = $this->sales_model->get_all($limit, $offset, 'date', true, null, null, $status, $courier, $search);

		header('Content-Type: application/json');
		echo json_encode($output);
	}

    public function history($startDate, $endDate)
	{
		$output = $this->sales_model->get_all(0, 0, 'date', true, $startDate, $endDate, 'Finalizado,En Camino,Enviando');
		$output['total_rows'] = count($output['response']);

		header('Content-Type: application/json');
		echo json_encode($output);
	}

    public function expenses($startDate, $endDate)
	{
        $this->load->model('expenses_model');

		$output = $this->expenses_model->get_all($startDate, $endDate);

		header('Content-Type: application/json');
		echo json_encode($output);
	}

	public function delete_expense()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if($params && isset($params->expense_id)) {
			$this->load->model('expenses_model');
			$expense = $this->expenses_model->get($params->expense_id);

			if($expense->user == $this->authentication->read('username') ||
				$this->authentication->is_admin()) {
				$this->expenses_model->delete($params->expense_id);
			}
		}
	}

	public function earnings($startDate, $endDate)
	{
		$output = $this->sales_model->get_earnings_details($startDate, $endDate);

		header('Content-Type: application/json');
		echo json_encode($output);
	}


    public function inversions()
	{
        $this->load->model('inversions_model');

		$sales = $this->sales_model->get_all_from_inversions();

		foreach ($sales as &$sale) {
			$sale->total *= -1;
			$sale->description = 'Venta #' . $sale->id . ': ' . $sale->name;
			unset($sale->name);
		}

		$output = $this->inversions_model->get_all();
		$output['response'] = array_merge($output['response'], $sales);

		$total = 0;
		foreach ($output['response'] as $row) {
			$total += $row->total;
		}
		$output['total_inversions'] = $total;
		$output['total_rows'] = count($output['response']);

		header('Content-Type: application/json');
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

		$transfers_totals = $this->transfers_model->get_expenses_total();
        $output['total_expenses'] = $transfers_totals['total'];
        $output['transfered_expenses'] = $transfers_totals['transfered'];
        $output['pending_expenses'] = $transfers_totals['pending'];

        header('Content-Type: application/json');
		echo json_encode($output);
	}

	public function delete_transfer()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if($params && isset($params->transfer_id)) {
			$this->load->model('transfers_model');
			$transfer = $this->transfers_model->get($params->transfer_id);

			if($this->authentication->is_admin()) {
				$this->transfers_model->delete($params->transfer_id);
			}
		}
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

        header('Content-Type: application/json');
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

        header('Content-Type: application/json');
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

        header('Content-Type: application/json');
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

        header('Content-Type: application/json');
		echo json_encode($output);
	}

	public function shipments($page, $limit)
	{
		if($page <= 0) $page = 1;
		$offset = ($page - 1) * $limit;

		$output = $this->sales_model->get_all($limit, $offset, 'status,date', true, null, null, 'Enviando,En Camino');

		header('Content-Type: application/json');
		echo json_encode($output);
	}

	public function trackables($page, $limit)
	{
		if($page <= 0) $page = 1;
		$offset = ($page - 1) * $limit;

		$output = $this->sales_model->get_all($limit, $offset, 'status,date', true, null, null, 'En Camino');

		header('Content-Type: application/json');
		echo json_encode($output);
	}

	public function paypal_request()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if(!isset($params->id)) {
			die(json_encode(array('error' => 'Undefined variable: id')));
		}

		if($this->authentication->is_admin()) {
			$this->load->model('notifications_model');
			$this->notifications_model->paypal_request($params->id);
			$this->sales_model->update_payment_method($params->id, 'Tarjeta');
		}
	}

	public function mark_as_paid()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if(!isset($params->id)) {
			die(json_encode(array('error' => 'Undefined variable: id')));
		}

		$output = $this->sales_model->update_status($params->id, 'Pagado', array('date' => date('Y-m-d')));

		$this->load->model('notifications_model');
		$this->notifications_model->notify_payment($params->id);

		// $wc_id = $this->sales_model->get_wc_id($params->id);
		// if($wc_id) {
		// 	$this->woocommerce_model->mark_as_paid($wc_id);
		// }

		header('Content-Type: application/json');
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

		header('Content-Type: application/json');
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

		header('Content-Type: application/json');
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

		header('Content-Type: application/json');
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

		$this->load->model('notifications_model');
		$this->notifications_model->notify_shipment($params->id);

		// Update order in woocommerce
		$wc_id = $this->sales_model->get_wc_id($params->id);
		if($wc_id) {
			$this->load->model('woocommerce_model');
			$this->woocommerce_model->mark_as_finished($wc_id);
		}

		header('Content-Type: application/json');
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

		header('Content-Type: application/json');
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

		$this->load->model('notifications_model');
		$this->notifications_model->notify_ended($params->id);


		header('Content-Type: application/json');
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

		// Update order in woocommerce
		$wc_id = $this->sales_model->get_wc_id($params->id);
		if($wc_id) {
			$this->load->model('woocommerce_model');
			$this->woocommerce_model->mark_as_cancelledd($wc_id);
		}

		header('Content-Type: application/json');
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

		header('Content-Type: application/json');
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

	public function sepomex_status($code)
	{
		$url = 'http://www.17track.net/r/handlertrack.ashx?num=' . $code;

		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');

        $reponse = curl_exec($curl);
        $json = json_decode($reponse);
        curl_close($curl);

        if($json && isset($json->dat)) {
		    if($json->dat->e == 40) {
		    	die('Entregado');
		    }

		    if($json->dat->e == 10) {
		    	die('Pendiente en Tránsito');
		    }

			if($json->dat->e == 0) {
		    	die('No hay información disponible');
			}
        }

		die('Error al consultar status');

	}

	public function upload_sale_file()
	{
		if(isset($_POST['id']) && $_FILES['file']) {
			$this->load->model('files_model');

			$output = $this->files_model->save_file($_POST['id'], $_FILES['file']);

			header('Content-Type: application/json');
			echo json_encode($output);
		}
	}

	public function delete_sale()
	{
		$post = file_get_contents("php://input");
		$params = json_decode($post);

		if($params && isset($params->id) && $this->authentication->is_admin()) {
			$this->sales_model->delete($params->id);
		}
	}

	public function get_sale_files($sale_id)
	{
		$this->load->model('files_model');

		$output = $this->files_model->get_files($sale_id);

		header('Content-Type: application/json');
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

	public function customers() {
		$this->load->model('customers_model');

		$output = $this->customers_model->get_customers();

		header('Content-Type: application/json');
		echo json_encode($output);
	}

	public function woocommerce($sale_id) {
		$this->load->model('woocommerce_model');

		$wc_id = $this->sales_model->get_wc_id($sale_id);
		if($wc_id) {
			$this->woocommerce_model->mark_as_finished($wc_id);
		}
	}

}

/* End of file API.php */
/* Location: ./application/controllers/API.php */