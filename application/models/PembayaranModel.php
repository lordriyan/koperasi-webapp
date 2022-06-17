<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PembayaranModel extends CI_Model
{

	public function getTransaksiByPinjamanId($pinjaman_id)
	{
		// Escaping Queries to prevent SQL Injection
		$pinjaman_id = $this->db->escape($pinjaman_id);

		// Query to get all transaksi by pinjaman_id
		$query1 = "SELECT * 
					FROM tb_pembayaran
				   WHERE pinjaman_id = $pinjaman_id";

		$query2 = "SELECT * 
					 FROM tb_pinjaman p
				   LEFT
				   	 JOIN tb_user u
					   ON p.anggota_user_id = u.user_id					
				    WHERE p.pinjaman_id = $pinjaman_id";


		$array1 = $this->db->query($query1)->result_array();
		$array2 = $this->db->query($query2)->result_array()[0];

		// Return as array
		return [
			'pembayaran' => $array1,
			'pinjaman' => $array2
		];
	}

	public function tambah_transaksi($jumlah_pembayaran, $pinjaman_id)
	{
		// Escaping Queries to prevent SQL Injection
		$jumlah_pembayaran	= $this->db->escape($jumlah_pembayaran);
		$pinjaman_id		= $this->db->escape($pinjaman_id);

		// Query to insert new transaksi
		$query = "INSERT
					INTO tb_pembayaran 
						 (
							jumlah,
							pinjaman_id,
							tanggal
						 )
				  VALUES (
							$jumlah_pembayaran,
							$pinjaman_id,
							NOW()
						 )";

		// Return as boolean
		return $this->db->query($query) ? $this->db->insert_id() : false;
	}

	public function hapus_transaksi($pembayaran_id)
	{
		// Escaping Queries to prevent SQL Injection
		$pembayaran_id = $this->db->escape($pembayaran_id);

		// Query to delete transaksi
		$query = "DELETE
				  FROM tb_pembayaran
				  WHERE pembayaran_id = $pembayaran_id";

		// Return as boolean
		return $this->db->query($query) === TRUE;
	}

	public function data_graph_pinjaman($agen_id, $year)
	{
		// Escaping Queries to prevent SQL Injection
		$agen_id 	= $this->db->escape($agen_id);
		$year 		= $this->db->escape($year);

		// Query to get all pinjaman by agen_id
		$query1 = "SELECT DATE_FORMAT(p.tanggal, '%M') AS bulan
					    , SUM(p.jumlah) AS total_angsuran
					 FROM tb_pembayaran p
				   LEFT
				     JOIN tb_pinjaman pj
					   ON p.pinjaman_id = pj.pinjaman_id
				    WHERE YEAR(p.tanggal) = $year
				   	  AND pj.agen_user_id = $agen_id
				   GROUP
				  	   BY YEAR(p.tanggal), MONTH(p.tanggal)";

		$query2 = "SELECT DATE_FORMAT(tanggal, '%M') AS bulan
					    , SUM(jumlah_permintaan) AS total_pinjaman
					 FROM tb_pinjaman
				    WHERE YEAR(tanggal) = $year
				   	  AND agen_user_id = $agen_id
					  AND status = 'diterima'
				   GROUP
				  	   BY YEAR(tanggal), MONTH(tanggal)";

		$data1 = $this->db->query($query1)->result_array();
		$data2 = $this->db->query($query2)->result_array();
		
		// Return as array
		return array(
			'angsuran' => $data1,
			'pinjaman' => $data2
		);
	}
}
