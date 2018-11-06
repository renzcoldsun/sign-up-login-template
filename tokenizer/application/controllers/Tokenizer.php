<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tokenizer extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('client_model');
        $this->load->model('token_model');
        $this->load->model('server_model');
        header("Content-type: text/plain");
    }

    public function index($token) {
        if($this->token_model->validateToken($token)) {
            $client_details = $this->client_model->getClientDetails($this->token_model->username);
            // add to the the server details
            $domain = $client_details["domain"];
            $connection_details = $this->server_model->getDomainServers($domain);
            $client_details = array_merge($client_details, $connection_details);
            if(isset($client_details["password"])) unset($client_details["password"]);
            echo json_encode($client_details);
        } else {
            echo "";
        }
    }

    public function getToken($username = NULL, $password = NULL) {
        if($this->client_model->auth($username, $password)) {
            echo $this->token_model->getValidToken($username);
        } else {
            echo "";
        }
    }
}