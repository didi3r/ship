<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();

	}

    public function get_total_sales($status = null)
    {
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->from('sales');

        return $this->db->count_all_results();
    }

	public function get_all($limit = 10, $offset = 0, $order = 'date', $desc = true,
        $startDate = null, $endDate = null, $status = '', $courier = '', $search = '')
	{
		if($limit > 0) {
			$this->db->limit($limit, $offset);
		}

        if($order) {
            $this->db->order_by($order, $desc ? 'desc' : 'asc');
            $this->db->order_by('id', 'desc');
        }

        if($startDate && $endDate) {
            $this->db->where('date >=', $startDate);
            $this->db->where('date <=', $endDate);
        }

		if(!empty($status)) {
			$status_array = explode(',', $status);
			foreach ($status_array as $i => $term) {
				if($i == 0)
					$this->db->where('status', $term);
				else
					$this->db->or_where('status', $term);
			}
		}

		if(!empty($courier)) {
			$this->db->where('courier', $courier);
		}

		$query = $this->db->get('sales');
//        die($this->db->last_query());

		$output = array();
        $output['response'] = array();
		foreach ($query->result() as $row) {
			$output['response'][] = $this->contruct_hierarchy($row);
		}

		if(empty($status) && empty($courier) && empty($search) && !$startDate && !$endDate) {
			$output['total_rows'] = $this->db->count_all('sales');
		} else {
			if(!empty($status)) {
				foreach ($status_array as $i => $term) {
					if($i == 0)
						$this->db->where('status', $term);
					else
						$this->db->or_where('status', $term);
				}
			}

			if(!empty($courier)) {
				$this->db->where('courier', $courier);
			}

            if($startDate && $endDate) {
                $this->db->where('date >=', $startDate);
                $this->db->where('date <=', $endDate);
            }

			$this->db->from('sales');
			$output['total_rows'] = $this->db->count_all_results();
		}


		return $output;
	}

	public function get($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('sales');

		return $this->contruct_hierarchy($query->row());
	}

	public function create($sale)
    {
    	$data = array(
	        'date' => $sale['date'],
	        'name' => $sale['name'],
	        'user' => $sale['user'],
	        'email' => $sale['email'],
	        'phone' => $sale['phone'],
	        'package' => implode(',', $sale['package']),
	        'addressee' => $sale['delivery']['addressee'],
	        'addressee_phone' => $sale['delivery']['phone'],
	        'address' => $sale['delivery']['address'],
	        'courier' => $sale['delivery']['courier'],
	        'shipping_cost' => $sale['delivery']['cost'],
	        'total' => $sale['payment']['total'],
	        'commission' => $sale['payment']['commission'],
	        'raw_material' => $sale['payment']['rawMaterial'],
	        'split_earnings' => $sale['split_earnings'],
    	);

        if($this->db->insert('sales', $data)) {
            return $this->get($this->db->insert_id());
        }
    }

    public function update($sale)
    {
        $this->db->set('date', $sale['date']);
        $this->db->set('name', $sale['name']);
        $this->db->set('user', $sale['user']);
        $this->db->set('email', $sale['email']);
        $this->db->set('phone', $sale['phone']);
        $this->db->set('package', implode(',', $sale['package']));
        $this->db->set('addressee', $sale['delivery']['addressee']);
        $this->db->set('addressee_phone', $sale['delivery']['phone']);
        $this->db->set('address', $sale['delivery']['address']);
        $this->db->set('courier', $sale['delivery']['courier']);
        $this->db->set('shipping_cost', $sale['delivery']['cost']);
        $this->db->set('total', $sale['payment']['total']);
        $this->db->set('commission', $sale['payment']['commission']);
        $this->db->set('raw_material', $sale['payment']['rawMaterial']);
        $this->db->set('split_earnings', $sale['split_earnings']);
        $this->db->where('id', $sale['id']);

        if($this->db->update('sales')) {
            return $this->get($sale['id']);
        }
    }

	public function update_status($id, $status, $args = array())
	{
		$this->db->select('status');
		$this->db->from('sales');
		$this->db->where('id', $id);
		$query = $this->db->get();

		$current_status = $query->row()->status;
		switch ($status) {
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

			case 'Pagado':
				if($current_status == 'Pendiente' || $current_status == 'Enviando') {
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

			case 'Enviando':
				if($current_status == 'Pagado' || $current_status == 'En Camino') {
					if(isset($args['delivery_comments'])) {
						$update = array(
							'shipping_status' => 'Pendiente',
							'shipping_comments' => $args['delivery_comments'],
							'status' => 'Enviando'
						);
					} else {
						$update = array(
							'shipping_status' => 'Pendiente',
							'status' => 'Enviando'
						);
					}
					$this->db->update('sales', $update, 'id = ' . $id);
					return $this->get($id);
				} else {
					return $this->error('No se puede marcar la venta como Enviando');
				}

				break;

			case 'En Camino':
				if($current_status == 'Enviando') {
					if(isset($args['delivery_code'])) {
						$update = array(
							'shipping_status' => 'Enviado',
							'status' => 'En Camino',
							'track_code' => $args['delivery_code']
						);
					} else {
						$update = array(
							'shipping_status' => 'Enviado',
							'status' => 'En Camino'
						);
					}
					$this->db->update('sales', $update, 'id = ' . $id);
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
        $array['split_earnings'] = (boolean) $array['split_earnings'];

		$array['delivery'] = array(
			'addressee' => $array['addressee'],
			'address' => trim($array['address']),
			'phone' => $array['addressee_phone'],
			'courier' => $array['courier'],
			'cost' => $array['shipping_cost'],
			'date' => $array['shipping_date'],
			'trackCode' => $array['track_code'],
			'status' => $array['shipping_status'],
			'comments' => $array['shipping_comments']
		);

		unset($array['addressee']);
		unset($array['address']);
		unset($array['addressee_phone']);
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

    public function get_sales_this_week($status = null, $per_day = false)
    {
    	date_default_timezone_set('America/Mexico_City');
        $today = strtotime(date('Y-m-d'));
        $start = date('Y-m-d', strtotime('last friday', $today));
        $end = date('Y-m-d', strtotime('next thursday', strtotime($start)));

        if($per_day) {
            $dates = array();
            while($start <= $end) {
                $dates[] = $start;
                $start = date('Y-m-d', strtotime($start . ' + 1 days'));;
            }

            $output = array();
            foreach($dates as $day) {
                $this->db->where('date', $day);
                if($status) {
                    $this->db->where('status', $status);
                }
//                $output[] = array(
//                    'date' => $day,
//                    'sales' => (int) $this->db->count_all_results('sales')
//                );
                $output['dates'][] = $day;
                $output['sales'][] = $this->db->count_all_results('sales');
            }

        } else {
            $this->db->from('sales');
            $this->db->where('date >=', $start);
            $this->db->where('date <=', $end);
            if($status) {
                $this->db->where('status', $status);
            }
            $output = $this->db->count_all_results();
        }

        return $output;
    }

    public function get_sales_last_week($status = null, $per_day = false)
    {
    	date_default_timezone_set('America/Mexico_City');
        $sunday = strtotime('-2 weeks sunday', strtotime(date('Y-m-d')));
        $start = date('Y-m-d', strtotime('last friday', $sunday));
        $end = date('Y-m-d', strtotime('next thursday', strtotime($start)));

        if($per_day) {
            $dates = array();
            while($start <= $end) {
                $dates[] = $start;
                $start = date('Y-m-d', strtotime($start . ' + 1 days'));;
            }

            $output = array();
            foreach($dates as $day) {
                $this->db->where('date', $day);
                if($status) {
                    $this->db->where('status', $status);
                }
                $output['dates'][] = $day;
                $output['sales'][] = (int) $this->db->count_all_results('sales');
            }

        } else {
            $this->db->where('date <=', $start);
            $this->db->where('date >=', $end);
            if($status) {
                $this->db->where('status', $status);
            }
            $output = (int) $this->db->count_all_results('sales');
        }

        return $output;
    }

    public function get_most_active_buyers($limit = 5)
    {
        $sql = "
            SELECT name, COUNT(*) AS purchases
            FROM sales
            GROUP BY name
            ORDER BY purchases DESC
            LIMIT 0, " . $limit . "
        ";
        $query = $this->db->query($sql);

        return $query->result();
    }

}

/* End of file SalesModel.php */
/* Location: ./application/models/SalesModel.php */