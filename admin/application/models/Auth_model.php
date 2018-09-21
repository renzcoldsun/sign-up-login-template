<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    /**
     * Model Members
     */
    public $id = 0;
    public $username;
    public $date_created;
    public $last_login;
    public $table_name = "dlpclientadmin";

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function login($username = "", $secret = "") {
        if($username == "" || $username == NULL) return NULL;
        if($secret == "" || $secret == NULL) return NULL;
        $data = Array("username" => $username);
        $query = $this->db->get_where($this->table_name, $data);
        foreach($query->result() as $row) {
            $returnData = Array(
                "username" => $row->username,
                "secret" => $row->secret,
                "date_created" => $row->date_created,
                "last_login" => $row->last_login
            );
            $this->id = $row->id;
            $this->update_login();
            return $returnData;
        }
        return NULL;
    }

    public function update_login() {
        if($this->id <= 0) return NULL;
        $this->db->set('last_login', date("Y-m-d H:i:s"));
        $this->db->where("id", $this->id);
        $this->db->update($this->table_name);
    }
}