<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pinjaman extends CI_Controller
{
	public function tambah()
	{
		// Protect the controller from unauthorized access
		if (!in_array($this->session->userdata('user')['roles'], ['anggota', 'admin'])) redirect(base_url(('auth')));

		// Prevent direct access to this method
		if (!$this->input->post('submit')) return show_404();

		// Get form data
		$jumlah_permintaan 	= $this->input->post('jumlah_permintaan');
		$catatan 			= $this->input->post('catatan');

		// Check if empty fields
		if (empty($jumlah_permintaan) || empty($catatan)) {

			// Empty fields feedback
			$result = array(
				'status' => 'error',
				'message' => 'Mohon isi semua data isian.'
			);
		} else {

			// Load model
			$this->load->model("PinjamanModel");

			if ($this->PinjamanModel->tambah($this->session->userdata('user')['user_id'], $jumlah_permintaan, $catatan)) {

				// Success feedback
				$result = array(
					'status' => 'success',
					'message' => 'Berhasil mengajukan pinjaman.'
				);
			} else {

				// Error feedback
				$result = array(
					'status' => 'error',
					'message' => 'Ada yang salah. Silahkan ulang lagi.'
				);
			}
		}

		// Return result as JSON
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($result);
		return;
	}

	public function verifikasi()
	{
		// Protect the controller from unauthorized access
		if (!in_array($this->session->userdata('user')['roles'], ['agen', 'admin'])) redirect(base_url(('auth')));

		// Prevent direct access to this method
		if (!$this->input->post('submit')) return show_404();

		// Get form data
		$pinjaman_id 	= $this->input->post('pinjaman_id');
		$status 		= $this->input->post('status');

		// Load model
		$this->load->model("PinjamanModel");

		if ($this->PinjamanModel->verifikasi($pinjaman_id, $status)) {

			// Success feedback
			$result = array(
				'status' => 'success',
				'message' => 'Berhasil mengubah status pinjaman.'
			);
		} else {

			// Error feedback
			$result = array(
				'status' => 'error',
				'message' => 'Ada yang salah. Silahkan ulang lagi.'
			);
		}

		// Return result as JSON
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($result);
		return;
	}

	public function tambah_transaksi()
	{
		// Protect the controller from unauthorized access
		if (!in_array($this->session->userdata('user')['roles'], ['agen', 'admin'])) redirect(base_url(('auth')));

		// Prevent direct access to this method
		if (!$this->input->post('submit')) return show_404();

		// Get form data
		$jumlah_pembayaran	= $this->input->post('jumlah_pembayaran');
		$pinjaman_id 		= $this->input->post('pinjaman_id');

		// Check if empty fields
		if (empty($jumlah_pembayaran) || empty($pinjaman_id)) {

			// Empty fields feedback
			$result = array(
				'status' => 'error',
				'message' => 'Mohon isi semua data isian.'
			);
		} else {

			// Load model
			$this->load->model("PembayaranModel");
			$insert_id = $this->PembayaranModel->tambah_transaksi($jumlah_pembayaran, $pinjaman_id);

			if ($insert_id) {

				// Success feedback
				$result = array(
					'status' => 'success',
					'message' => 'Berhasil menambahkan pembayaran.',
					'data' => array(
						'insert_id' => $insert_id
					)
				);
			} else {

				// Error feedback
				$result = array(
					'status' => 'error',
					'message' => 'Ada yang salah. Silahkan ulang lagi.'
				);
			}
		}

		// Return result as JSON
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($result);
		return;
	}

	public function delete_transaksi()
	{
		// Protect the controller from unauthorized access
		if (!in_array($this->session->userdata('user')['roles'], ['agen', 'admin'])) redirect(base_url(('auth')));

		// Prevent direct access to this method
		if (!$this->input->post('submit')) return show_404();

		// Get form data
		$pembayaran_id 	= $this->input->post('pembayaran_id');

		// Load model
		$this->load->model("PembayaranModel");

		if ($this->PembayaranModel->hapus_transaksi($pembayaran_id)) {

			// Success feedback
			$result = array(
				'status' => 'success',
				'message' => 'Berhasil menghapus pembayaran.'
			);
		} else {

			// Error feedback
			$result = array(
				'status' => 'error',
				'message' => 'Ada yang salah. Silahkan ulang lagi.'
			);
		}

		// Return result as JSON
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($result);
		return;
	}

	public function data_graph_pinjaman()
	{
		// Protect the controller from unauthorized access
		if (!in_array($this->session->userdata('user')['roles'], ['agen', 'admin'])) redirect(base_url(('auth')));

		// Prevent direct access to this method
		if (!$this->input->post('submit')) return show_404();

		// Get form data
		$year = $this->input->post('year');

		// Load model
		$this->load->model("PembayaranModel");

		// Get data
		$data = $this->PembayaranModel->data_graph_pinjaman($this->session->userdata('user')['user_id'], $year);

		$result = array(
			'status' => 'success',
			'data' => $data,
			'message' => 'Berhasil mendapatkan data.'
		);

		// Return result as JSON
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($result);
		return;
	}
}
