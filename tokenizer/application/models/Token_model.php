<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Token_model extends MY_Model {
    public $table_name = "dlptoken";
    public $fields = Array(
        "id" => "BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY",
        "username" => "CHARACTER(255) NOT NULL",
        "time_stamp" => "DATETIME",
        "unique_id" => "CHARACTER(16) NOT NULL UNIQUE"
    );
    public $id_field = "id";
    public $engine = "MEMORY";

    private $expiry_hours = 12;
    private $date_format = 'Y-m-d H:i:s';

    public function __construct() {
        parent::__construct();
        $this->memoryCheck();
    }

    public function getValidToken($username, $create = TRUE) {
        $this->username = $username;
        if($this->get($username, "username")) {
            if($this->validateToken($this->unique_id)) {
                return $this->unique_id;
            } else {
                $this->create_new_token();
            }
        } else {
            $this->create_new_token();
        }
        return $this->unique_id;
    }

    public function validateToken($token) {
        if($token == NULL) return FALSE;
        if(!isset($this->time_stamp)) {
            if(!$this->get($token, "unique_id")) {
                return FALSE;
            }
        }
        $date = new DateTime();
        $code_date = new DateTime($this->time_stamp);
        $code_date->modify("+" . $this->expiry_hours . " hours");
        # echo "Token valid until " . $code_date->format($this->date_format) . "  :: " . $date->format($this->date_format);
        return $date < $code_date;
    }

    private function create_new_token() {
        $uuid = $this->gen_uuid();
        $now = date('Y-m-d H:i:s');
        $values = Array(
            "id" => NULL,
            "username" => $this->username,
            "time_stamp" => $now,
            "unique_id" => $uuid
        );
        $this->db->insert($this->table_name, $values);
        $id = $this->db->insert_id();
        $this->get($id, "id");
    }
}