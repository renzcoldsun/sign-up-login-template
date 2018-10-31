<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Server_model extends MY_Model {

    public $table_name = "dlpclientserverdetails";
    public $fields = Array(
        "server_type"       => "CHARACTER(255)",
        "domain"            => "CHARACTER(255)",
        "server_ip"         => "CHARACTER(255)",
        "server_port"       => "CHARACTER(255)",
        "dns_name"          => "CHARACTER(255)"
    );

    public function __construct() {
        parent::__construct();
    }

    // SELECT server_type, server_ip, server_port, dns_name FROM dlpclientserverdetails WHERE domain = 'ALL' OR domain = ?
    public function getDomainServers($domain) {
        $returnValue = Array();
        $domain = trim($domain);
        $data = Array("domain" => $domain);
        $this->db->where($data);
        $this->db->or_where(Array("domain" => "ALL"));
        $query = $this->db->get($this->table_name);
        foreach($query->result() as $row) {
            $server_type = strtoupper($row->server_type);
            $dns_name = $row->dns_name;
            $returnValue[$server_type ."_server_type"] = $row->server_type;
            $returnValue[$server_type ."_server_ip"] = $row->server_ip;
            $returnValue[$server_type ."_server_port"] = $row->server_port;
            if($dns_name != NULL AND $dns_name != "")
                $returnValue[$server_type ."_server_ip"] = $row->dns_name;
        }
        return $returnValue;
    }
}