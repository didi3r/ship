<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customers_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();

	}


	public function get_customers() {
		$sql = "
            SELECT
            	*,
            	GROUP_CONCAT(address SEPARATOR '\n\n'),
            	COUNT(*) AS purchases,
            	SUM(total) AS total
            FROM sales
            WHERE status <> 'Cancelado'
            GROUP BY name
            ORDER BY purchases DESC
        ";
        $query = $this->db->query($sql);

        return $query->result();
	}
}

/* End of file Customers_model.php */
/* Location: ./application/models/Customers_model.php */