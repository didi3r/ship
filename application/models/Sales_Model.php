<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_Model extends CI_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();

	}

	public function get_all($limit = 10, $offset = 0, $order = 'date', $desc = true,
		$status = '', $courier = '', $search = '')
	{
		if($limit > 0) {
			$this->db->limit($limit, $offset);
		}

		if(!empty($status)) {
			$this->db->where('status', $status);
		}

		if(!empty($courier)) {
			$this->db->where('courier', $courier);
		}

		if(!empty($order)) {
			$this->db->order_by($order, $desc ? 'desc' : 'asc');
		}

		$query = $this->db->get('sales');

		$output = array();
		$output['total_rows'] = $this->db->count_all('sales');
		foreach ($query->result() as $row) {
			$output['response'][] = $this->contruct_hierarchy($row);
		}

		return $output;
	}

	public function get($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('sales');

		return array('response' => array($this->contruct_hierarchy($query->row())));
	}

	public function update_status($id, $status, $args = array())
	{
		$this->db->select('status');
		$this->db->from('sales');
		$this->db->where('id', $id);
		$query = $this->db->get();

		$current_status = $query->row()->status;
		// die($current_status);
		switch ($status) {
			case 'Pagado':
				if($current_status == 'Pendiente') {
					$this->db->update(
						'sales',
						array(
							'payment_status' => 'Pagado',
							'status' => 'Pagado'
						),
						'id = ' . $id
					);
					return $this->get($id);
				} else {
					return $this->error('No se puede marcar la venta como Pagado');
				}

				break;

			case 'Pendiente':
				if($current_status != 'Pendiente') {
					$this->db->update(
						'sales',
						array(
							'payment_status' => 'Pendiente',
							'status' => 'Pendiente'
						),
						'id = ' . $id
					);
					return $this->get($id);
				} else {
					return $this->error('No se puede marcar la venta como Pendiente');
				}

				break;

			case 'Enviando':
				if($current_status == 'Pagado') {
					$this->db->update(
						'sales',
						array(
							'shipping_status' => 'Pendiente',
							'shipping_comments' => $args['delivery_comments'],
							'status' => 'Enviando'
						),
						'id = ' . $id
					);
					return $this->get($id);
				} else {
					return $this->error('No se puede marcar la venta como Enviando');
				}

				break;

			case 'En Camino':
				if($current_status == 'Enviando' || $current_status == 'Pagado') {
					$this->db->update(
						'sales',
						array(
							'shipping_status' => 'Enviado',
							'status' => 'En Camino'
						),
						'id = ' . $id
					);
					return $this->get($id);
				} else {
					return $this->error('No se puede marcar la venta como En Camino');
				}

				break;

			case 'Finalizado':
				if($current_status == 'En Camino') {
					$this->db->update(
						'sales',
						array(
							'status' => 'Finalizado'
						),
						'id = ' . $id
					);
					return $this->get($id);
				} else {
					return $this->error('No se puede marcar la venta como Finalizado');
				}

				break;

			case 'Cancelado':
				if($current_status != 'Cancelado') {
					$this->db->update(
						'sales',
						array(
							'status' => 'Cancelado'
						),
						'id = ' . $id
					);
					return $this->get($id);
				} else {
					return $this->error('No se puede marcar la venta como Cancelado');
				}

				break;

			default:
				return $this->error('Invalid status:' . $status);
				break;
		}
	}

	private function contruct_hierarchy($obj)
	{
		$array = (array) $obj;

		$array['package'] = preg_split('/\s*,\s*/', trim($array['package'] ));

		$array['delivery'] = array(
			'addressee' => $array['addressee'],
			'address' => trim($array['address']),
			'phone' => $array['phone'],
			'courier' => $array['courier'],
			'cost' => $array['shipping_cost'],
			'date' => $array['shipping_date'],
			'trackCode' => $array['track_code'],
			'status' => $array['shipping_status'],
			'comments' => $array['shipping_comments']
		);

		unset($array['addressee']);
		unset($array['address']);
		unset($array['phone']);
		unset($array['courier']);
		unset($array['shipping_cost']);
		unset($array['shipping_date']);
		unset($array['track_code']);
		unset($array['shipping_status']);
		unset($array['shipping_comments']);

		$array['payment'] = array(
			'commission' => $array['commission'],
			'rawMaterial' => $array['raw_material'],
			'total' => $array['total'],
			'date' => $array['payment_date'],
			'status' => $array['payment_status']
		);

		unset($array['commission']);
		unset($array['raw_material']);
		unset($array['total']);
		unset($array['payment_date']);
		unset($array['payment_status']);

		return $array;
	}

	private function error($msg)
	{
		return array('error' => $msg);
	}

}

/* End of file SalesModel.php */
/* Location: ./application/models/SalesModel.php */