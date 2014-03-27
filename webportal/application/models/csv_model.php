<?php
 
class Csv_model extends CI_Model {
 
    function __construct() {
        parent::__construct();
 
    }
 
    function get_addressbook() {     
        $query = $this->db->get('leads');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }
 
    function insert_csv($data) {
        $this->db->insert('leads', $data);
    }
}