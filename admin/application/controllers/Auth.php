<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

    public function __construct($model_name = "auth") {
        parent::__construct();
        $this->load->database();
        $this->load->library("authlib");
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function login() {
        if($this->input->method(FALSE) == "post") {
            $username = $this->input->post("username_email");
            $password = $this->input->post("password");
            $this->authlib->login($username, $password);
        }
        $this->load->view('admin/login.html');
    }

    public function logout() {
        $this->authlib->logout();
    }

}