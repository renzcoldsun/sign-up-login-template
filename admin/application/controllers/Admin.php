<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {
	public function index()
	{
		$this->load->library('authlib');
		$this->authlib->securePage();
		$this->load->model('client_model');
		$clients = Array("clients" => $this->client_model->getClients());
		$data = Array(
			"title" => "Admin Dashbaord",
			"content" => $this->load->view("admin/clients/list.html", $clients, TRUE),
		);
		$this->load->view('admin/layout.html', $data);
	}
}
