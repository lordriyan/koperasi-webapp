<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PinjamanModel extends CI_Model
{

	public function tambah($user_id, $jumlah_permintaan, $catatan)
	{

		// Escaping Queries to prevent SQL Injection
		$jumlah_permintaan 	= $this->db->escape($jumlah_permintaan);
		$catatan 			= $this->db->escape($catatan);
		$anggota_user_id 	= $this->db->escape($user_id);

		// Query to save new data
		$query = "INSERT
					INTO tb_pinjaman
				  VALUES (
							NULL,
							NULL,
							$anggota_user_id,
							$jumlah_permintaan,
							$catatan,
							'menunggu',
							NOW()
						 )";

		// Return as boolean
		return $this->db->query($query) === TRUE;
	}

	public function getPinjamanAktifByUserId($user_id, $isAgen = false)
	{
		// Escaping Queries to prevent SQL Injection
		$user_id = $this->db->escape($user_id);

		// Query to get all data
		$query = "SELECT p.*
					   , SUM(pb.jumlah) AS total_lunas 
					   , p.pinjaman_id AS pinjaman_id
					   , u.fullname AS agen_name
					FROM tb_pinjaman p
				  LEFT
				    JOIN tb_user u 
					  ON p.agen_user_id = u.user_id
				  LEFT
				  	JOIN tb_pembayaran pb
					  ON p.pinjaman_id = pb.pinjaman_id
				   WHERE p.anggota_user_id = $user_id
				     AND p.status = 'diterima' GROUP BY p.pinjaman_id";

		// Change query if agen is true
		if ($isAgen) {
			$query = "SELECT *
						   , SUM(pb.jumlah) AS total_lunas 
						   , p.pinjaman_id AS pinjaman_id
						   , u.fullname AS anggota_name
						FROM tb_pinjaman p
					  LEFT
					    JOIN tb_user u 
						  ON p.anggota_user_id = u.user_id
					  LEFT
				  	    JOIN tb_pembayaran pb
					      ON p.pinjaman_id = pb.pinjaman_id
					   WHERE p.agen_user_id = $user_id
					     AND p.status = 'diterima' GROUP BY p.pinjaman_id";
		}

		// Return as array
		return $this->db->query($query)->result_array();
	}

	public function getPinjamanNonaktifByUserId($user_id, $isAgen = false)
	{
		// Escaping Queries to prevent SQL Injection
		$user_id = $this->db->escape($user_id);

		// Query to get all data
		$query = "SELECT *
					FROM tb_pinjaman
				   WHERE anggota_user_id = $user_id
				     AND status != 'diterima'";

		// Change query if agen is true
		if ($isAgen) {
			$query = "SELECT *
					    FROM tb_pinjaman p
					  LEFT
						JOIN tb_user u
						  ON p.anggota_user_id = u.user_id
				       WHERE status != 'diterima'";
		}

		// Return as array
		return $this->db->query($query)->result_array();
	}

	public function verifikasi($pinjaman_id, $status)
	{
		// Escaping Queries to prevent SQL Injection
		$pinjaman_id = $this->db->escape($pinjaman_id);
		$status 	 = $this->db->escape($status);
		$agen_id 	 = $this->db->escape($this->session->userdata('user')['user_id']);

		// Query to save new data
		$query = "UPDATE tb_pinjaman
				     SET status = $status,
					 	 agen_user_id = $agen_id
				   WHERE pinjaman_id = $pinjaman_id";

		// Return as boolean
		return $this->db->query($query) === TRUE;
	}

	public function jumlahAnggotaDiterima($agen_id)
	{
		// Escaping Queries to prevent SQL Injection
		$agen_id = $this->db->escape($agen_id);

		// Query to get all data
		$query = "SELECT COUNT(*) AS jumlah
					FROM tb_pinjaman
				   WHERE status = 'diterima'
				     AND agen_user_id = $agen_id
				  GROUP
				  	  BY anggota_user_id";

		$sql = $this->db->query($query);
		
		// Return as number
		return ($sql->row()) ? $sql->row()->jumlah : 0;

	}

	public function jumlahAnggotaMenunggu()
	{
		
		// Query to get all data
		$query = "SELECT COUNT(*) AS jumlah
					FROM tb_pinjaman
				   WHERE status = 'menunggu'";

		$sql = $this->db->query($query);
		
		// Return as number
		return ($sql->row()) ? $sql->row()->jumlah : 0;
	}

	public function danaBeredarByAgen($agen_id)
	{
		// Escaping Queries to prevent SQL Injection
		$agen_id = $this->db->escape($agen_id);

		// Query to get all data
		$query = "SELECT SUM(jumlah_permintaan) AS jumlah
					FROM tb_pinjaman
				   WHERE agen_user_id = $agen_id
				   	 AND status = 'diterima'";

		$sql = $this->db->query($query);
		
		// Return as number
		return ($sql->row()) ? $sql->row()->jumlah : 0;
	}

	public function danaDiedarkan()
	{
		// Query to get all data
		$query = "SELECT SUM(jumlah_permintaan) AS jumlah
					FROM tb_pinjaman
				   WHERE status = 'diterima'";

		$sql = $this->db->query($query);
		
		// Return as number
		return ($sql->row()) ? $sql->row()->jumlah : 0;
	}

	public function danaDilunasi()
	{
		// Query to get all data
		$query = "SELECT SUM(jumlah) AS jumlah
					FROM tb_pembayaran";

		$sql = $this->db->query($query);
		
		// Return as number
		return ($sql->row()) ? $sql->row()->jumlah : 0;
	}

	public function topAnggota()
	{
		// Query to get all data
		$query = "SELECT *
					   , SUM(p.jumlah_permintaan) AS jumlah_pinjaman
					FROM tb_pinjaman p
				  LEFT
				  	JOIN tb_user u
					  ON p.anggota_user_id = u.user_id
				   WHERE p.status = 'diterima'
				  GROUP
				  	  BY u.user_id
				  ORDER
				  	  BY jumlah_pinjaman DESC";

		// Return as array
		return $this->db->query($query)->result_array();
	}

	public function topAgen()
	{
		// Query to get all data
		$query = "SELECT *
					   , SUM(p.jumlah_permintaan) AS jumlah_pinjaman
					FROM tb_pinjaman p
				  LEFT
				  	JOIN tb_user u
					  ON p.agen_user_id = u.user_id
				   WHERE p.status = 'diterima'
				  GROUP
				  	  BY u.user_id
				  ORDER
				  	  BY jumlah_pinjaman DESC";

		// Return as array
		return $this->db->query($query)->result_array();
	}
}

