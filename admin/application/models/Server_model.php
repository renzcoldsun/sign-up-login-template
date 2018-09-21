<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_model extends MY_Model {
    public $fields = Array(
        "server_type", "domain", "server_ip", "server_port"
    );
    public $table_name = "dlpclientserverdetails";
    private $table_name = 'dlpclienttable';
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
}
