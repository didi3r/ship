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
            	GROUP_CONCAT(DISTINCT address SEPARATOR '\n\n') AS address,
            	COUNT(*) AS purchases,
            	SUM(total) AS total
            FROM sales
            WHERE status <> 'Cancelado'
            GROUP BY UPPER(name)
            ORDER BY purchases DESC
        ";
        $query = $this->db->query($sql);
        $output = $query->result();

        foreach ($output as &$row) {
            $row->name = ucwords(strtolower($row->name));
            $row->addressee = ucwords(strtolower($row->addressee));
            $row->address = trim(ucwords(strtolower($row->address)));
            $row->address = preg_replace('/C(\.)?P(\.)?(:)?/i', 'C.P.', $row->address);
        }

        return $output;
	}
}

/* End of file Customers_model.php */
/* Location: ./application/models/Customers_model.php */