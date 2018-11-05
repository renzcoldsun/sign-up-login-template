<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

    public $table_name = NULL;
    public $fields = NULL;
    public $id_field = "id";
    public $engine = "INNODB";
    public $results = Array();

    public function __construct() {
        parent::__construct();
    }

    public function memoryCheck() {
        $this->engine == trim(strtoupper($this->engine));
        if($this->engine == "MEMORY") {
            if(!$this->isAssoc($this->fields)) {
                throw new Exception("Cannot proceed memory check and creation due to invalid declaration of model class :: " . get_called_class());
            }
            $sql = "CREATE TABLE IF NOT EXISTS " . $this->table_name . " (";
            $field_length = count(array_keys($this->fields));
            $field_count = 1;
            foreach($this->fields as $key => $field) {
                $sql .= $key . " " . $field;
                if($field_count < $field_length) $sql .= ", ";
                $field_count++;
            }
            $sql .= ") ENGINE = MEMORY";
            $this->db->query($sql);
        }
    }

    public function get($value = NULL, $field = NULL) {
        $this->results = Array();
        if($field == NULL or $field == "") $field = $this->id_field;
        if($value == NULL or $value == "") throw new Exception("Value is blank error");
        $field = preg_replace("/[^A-Za-z0-9_]+/", "", strtolower($field));
        if(!array_key_exists($field, $this->fields)) throw new Exception("Field is not in the table error");
        $data = Array($field => $value);
        $query = $this->db->get_where($this->table_name, $data);
        $found = false;
        $result = Array();
        foreach($query->result() as $row) {
            $result = Array();
            foreach($this->fields as $field => $desc) {
                $this->$field = $row->$field;
                $result[$field] = $row->$field;
            }
            $found = true;
        }
        $this->results[] = $result;
        return $found;
    }

    public function all() {
        $returnvalue = Array();
        $query = $this->db->get($this->table_name);
        foreach($query->result() as $row) {
            $value = Array();
            foreach($this->fields as $field => $field_desc) {
                $value[$field] = $row->$field;
            }
            $returnvalue[] = $value;
        }
        return $returnvalue;
    }

    // utilities
    private function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public function gen_uuid() {
        return sprintf( '%04x%04x%04x%04x%04x%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
    
            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),
    
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,
    
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,
    
            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }


}
