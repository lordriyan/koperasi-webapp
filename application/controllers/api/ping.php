<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ping extends CI_Controller {
	
	public function index()
	{
		// Return as JSON
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode(['status' => 'success', 'message' => 'Pong!!!']);	// Return a JSON response
		return;
	}

}
