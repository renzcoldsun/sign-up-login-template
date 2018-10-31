<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_model extends MY_Model {

    public $table_name = "dlpclienttable";
    public $fields = Array(
        "email"             => "CHARACTER(255)",
        "password"          => "CHARACTER(255)",
        "phone_number"      => "CHARACTER(255)",
        "first_name"        => "CHARACTER(255)",
        "last_name"         => "CHARACTER(255)",
        "middle_name"       => "CHARACTER(255)",
        "address1"          => "CHARACTER(255)",
        "address2"          => "CHARACTER(255)",
        "address3"          => "CHARACTER(255)",
        "address4"          => "CHARACTER(255)",
        "city"              => "CHARACTER(255)",
        "state"             => "CHARACTER(255)",
        "zip_code"          => "CHARACTER(255)",
        "occupation"        => "CHARACTER(255)",
        "source_of_funds"   => "CHARACTER(255)",
        "usage_of_funds"    => "CHARACTER(255)",
        "employer"          => "CHARACTER(255)",
        "ss_id_number"      => "CHARACTER(255)",
        "key1"              => "CHARACTER(255)",
        "key2"              => "CHARACTER(255)",
        "account_number"    => "BIGINT",
        "domain"            => "CHARACTER(255)",
        "backoffice"        => "TINYINT(1)",
        "record_sent"       => "TINYINT(1)"
    );
    public $id_field = "email";
    public $engine = "INNODB";
    public $secure_fields = Array(
        "email",
        "key1",
        "key2",
        "account_number",
        "domain"
    );

    public function __construct() {
        parent::__construct();
    }

    public function auth($username, $password) {
        $this->email = NULL;
        $this->get($username, NULL);
        if($this->email == NULL) return FALSE;
        if($this->password == $password) return TRUE;
        return false;
    }

    public function getClientDetails($username = NULL, $json = FALSE, $secure = FALSE) {
        $json_string = "";
        if($username == NULL) return $json_string;
        if($this->get($username, "email")) {
            $retval = $this->results[0];
        }
        foreach($this->fields as $field => $desc) {
            if(!in_array($field, $this->secure_fields)) unset($retval[$field]);
        }
        if($json) $json_string = json_encode($retval);
        if ($json) return $json_string;
        else return $retval;
    }
}