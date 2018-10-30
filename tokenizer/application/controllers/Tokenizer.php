<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tokenizer extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('client_model');
        $this->load->model('token_model');
        header("Content-type: text/plain");
    }

    public function index($token) {
        if($this->token_model->validateToken($token)) {
            $json_string = $this->client_model->getClientDetails($this->token_model->username);
            var_dump($json_string);
        } else {
            echo "Does not compute";
        }
    }

    public function getToken($username = NULL, $password = NULL) {
        if($this->client_model->auth($username, $password)) {
            echo $this->token_model->getValidToken($username);
        } else {
            echo "Invalid User!!!";
        }
    }
}