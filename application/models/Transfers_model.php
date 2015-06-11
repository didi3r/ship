<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transfers_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();

	}

    public function get_all($startDate = null, $endDate = null, $order = 'date', $desc = true)
    {
        if($order) {
            $this->db->order_by($order, $desc ? 'desc' : 'asc');
            $this->db->order_by('id', 'desc');
        }

        if($startDate && $endDate) {
            $this->db->where('date >=', $startDate);
            $this->db->where('date <=', $endDate);
        }

        $query = $this->db->get('transfers');

        $output = array();
        $output['response'] = $query->result();
        $output['total_rows'] = count($query->result());

        return $output;
    }

    public function get($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('transfers');

		return $query->row();
	}


    public function create($expense)
    {
    	$data = array(
	        'date' => $expense['date'],
	        'account' => $expense['account'],
            'total' => $expense['total']
    	);

        if($this->db->insert('transfers', $data)) {
            return $this->get($this->db->insert_id());
        }
    }

    public function update($expense)
    {
        $this->db->set('date', $expense['date']);
        $this->db->set('account', $expense['account']);
        $this->db->set('total', $expense['total']);

        if($this->db->update('transfers')) {
            return $this->get($expense['id']);
        }
    }

    public function get_raw_material_total()
    {
        $output = array();
        $this->db->select('SUM(raw_material) AS total');
        $this->db->where('status', 'Finalizado');
        $this->db->or_where('status', 'En Camimno');
        $query = $this->db->get('sales');

        $output['total'] = (float) $query->row()->total ? $query->row()->total : 0;

        $this->db->select('SUM(total) AS total');
        $query = $this->db->get('payments');

        $output['payed'] = (float) $query->row()->total ? $query->row()->total : 0;

        $this->db->select('SUM(total) AS total');
        $this->db->where('account', 'Victor');
        $query = $this->db->get('transfers');

        $output['transfered'] = (float) $query->row()->total ? $query->row()->total : 0;

        $output['pending'] = $output['total'] - $output['transfered'];

        return $output;
    }

    public function get_splittings_total()
    {
        $output = array();

        $sql = "
            SELECT
                ROUND(
                    ROUND(SUM(total), 2) -
                    ROUND(SUM(commission), 2) -
                    ROUND(SUM(raw_material), 2)
                , 2) * 0.30 AS total
            FROM sales
            WHERE (status = 'Finalizado' OR status = 'En Camino')
            AND split_earnings
        ";

        $query = $this->db->query($sql);

        $output['total'] = (float) $query->row()->total ? $query->row()->total : 0;

        $this->db->select('SUM(total) AS total');
        $this->db->where('account', 'Aztrid');
        $query = $this->db->get('transfers');

        $output['transfered'] = (float) $query->row()->total ? $query->row()->total : 0;

        $this->db->select('SUM(total) AS total');
        $query = $this->db->get('expenses');

        $output['expenses'] = (float) $query->row()->total ? $query->row()->total * 0.30 : 0;

        $output['pending'] = $output['total'] - $output['expenses'] - $output['transfered'];

        return $output;
    }


}