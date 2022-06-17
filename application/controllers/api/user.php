<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

	public function add($roles)
	{
		// Protect the controller from unauthorized access
		if ($roles == 'agen') {
			if (!in_array($this->session->userdata('user')['roles'], ['admin'])) redirect(base_url(('auth')));
		} elseif ($roles == 'anggota') {
			if (!in_array($this->session->userdata('user')['roles'], ['admin', 'agen'])) redirect(base_url(('auth')));
		} else {
			redirect(base_url(('auth')));
		}

		// Prevent direct access to this method
		if (!$this->input->post('submit')) return show_404();

		// Get form data
		$fullname 	= $this->input->post('fullname');
		$username 	= $this->input->post('username');
		$password 	= $this->input->post('password');

		// Load model
		$this->load->model('UserModel');

		// Insert data and get id
		$id = $this->UserModel->new_user($fullname, $username, $password, $roles);

		$config = array(
			'upload_path' => __DIR__ . "/../../../public/assets/images/users/",
			'allowed_types' => "jpg|png|jpeg",
			'overwrite' => TRUE,
			'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
			'max_height' => "6000",
			'max_width' => "6000",
			'file_name' => $id
		);

		$this->load->library('upload', $config);

		if ($this->upload->do_upload('photo')) {

			$result = array(
				'status' => 'success',
				'data' => $this->upload->data(),
				'message' => 'Berhasil menyimpan data.'
			);
		} else {

			$this->UserModel->delete_user($id, $roles);

			// Error feedback
			$result = array(
				'status' => 'errro',
				'message' => $this->upload->display_errors()
			);
		}

		// Return result as JSON
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($result);
		return;
	}

	public function delete($roles)
	{
		// Protect the controller from unauthorized access
		if ($roles == 'agen') {
			if (!in_array($this->session->userdata('user')['roles'], ['admin'])) redirect(base_url(('auth')));
		} elseif ($roles == 'anggota') {
			if (!in_array($this->session->userdata('user')['roles'], ['admin', 'agen'])) redirect(base_url(('auth')));
		} else {
			redirect(base_url(('auth')));
		}

		// Protect the controller from unauthorized access
		if (!in_array($this->session->userdata('user')['roles'], ['admin'])) redirect(base_url(('auth')));

		// Prevent direct access to this method
		if (!$this->input->post('submit')) return show_404();

		// Get form data
		$user_id = $this->input->post('user_id');

		// Load model
		$this->load->model('UserModel');

		// Delete user
		if ($this->UserModel->delete_user($user_id, $roles)) {

			$result = array(
				'status' => 'success',
				'message' => 'Data berhasil dihapus.'
			);
		} else {

			// Error feedback
			$result = array(
				'status' => 'error',
				'message' => 'Gagal menghapus data.'
			);
		}

		// Return result as JSON
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($result);
		return;
	}
}
