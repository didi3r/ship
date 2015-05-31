<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_Model extends CI_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();

	}

	public function get_all()
	{
		$query = $this->db->get('sales');

		$sales = array();
		foreach ($query->result() as $row) {
			$sales['items'][] = $this->contruct_hierarchy($row);
		}

		return $sales;
	}

	private function contruct_hierarchy($obj)
	{
		$array = (array) $obj;

		$array['package'] = preg_split('/\s*,\s*/', trim($array['package'] ));

		$array['delivery'] = array(
			'addressee' => $array['addressee'],
			'address' => $array['address'],
			'phone' => $array['phone'],
			'courier' => $array['courier'],
			'cost' => $array['shipping_cost'],
			'date' => $array['shipping_date'],
			'trackCode' => $array['track_code'],
			'status' => $array['shipping_status']
		);

		unset($array['addressee']);
		unset($array['address']);
		unset($array['phone']);
		unset($array['courier']);
		unset($array['shipping_cost']);
		unset($array['shipping_date']);
		unset($array['track_code']);
		unset($array['shipping_status']);

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

}

/* End of file SalesModel.php */
/* Location: ./application/models/SalesModel.php */