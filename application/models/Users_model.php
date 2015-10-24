<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();

	}

	public function get_all()
	{
		$this->db->select('id, email, first_name AS name, last_name AS lastName, role', false);
		$output = $this->db->get('users');

		return $output->result();
	}

}

/* End of file Users_model.php */
/* Location: ./application/models/Users_model.php */