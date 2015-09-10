<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customers_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();

	}


	public function get_customers() {
		$sql = "
            SELECT *, COUNT(*) AS purchases
            FROM sales
            WHERE status <> 'Cancelado'
            GROUP BY address
            ORDER BY purchases DESC
        ";
        $query = $this->db->query($sql);

        return $query->result();
	}
}

/* End of file Customers_model.php */
/* Location: ./application/models/Customers_model.php */