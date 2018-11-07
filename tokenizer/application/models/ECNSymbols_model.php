<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ECNSymbols_model extends MY_Model {

    public $table_name = "ecnsymbols";
    public $fields = Array(
        "symbol" => "CHARACTER(255) NOT NULL PRIMARY KEY",
        "exchange" => "CHARACTER(255) NOT NULL",
        "formatprice" => "CHARACTER(255) NOT NULL",
        "formatsize" => "CHARACTER(255) NOT NULL",
        "bidvol" => "CHARACTER(255) NOT NULL",
        "askvol" => "CHARACTER(255) NOT NULL"
    );
    public $id_field = "symbol";
    public $engine = "INNODB";

    public function __construct() {
        parent::__construct();
    }
}