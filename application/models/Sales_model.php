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

        if(!empty($order)) {
        	$order_array = explode(',', $order);
        	foreach ($order_array as $order) {
            	$this->db->order_by($order, $desc ? 'desc' : 'asc');
        	}
            $this->db->order_by('id', 'desc');
        }

        if($startDate && $endDate) {
            $this->db->where('date >=', $startDate);
            $this->db->where('date <=', $endDate);
        }

		if(!empty($status)) {
			$status_array = explode(',', $status);
			$where = "(";
			foreach ($status_array as $i => $term) {
				if($i == 0)
					$where .= "status = '" . $term . "'";
				else
					$where .= " || status = '" . $term . "'";
			}
			$where .= ")";
			$this->db->where($where);
		}

		if(!empty($courier)) {
			$this->db->where('courier', $courier);
		}

		if(!empty($search)) {
			$search = trim($search);
			if(mb_substr($search, 0, 1, 'utf-8') == '#') {
				$where = 'id = ' . mb_substr($search, 1, strlen($search), 'utf-8');
				$where .= ' || wc_id = ' . mb_substr($search, 1, strlen($search), 'utf-8');
			} else {
				$where = 'name LIKE \'%' . $search . '%\'';
				$where .= ' || user LIKE \'%' . $search . '%\'';
				$where .= ' || email LIKE \'%' . $search . '%\'';
				$where .= ' || track_code LIKE \'%' . $search . '%\'';
				$where .= ' || address LIKE \'%' . $search . '%\'';
				$where .= ' || package LIKE \'%' . $search . '%\'';
				$where .= ' || shipping_comments LIKE \'%' . $search . '%\'';
			}

			$this->db->where($where, null, false);
		}

		$this->db->select('SQL_CALC_FOUND_ROWS *', false);
		$this->db->from('sales');

		$query = $this->db->get();
       	// die($this->db->last_query());
		$output = array();
		$count = $this->db->query('SELECT FOUND_ROWS() AS `total_rows`');
		$output['total_rows'] = $count->row()->total_rows;

        $output['response'] = array();
		foreach ($query->result() as $row) {
			$output['response'][] = $this->construct_hierarchy($row);
		}


		return $output;
	}

	public function get_all_from_inversions()
	{
		$this->db->select("id, date, name, raw_material AS total");
		$this->db->where('from_inversions', true);
		$this->db->where("(status = 'Finalizado' OR status = 'En Camino' OR status = 'Enviando')");
		$query = $this->db->get('sales');

		return $query->result();
	}

	public function get($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('sales');

		return $this->construct_hierarchy($query->row());
	}

	public function create($sale)
    {
    	if(!$sale['smsNotifications']) {
    		$sale['smsNotifications'] = 0;
    	}

    	if(!$sale['delivery']['hasRX']) {
    		$sale['delivery']['hasRX'] = 0;
    	}

    	$data = array(
    		'wc_id' => $sale['wc_id'],
	        'date' => $sale['date'],
	        'name' => $sale['name'],
	        'user' => $sale['user'],
	        'email' => $sale['email'],
	        'phone' => $sale['phone'],
	        'sms_notifications' => $sale['smsNotifications'],
	        'package' => implode(',', $sale['package']),
	        'addressee' => $sale['delivery']['addressee'],
	        'addressee_phone' => $sale['delivery']['phone'],
	        'address' => $sale['delivery']['address'],
	        'has_rx' => $sale['delivery']['hasRX'],
	        'courier' => $sale['delivery']['courier'],
	        'shipping_cost' => $sale['delivery']['cost'],
	        'shipping_method' => $sale['delivery']['method'],
	        'payment_method' => $sale['payment']['method'],
	        'total' => $sale['payment']['total'],
	        'commission' => $sale['payment']['commission'],
	        'raw_material' => $sale['payment']['rawMaterial'],
	        'split_earnings' => $sale['splitEarnings'],
	        'from_inversions' => $sale['fromInversions'],
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
        $this->db->set('has_rx', $sale['delivery']['hasRX']);
        $this->db->set('courier', $sale['delivery']['courier']);
        $this->db->set('shipping_cost', $sale['delivery']['cost']);
        $this->db->set('shipping_method', $sale['delivery']['method']);
        $this->db->set('shipping_comments', $sale['delivery']['comments']);
        $this->db->set('payment_method', $sale['payment']['method']);
        $this->db->set('total', $sale['payment']['total']);
        $this->db->set('commission', $sale['payment']['commission']);
        $this->db->set('raw_material', $sale['payment']['rawMaterial']);
        $this->db->set('split_earnings', $sale['splitEarnings']);
        $this->db->set('from_inversions', $sale['fromInversions']);
        $this->db->set('sms_notifications', $sale['smsNotifications']);
        $this->db->where('id', $sale['id']);

        if($this->db->update('sales')) {
            return $this->get($sale['id']);
        }
    }

    public function delete($id) {
    	$this->db->where('id', $id);
		$this->db->delete('sales');
    }

    public function update_payment_method($id, $method)
	{
		switch ($method) {
			case 'Tarjeta':
			case 'Deposito':
				$this->db->where('id', $id);
				$this->db->update('sales', array('payment_method' => $method));
				break;

			default:

				break;
		}
	}

	public function get_status($id) {
		$this->db->select('status');
		$this->db->from('sales');
		$this->db->where('id', $id);
		$query = $this->db->get();

		return $query->row() ? $query->row()->status : false;
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
							'payment_date' => $args['date'],
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
							'shipping_date' => $args['date'],
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

	private function construct_hierarchy($obj)
	{
		$array = (array) $obj;

		$array['name'] = ucwords(strtolower($array['name']));
		$array['addressee'] = ucwords(strtolower($array['addressee']));
		$array['address'] = trim(ucwords(strtolower($array['address'])));
		$array['address'] = preg_replace('/C(\.)?P(\.)?(:)?/i', 'C.P.', $array['address']);
		$array['package'] = preg_split('/\s*,\s*/', trim($array['package'] ));
        $array['split_earnings'] = (boolean) $array['split_earnings'];
        $array['from_inversions'] = (boolean) $array['from_inversions'];
        $array['smsNotifications'] = (boolean) $array['sms_notifications'];

		$array['delivery'] = array(
			'addressee' => ucwords(strtolower($array['addressee'])),
			'address' => $array['address'],
			'phone' => $array['addressee_phone'],
			'courier' => $array['courier'],
			'cost' => $array['shipping_cost'],
			'method' => $array['shipping_method'],
			'date' => $array['shipping_date'],
			'trackCode' => strtoupper($array['track_code']),
			'status' => $array['shipping_status'],
			'comments' => $array['shipping_comments'],
			'hasRX' => (boolean) $array['has_rx']
		);

		unset($array['addressee']);
		unset($array['address']);
		unset($array['addressee_phone']);
		unset($array['courier']);
		unset($array['shipping_cost']);
		unset($array['shipping_method']);
		unset($array['shipping_date']);
		unset($array['track_code']);
		unset($array['shipping_status']);
		unset($array['shipping_comments']);
		unset($array['has_rx']);

		$array['payment'] = array(
			'commission' => $array['commission'],
			'rawMaterial' => $array['raw_material'],
			'total' => $array['total'],
			'method' => $array['payment_method'],
			'date' => $array['payment_date'],
			'status' => $array['payment_status']
		);

		unset($array['commission']);
		unset($array['raw_material']);
		unset($array['total']);
		unset($array['payment_method']);
		unset($array['payment_date']);
		unset($array['payment_status']);

		if($array['has_files']) {
			$this->load->model('files_model');
			$array['files'] = $this->files_model->get_files($array['id']);
		}

		return $array;
	}

	private function error($msg)
	{
		return array('error' => $msg);
	}

    public function get_sales_this_week($status = null, $per_day = false)
    {
    	date_default_timezone_set('America/Mexico_City');
    	if(date('w', time()) === '5') {
    		$start = date('Y-m-d');
    	} else {
	        $today = strtotime(date('Y-m-d'));
	        $start = date('Y-m-d', strtotime('last friday', $today));
    	}
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
                    $status_array = explode(',', $status);
					$where = "(";
					foreach ($status_array as $i => $term) {
						if($i == 0)
							$where .= "status = '" . $term . "'";
						else
							$where .= " || status = '" . $term . "'";
					}
					$where .= ")";
					$this->db->where($where);
                }
                $output['dates'][] = $day;
                $output['sales'][] = $this->db->count_all_results('sales');
            }
        } else {
            $this->db->from('sales');
            $this->db->where('date >=', $start);
            $this->db->where('date <=', $end);
            if($status) {
                $status_array = explode(',', $status);
				$where = "(";
				foreach ($status_array as $i => $term) {
					if($i == 0)
						$where .= "status = '" . $term . "'";
					else
						$where .= " || status = '" . $term . "'";
				}
				$where .= ")";
				$this->db->where($where);
            }
            $output = $this->db->count_all_results();
        }

        return $output;
    }

    public function get_sales_last_week($status = null, $per_day = false)
    {
    	date_default_timezone_set('America/Mexico_City');
    	if(date('w', time()) === '5') {
	        $today = strtotime(date('Y-m-d'));
	        $start = date('Y-m-d', strtotime('last friday', $today));
        	$end = date('Y-m-d', strtotime('next thursday', strtotime($start)));
    	} else {
	        $sunday = strtotime('-2 weeks sunday', strtotime(date('Y-m-d')));
	        $start = date('Y-m-d', strtotime('last friday', $sunday));
	        $end = date('Y-m-d', strtotime('next thursday', strtotime($start)));
    	}

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
                    $status_array = explode(',', $status);
					$where = "(";
					foreach ($status_array as $i => $term) {
						if($i == 0)
							$where .= "status = '" . $term . "'";
						else
							$where .= " || status = '" . $term . "'";
					}
					$where .= ")";
					$this->db->where($where);
                }
                $output['dates'][] = $day;
                $output['sales'][] = (int) $this->db->count_all_results('sales');
            }

        } else {
            $this->db->where('date <=', $start);
            $this->db->where('date >=', $end);
            if($status) {
                $status_array = explode(',', $status);
				$where = "(";
				foreach ($status_array as $i => $term) {
					if($i == 0)
						$where .= "status = '" . $term . "'";
					else
						$where .= " || status = '" . $term . "'";
				}
				$where .= ")";
				$this->db->where($where);
            }
            $output = (int) $this->db->count_all_results('sales');
        }

        return $output;
    }

    public function get_sales_this_month($status = null, $per_day = false)
    {
    	date_default_timezone_set('America/Mexico_City');

    	$start = date('Y-m-01');
        $end = date('Y-m-t');

        if($per_day) {
        	$en_strings =array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');
        	$es_strings =array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes');

            $dates = array();
            while($start <= $end) {
                $dates[] = $start;
                $start = date('Y-m-d', strtotime($start . ' + 1 days'));;
            }

            $output = array();
            foreach($dates as $day) {
                $this->db->where('date', $day);
                if($status) {
                    $status_array = explode(',', $status);
					$where = "(";
					foreach ($status_array as $i => $term) {
						if($i == 0)
							$where .= "status = '" . $term . "'";
						else
							$where .= " || status = '" . $term . "'";
					}
					$where .= ")";
					$this->db->where($where);
                }

                $date_string = date('l d', strtotime($day));

                $output['dates'][] = str_ireplace($en_strings, $es_strings, $date_string);
                $output['sales'][] = $this->db->count_all_results('sales');
            }

        } else {
            $this->db->from('sales');
            $this->db->where('date >=', $start);
            $this->db->where('date <=', $end);
            if($status) {
                $status_array = explode(',', $status);
				$where = "(";
				foreach ($status_array as $i => $term) {
					if($i == 0)
						$where .= "status = '" . $term . "'";
					else
						$where .= " || status = '" . $term . "'";
				}
				$where .= ")";
				$this->db->where($where);
            }
            $output = $this->db->count_all_results();
        }

        return $output;
    }

    public function get_sales_this_year($status = null, $per_month = false)
    {
    	date_default_timezone_set('America/Mexico_City');

        if($per_month) {
            $en_strings = array('January', 'February' , 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
            $es_strings = array('Enero', 'Febrero' , 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

            $output = array();
            for($i = 1; $i <= 12; $i++) {
                if($status) {
                    $status_array = explode(',', $status);
					$where = "(";
					foreach ($status_array as $j => $term) {
						if($j == 0)
							$where .= "status = '" . $term . "'";
						else
							$where .= " || status = '" . $term . "'";
					}
					$where .= ")";
                }

                $sql = "
                	SELECT COUNT(*) AS total
                	FROM sales
                	WHERE YEAR(date) = '" . date('Y') . "'
                	AND MONTH(date) = $i
                ";
                $sql = $status ? $sql . ' AND ' . $where : $sql;

                $query = $this->db->query($sql);

                $month_string = date('F', strtotime(date('Y') . '-' . $i . '-01'));

                $output['months'][] = str_ireplace($en_strings, $es_strings, $month_string);
                $output['sales'][] = (int) $query->row()->total;
            }

        } else {
            $this->db->from('sales');
            $this->db->where('YEAR(date)', date('Y'));
            if($status) {
                $status_array = explode(',', $status);
				$where = "(";
				foreach ($status_array as $i => $term) {
					if($i == 0)
						$where .= "status = '" . $term . "'";
					else
						$where .= " || status = '" . $term . "'";
				}
				$where .= ")";
				$this->db->where($where);
            }
            $output = $this->db->count_all_results();
        }

        return $output;
    }

    public function get_most_active_customers($limit = 5)
    {
        $sql = "
            SELECT name, COUNT(*) AS purchases
            FROM sales
            WHERE status = 'Finalizado'
            GROUP BY UPPER(name)
            ORDER BY purchases DESC
            LIMIT 0, " . $limit . "
        ";
        $query = $this->db->query($sql);

        return $query->result();
    }

    public function search_for_customer($search = null)
    {
    	$this->db->select('name, user, email, phone, address, addressee, addressee_phone');
        if($search) {
            $this->db->like('name', $search);
            $this->db->or_like('user', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('address', $search);
            $this->db->or_like('addressee', $search);
        } else {
        	$this->db->limit(5);
        }
        $this->db->group_by('UPPER(address)');
        $this->db->order_by('count(*)', 'desc');
        $query = $this->db->get('sales');

        $output = $query->result();
        foreach ($output as &$row) {
        	$row->address = trim($row->address);
        }

        return $output;
    }

    public function get_earnings_details($startDate, $endDate)
    {
    	$this->db->where("(status = 'Enviando' || status = 'En Camino' || status = 'Finalizado')");
    	$this->db->where('date >=', $startDate);
        $this->db->where('date <=', $endDate);
        $query = $this->db->get('sales');

        $sales = array();
        foreach ($query->result() as $row) {
        	$subtotal = $row->total - $row->commission;

        	if($row->courier == 'Correos de México') {
        		$subtotal += $row->shipping_cost;
        	}

        	if(!$row->from_inversions) {
        		$subtotal -= $row->raw_material;
        	}

        	if($row->split_earnings) {
        		$subtotal = round($subtotal * 0.70, 2);
        	}

        	$sales[] = array(
	        	'id' => $row->id,
	        	'date' => $row->date,
	        	'type' => 'Venta',
	        	'description' => 'Compra de: ' . $row->name,
	        	'subtotal' => $subtotal
        	);
        }

        $this->db->where('date >=', $startDate);
        $this->db->where('date <=', $endDate);
        $query = $this->db->get('expenses');

        $expenses = array();
        foreach ($query->result() as $row) {
        	$subtotal = $row->total * (-1);
        	$expenses[] = array(
	        	'id' => $row->id,
	        	'date' => $row->date,
	        	'type' => 'Gasto',
	        	'description' => 'Gasto de: ' . $row->description,
	        	'subtotal' => $subtotal
        	);
        }

        $output['response'] = array_merge($sales, $expenses);
        function sort_by_date($a, $b) {
		    return strtotime($a["date"]) - strtotime($b["date"]);
		}
		usort($output['response'], "sort_by_date");
		$output['response'] = array_reverse($output['response']);

        $total = 0;
        foreach ($output['response'] as &$row) {
        	$total += $row['subtotal'];
        	$row['total'] = (float) $total;
        }

        $output['total'] = (float) $total;
        $output['total_rows'] = count($output['response']);


        return $output;
    }

    public function check_wc_exists($wc_id) {
    	$this->db->where('wc_id', $wc_id);
    	$query = $this->db->get('sales');

    	if ($query->num_rows() > 0) {
		   return $query->row()->id;
		}

		return null;
    }

    public function get_wc_id($id) {
    	$this->db->where('id', $id);
    	$query = $this->db->get('sales');

    	if ($query->num_rows() > 0) {
		   return $query->row()->wc_id;
		}

		return null;
    }

}

/* End of file SalesModel.php */
/* Location: ./application/models/SalesModel.php */