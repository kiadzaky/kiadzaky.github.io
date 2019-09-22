<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notif extends CI_Controller {

	public function index()
	{
		$this->load->view('template/header', FALSE);
		$this->load->view('notif_view', FALSE);
		$this->load->view('template/footer', FALSE);
	}

}

/* End of file Notif.php */
/* Location: ./application/controllers/Notif.php */