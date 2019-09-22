<?php

class Main extends CI_Controller {

	public function index() {
		$this->load->view('template/header', FALSE);
		$this->load->view('loket', FALSE);
		$this->load->view('template/footer', FALSE);
		// echo 'hahaha';
	}
}