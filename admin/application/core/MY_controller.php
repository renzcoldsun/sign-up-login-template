<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

    public function show_flash($key, $start_tag, $end_tag, $count, $remove) {
        $messages = $this->session->flashdata($key);
        $contents = "";
        $c = 0;
        if(is_array($messages)) {
            foreach($messages as $message) {
                if($c >= $count) break;
                $contents .= $start_tag . $message . $end_tag;
                $c++;
            }
        }
        if(!$remove) {
            $this->session->set_flashdata($key, $messages);
        }
        return $contents;
    }

    public function getUsername() {
        $username = "UNAUTH USER";
        $this->load->library('authlib');
        $username = $this->authlib->get_session_var('username');
        return $username;
    }
}