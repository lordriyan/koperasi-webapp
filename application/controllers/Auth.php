<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	public function index()
	{
		// Redirect user to dashboard if already logged in
		if ($this->session->userdata('user') !== null) redirect(base_url(('dashboard')));

		// Show login page
		$this->load->view('pages/login');
	}

}
