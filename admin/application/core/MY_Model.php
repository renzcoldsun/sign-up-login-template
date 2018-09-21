<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function get($field = NULL, $value = NULL) {
        if($field == NULL or $field == "") throw new Exception("Field does not exist error");
        if($value == NULL or $value == "") throw new Exception("Value is blank error");
        $field = preg_replace("/[^A-Za-z0-9_]+/", "", strtolower($field));
        if(!array_key_exists($field, $this->fields)) throw new Exception("Field is not in the table error");
        $data = Array($field => $value);
        $query = $this->db->get_where($this->table_name, $data);
        foreach($query->result() as $row) {
            foreach($this->fields as $field) {
                $this->$field = $row->$field;
            }
        }
    }

    public function all() {
        $returnvalue = Array();
        $query = $this->db->get($this->table_name);
        foreach($query->result() as $row) {
            $value = Array();
            foreach($this->fields as $field) {
                $value[$field] = $row->$field;
            }
            $returnvalue[] = $value;
        }
        return $returnvalue;
    }

}
