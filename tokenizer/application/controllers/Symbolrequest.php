<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Symbolrequest extends MY_Controller {
    public function __construct() {
        parent::__construct();
        header("Content-type:text/plain");
        $this->load->model('ECNSymbols_model');
    }

    public function index() {
        $symbol = $this->input->get("symbol");
        if($symbol == NULL) {
            $symbol = $this->input->post("symbol");
        }
        if($symbol == NULL) {
            $symbols = $this->ECNSymbols_model->all();
        }
        else {
            if($this->ECNSymbols_model->get($symbol, "symbol"))
                $symbols = $this->ECNSymbols_model->results;
        }
        echo json_encode($symbols);
    }
}