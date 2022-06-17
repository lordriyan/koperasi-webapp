<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	public function index()
	{
		// Protect the controller from unauthorized access
		if (!in_array($this->session->userdata('user')['roles'], ['anggota', 'admin', 'agen'])) redirect(base_url(('auth')));

		// Show home page
		$this->load->view('pages/home', ['user' => $this->session->userdata('user')]);
	}

	public function pinjaman()
	{
		// Protect the controller from unauthorized access
		if (!in_array($this->session->userdata('user')['roles'], ['anggota'])) redirect(base_url(('auth')));

		// Load model
		$this->load->model('PinjamanModel');

		// Show pinjaman page
		$this->load->view('pages/pinjaman', [
			'user' => $this->session->userdata('user'),
			'pinjamansAktif' => $this->PinjamanModel->getPinjamanAktifByUserId($this->session->userdata('user')['user_id']),
			'pinjamansNonaktif' => $this->PinjamanModel->getPinjamanNonaktifByUserId($this->session->userdata('user')['user_id'])
		]);
	}

	public function pinjaman_baru()
	{
		// Protect the controller from unauthorized access
		if (!in_array($this->session->userdata('user')['roles'], ['anggota'])) redirect(base_url(('auth')));

		// Show pinjaman baru page
		$this->load->view('pages/pinjaman_baru', ['user' => $this->session->userdata('user')]);
	}

	public function daftar_pinjaman()
	{
		// Protect the controller from unauthorized access
		if (!in_array($this->session->userdata('user')['roles'], ['agen', 'admin'])) redirect(base_url(('auth')));

		// Load model
		$this->load->model('PinjamanModel');

		// Show pinjaman page
		$this->load->view('pages/daftar_pinjaman', [
			'user' => $this->session->userdata('user'),
			'pinjamansAktif' => $this->PinjamanModel->getPinjamanAktifByUserId($this->session->userdata('user')['user_id'], true),
			'pinjamansNonaktif' => $this->PinjamanModel->getPinjamanNonaktifByUserId($this->session->userdata('user')['user_id'], true)
		]);
	}

	public function detail_transaksi($pinjaman_id)
	{
		// Protect the controller from unauthorized access
		if (!in_array($this->session->userdata('user')['roles'], ['agen', 'admin'])) redirect(base_url(('auth')));

		// Check $pinjaman_id is valid
		if (!is_numeric($pinjaman_id)) redirect(base_url(('dashboard/daftar_pinjaman')));

		// Load model
		$this->load->model('PembayaranModel');

		// Show detail transaksi page
		$this->load->view('pages/detail_transaksi', [
			'user' => $this->session->userdata('user'),
			'transaksi' => $this->PembayaranModel->getTransaksiByPinjamanId($pinjaman_id)
		]);
	}

	public function keanggotaan()
	{
		// Protect the controller from unauthorized access
		if (!in_array($this->session->userdata('user')['roles'], ['agen', 'admin'])) redirect(base_url(('auth')));

		// Load model
		$this->load->model('UserModel');

		// Show keanggotaan page
		$this->load->view('pages/keanggotaan', [
			'user' => $this->session->userdata('user'),
			'anggotas' => $this->UserModel->listAnggota()
		]);
	}

	public function laporan_agen()
	{
		// Protect the controller from unauthorized access
		if (!in_array($this->session->userdata('user')['roles'], ['agen', 'admin'])) redirect(base_url(('auth')));

		// Load model
		$this->load->model('PinjamanModel');

		// Show laporan agen page
		$this->load->view('pages/laporan_agen', [
			'user' => $this->session->userdata('user'),
			'anggotaDitangani' => $this->PinjamanModel->jumlahAnggotaDiterima($this->session->userdata('user')['user_id']),
			'anggotaMenunggu' => $this->PinjamanModel->jumlahAnggotaMenunggu($this->session->userdata('user')['user_id']),
			'danaBeredar' => $this->PinjamanModel->danaBeredarByAgen($this->session->userdata('user')['user_id'])
		]);
	}

	public function laporan()
	{
		// Protect the controller from unauthorized access
		if (!in_array($this->session->userdata('user')['roles'], ['admin'])) redirect(base_url(('auth')));

		// Load model
		$this->load->model('PinjamanModel');

		// Show laporan page
		$this->load->view('pages/laporan', [
			'user' => $this->session->userdata('user'),
			'dana_diedarkan' => $this->PinjamanModel->danaDiedarkan(),
			'dana_dilunasi' => $this->PinjamanModel->danaDilunasi(),
			'top_anggota' => $this->PinjamanModel->topAnggota(),
			'top_agen' => $this->PinjamanModel->topAgen()
		]);
	}
	public function agen()
	{
		// Protect the controller from unauthorized access
		if (!in_array($this->session->userdata('user')['roles'], ['admin'])) redirect(base_url(('auth')));

		// Load model
		$this->load->model('UserModel');

		// Show agen page
		$this->load->view('pages/agen', [
			'user' => $this->session->userdata('user'),
			'agens' => $this->UserModel->listAgen()
		]);
	}
}
