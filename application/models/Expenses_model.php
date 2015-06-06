<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expenses_model extends CI_Model {
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

        $query = $this->db->get('expenses');

        $output = array();
        $output['response'] = $query->result();
        $output['total_rows'] = count($query->result());

        return $output;
    }

    public function get($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('expenses');

		return $query->row();
	}


    public function create($expense)
    {
    	$data = array(
	        'date' => $expense['date'],
            'user' => $expense['user'],
	        'description' => $expense['description'],
            'total' => $expense['total']
    	);

        if($this->db->insert('expenses', $data)) {
            return $this->get($this->db->insert_id());
        }
    }

    public function update($expense)
    {
        $this->db->set('date', $expense['date']);
        $this->db->set('description', $expense['description']);
        $this->db->set('total', $expense['total']);

        if($this->db->update('expenses')) {
            return $this->get($expense['id']);
        }
    }


}