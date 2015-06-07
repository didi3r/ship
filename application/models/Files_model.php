<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Files_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();

	}

	public function save_file($sale_id, $file)
	{
		date_default_timezone_set('America/Mexico_City');

		$parts = pathinfo($file['name']);
		$name = preg_replace('/[^\w]+/', '_', $parts['filename']);

		$filename = $name . '__' . date('Ymdhis') . '.' . $parts['extension'];
		$name .= '.' . $parts['extension'];

		// die($name . '|' . $filename);

		$destination = FCPATH . 'public' . DIRECTORY_SEPARATOR  . 'uploads' . DIRECTORY_SEPARATOR  . $filename;

		if(move_uploaded_file($file['tmp_name'] , $destination)) {
			$data = array(
				'sale_id' => $sale_id,
				'name' => $name,
				'path' => $destination,
				'url' => 'public/uploads/' . $filename
			);

			$this->db->insert('files', $data);
			$this->check_sale_files($sale_id);

			return $this->get_files($sale_id);
		}
	}

	public function delete_file($sale_id, $file_id)
	{
		$this->db->where('id', $file_id);
		$this->db->where('sale_id', $sale_id);
		$query = $this->db->get('files');
		$row = $query->row();
		if($row) {
			if(unlink($row->path)) {
				$this->db->delete('files', array('id' => $file_id));
				$this->check_sale_files($row->sale_id);
			}
		}

		return $this->get_files($sale_id);
	}

	public function get_files($sale_id)
	{
		$this->db->where('sale_id', $sale_id);
		$query = $this->db->get('files');

		$output = array();
		foreach ($query->result() as $row) {
			$output[] = $this->convert_to_array($row);
		}

		return $output;
	}

	private function convert_to_array($row)
	{
		return array(
			'id' => $row->id,
			'name' => $row->name,
			'url' => $row->url
		);
	}

	private function check_sale_files($sale_id)
	{
		$this->db->from('files');
		$this->db->where('sale_id', $sale_id);
		if($this->db->count_all_results() > 0) {
			$this->db->set('has_files', true);
		} else {
			$this->db->set('has_files', false);
		}
		$this->db->where('id', $sale_id);
		$this->db->update('sales');
	}

}

/* End of file Files_model.php */
/* Location: ./application/models/Files_model.php */