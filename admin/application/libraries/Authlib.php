<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class AuthLib {
    private $CI = NULL;
    public $session_info_key;
    private $auth_model;
    private $delimiter = "pbkdf2:|:";
    private $login_page;

    public function __construct($model = "") {
        $this->CI =& get_instance();
        $this->CI->load->library('session');
        $this->CI->load->helper('url');

        if($model == "" || $model == NULL) $model = "auth_model";
        $this->CI->load->model($model, 'lcs_auth_model');
        $this->auth_model = $model;
    }

    private function generate_hash($username, $password, $algo = "sha512") {
        if($username == "" || $username == NULL) return "";
        if($password == "" || $password == NULL) return "";
        if(!function_exists('hash_pbkdf2')) die("Unable to continue, hash_pbkdf2 is a requirement");
        if($algo == "" || $algo == NULL) {
            $algos = hash_algos();
            $algo = $algos[count($algos) - 1]; 
        }
        $salt = $this->generate_salt($username, $password);
        $hashed = $this->delimiter . $algo . $this->delimiter . hash_pbkdf2($algo, $password, $salt, 64);
        return $hashed;
    }

    public function generate_salt($username, $password) {
        $salt = "";
        if(strlen($username) < 4) $username = substr(str_repeat($username, 100), 0, 20);
        $salt .= substr($username, 0, 6);
        if(strlen($password) < 20) $password = substr(str_repeat($password, 100), 0, 20);
        $salt .= substr($password, 14, 6);
        return $salt;
    }

    public function get_algo($secret) {
        if(!strstr($this->delimiter, $secret)) return "";
        $tmp = explode($this->delimeter, $secret);
        if(count($tmp) != 4) return "";
        return $tmp[2];
    }

    public function generate_session_key() {
        return uniqid();
    }

    public function get_session_var($key) {
        if($key == "" || $key == NULL) return NULL;
        $this->session_info_key = $this->CI->session->userdata('sinfo_id');
        if($this->session_info_key == "" || $this->session_info_key == NULL) return NULL;
        $values = $this->CI->session->userdata($this->session_info_key);
        if(array_key_exists($key, $values)) {
            return $values[$key];
        }
        return NULL;
    }

    public function set_session_var($key, $value) {
        if($key == "" || $key == NULL) return FALSE;
        $this->session_info_key = $this->CI->session->userdata('sinfo_id');
        if($this->session_info_key == "" || $this->session_info_key == NULL) return FALSE;
        $values = $this->CI->session->userdata($this->session_info_key);
        $values[$key] = $value;
        $this->CI->session->set_userdata($this->session_info_key, $values);
        return TRUE;
    }

    public function securePage($login_page="/auth/login") {
        $this->CI->load->helper('url');
        $this->session_info_key = $this->CI->session->userdata('sinfo_id');
        // if the key above is null
        // redirect to login page
        $this->login_page = $login_page;
        if($this->session_info_key == NULL)
            redirect($login_page);
    }

    public function login($username, $password, $redirect_dashboard="/admin/index") {
        if($username == "" || $username == NULL) $this->securePage();
        if($password == "" || $password == NULL) $this->securePage();
        $this->session_info_key = $this->CI->session->userdata('sinfo_id');
        if($this->session_info_key != NULL && $this->session_info_key != "") {
            $this->CI->session->unset_userdata("sinfo_id");
            $this->CI->session->unset_userdata($this->session_info_key);
        }
        # get new session info key
        $this->session_info_key = $this->generate_session_key();
        $hashed_key = $this->generate_hash($username, $password);
        $login_data = $this->CI->lcs_auth_model->login($username, $hashed_key);
        if($login_data == NULL) {
            $errors = $this->CI->session->flashdata("login_errors");
            if($errors == NULL) $errors = Array();
            $errors[] = "Username / Email Invalid";
            $this->CI->session->set_flashdata("login_errors", $errors);
            redirect($this->login_page);
        }
        $secret = $login_data["secret"];
        $dbalgo = $this->get_algo($secret);
        $algo = $this->get_algo($hashed_key);
        if($algo != $dbalgo) {
            $errors = $this->CI->session->flashdata("login_errors");
            if($errors == NULL) $errors = Array();
            $errors[] = "Algorithm Error Please See Developer";
            $this->CI->session->set_flashdata("login_errors", $errors);
            redirect($this->login_page);
        }
        if($hashed_key != $secret) {
            $errors = $this->CI->session->flashdata("login_errors");
            if($errors == NULL) $errors = Array();
            $errors[] = "Username / Email Invalid";
            $this->CI->session->set_flashdata("login_errors", $errors);
            redirect($this->login_page);
        }

        // if we made it up to here, we are suuposed to log in
        $save_data = Array(
            "username" => $login_data["username"],
            "date_created" => $login_data["date_created"],
            "last_login" => $login_data["last_login"]
        );
        $this->CI->session->set_userdata($this->session_info_key, $save_data);
        # $this->session_info_key = $this->CI->session->userdata('sinfo_id');
        $this->CI->session->set_userdata('sinfo_id', $this->session_info_key);
        redirect($redirect_dashboard);
    }

    public function logout($login_page="/auth/login") {
        $this->session_info_key = $this->CI->session->userdata('sinfo_id');
        if($this->session_info_key != NULL && $this->session_info_key != "") {
            $this->CI->session->unset_userdata("sinfo_id");
            $this->CI->session->unset_userdata($this->session_info_key);
        }
        redirect($login_page);        
    }
}
