<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_model extends MY_Model {
    public $fields = Array(
        "username", "phone_number", "first_name", "last_name", "middle_name",
        "address1", "address2", "address3", "address4", "city", "state",
        "zip_code", "email", "occupation", "source_of_funds", "usage_of_funds",
        "employer", "ss_id_number", "key1", "key2", "account_number", "domain"
    );
    private $table_name = 'dlpclienttable';
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getClients() {
        $clients = Array();
        $query = $this->db->get($this->table_name);
        foreach($query->result() as $row) {
            $clients[$row->username] = Array();
            foreach($this->fields as $field) {
                $clients[$row->username][$field] = $row->$field;
            }
        }
        return $clients;
    }
}

/*

CREATE TABLE IF NOT EXISTS `dlpclienttable` (
  `username` char(255) NOT NULL,
  `phone_number` char(255) NOT NULL,
  `password` char(255) NOT NULL,
  `first_name` char(255) NOT NULL,
  `last_name` char(255) NOT NULL,
  `middle_name` char(255) DEFAULT NULL,
  `address1` char(255) DEFAULT NULL,
  `address2` char(255) DEFAULT NULL,
  `address3` char(255) DEFAULT NULL,
  `address4` char(255) DEFAULT NULL,
  `city` char(255) DEFAULT NULL,
  `state` char(255) DEFAULT NULL,
  `zip_code` char(255) DEFAULT NULL,
  `email` char(255) DEFAULT NULL,
  `occupation` char(255) DEFAULT NULL,
  `source_of_funds` char(255) DEFAULT NULL,
  `usage_of_funds` char(255) DEFAULT NULL,
  `employer` char(255) DEFAULT NULL,
  `ss_id_number` char(255) DEFAULT NULL,
  `key1` char(255) DEFAULT NULL,
  `key2` char(255) DEFAULT NULL,
  `account_number` bigint(255) NOT NULL,
  `domain` char(255) NOT NULL,

*/