<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('authlib');
		$this->authlib->securePage();
		$this->load->model('client_model');
	}	
	public function index()
	{
		$this->load->library('authlib');
		$this->authlib->securePage();
		$this->load->model('client_model');
		$clients = Array("clients" => $this->client_model->getClients());
		$data = Array(
			"title" => "Admin Dashbaord",
			"content" => $this->load->view("admin/clients/list.html", $clients, TRUE),
			"header_content" => $this->load->view("admin/clients/header_content.html", NULL, TRUE),
			"footer_content" => $this->load->view("admin/clients/list_footer.html", NULL, TRUE),
		);
		$this->load->view('admin/layout.html', $data);
	}

##### CLIENT ROUTINES
	public function client($action, $id) {
		if(method_exists($this, $action)) {
			$this->$action($id);
		}
	}

	public function edit($id) {
		$title = "Admin Dashbaord :: Editing User : " . $id;
		$data = Array(
			"title" => $title,
		);
		try {
			$user = Array("user" => $this->client_model->get("username", $id));
			$data["content"] = $this->load->view("admin/clients/edit.html", $user, TRUE);
		} catch (Exception $ex) {
			redirect("/admin/index");
		}
		$this->load->view('admin/layout.html', $data);
	}

	public function logout() {
		$this->authlib->logout();
	}
##### CLIENT ROUTINES

##### SERVER ROUTINES

	public function server_details($action, $id = NULL) {
		$data = array();
		$this->load->view('admin/layout.html', $data);
	}

##### CLIENT ROUTINES

}
