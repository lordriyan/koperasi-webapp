<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	public function check_login()
	{
		// Prevent direct access to this method
		if (!$this->input->post('submit')) return show_404();

		// Check if empty fields
		if (
			empty($this->input->post('username')) ||
			empty($this->input->post('password'))
		) {

			// Empty fields feedback
			$result = array(
				'status' => 'error',
				'message' => 'Please fill the form completely.'
			);
		} else {

			// Load model
			$this->load->model("UserModel");

			// Check login credentials to database
			$user = $this->UserModel->check_login(
				$this->input->post('username'),
				$this->input->post('password')
			);

			// Default feedback
			$result = array(
				'status' => 'error',
				'message' => 'Invalid username or password!'
			);

			// Decide if login is successful or not
			if (!empty($user)) {
				// Set session data
				$this->session->set_userdata('user', $user[0]);

				// Update last login time
				$this->UserModel->update_last_login($user[0]['user_id']);

				$result = array(
					'status' => 'success',
					'message' => 'Login successful!'
				);
			}
		}

		// Return result as JSON
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($result);
		return;
	}

	public function logout()
	{
		// Destroy session data
		$this->session->unset_userdata('user');
		redirect(base_url(('auth')));
	}
}
