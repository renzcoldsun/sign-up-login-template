<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task extends MY_Controller {
    public function __construct() {
        if (php_sapi_name() != "cli") {
            // Not in cli-mode
            exit("Not enabled in browser");
        }
        parent::__construct();
        $this->load->model('token_model');
    }

    public function clearInvalidTokens() {
        $tokens = $this->token_model->all();
        $now = new Datetime();
        $date_then = $now->modify("-" . $this->token_model->getExpiryString());
        $id_to_remove = Array();
        foreach($tokens as $token) {
            $id = $token["id"];
            $username = $token["username"];
            $time_stamp = date_create_from_format('Y-m-d H:i:s', $token["time_stamp"]);
            $unique_id = $token["unique_id"];
            if($time_stamp <= $date_then) {
                array_push($id_to_remove, $id);
            }
        }
        foreach($id_to_remove as $id) {
            $data = array("id" => $id);
            $this->db->delete($this->token_model->table_name, $data);
        }
        echo count($id_to_remove) . " tokens removed";
    }
}