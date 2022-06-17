<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserModel extends CI_Model
{

	public function check_login($username, $password)
	{

		// Escaping Queries to prevent SQL Injection
		$username = $this->db->escape($username);
		$password = $this->db->escape(md5($password));

		// Query to check if user exists
		$query = "SELECT * 
					FROM tb_user 
				   WHERE username = $username 
					 AND password = $password";

		// Return as array
		return $this->db->query($query)->result_array();
	}

	public function update_last_login($user_id)
	{
		// Escaping Queries to prevent SQL Injection
		$user_id = $this->db->escape($user_id);

		// Query to update last login time
		$query = "UPDATE tb_user 
				   SET last_login = NOW() 
				 WHERE user_id = $user_id";

		// Execute query
		return $this->db->query($query) === TRUE;
	}

	public function listAnggota()
	{

		// Query to get all users
		$query = "SELECT * 
					   , SUM(p.jumlah_permintaan) AS jumlah_pinjaman
					   , SUM(t.jumlah) AS jumlah_angsuran
					FROM tb_user u
				  LEFT
				  	JOIN tb_pinjaman p
					  ON u.user_id = p.anggota_user_id
				  LEFT
				  	JOIN tb_pembayaran t
					  ON p.pinjaman_id = t.pinjaman_id
				   WHERE u.roles = 'anggota'
				  GROUP
				  	  BY p.anggota_user_id";

		// Return as array
		return $this->db->query($query)->result_array();
	}

	public function listAgen()
	{

		// Query to get all users
		$query = "SELECT * 
					   , SUM(p.jumlah_permintaan) AS jumlah_pinjaman
					   , SUM(t.jumlah) AS jumlah_angsuran
					FROM tb_user u
				  LEFT
				  	JOIN tb_pinjaman p
					  ON u.user_id = p.agen_user_id
				  LEFT
				  	JOIN tb_pembayaran t
					  ON p.pinjaman_id = t.pinjaman_id
				   WHERE u.roles = 'agen'
				  GROUP
				  	  BY u.user_id";

		// Return as array
		return $this->db->query($query)->result_array();
	}

	public function new_user($fullname, $username, $password, $roles)
	{
		// Escaping Queries to prevent SQL Injection
		$fullname 	= $this->db->escape($fullname);
		$username 	= $this->db->escape($username);
		$password 	= $this->db->escape(md5($password));
		$roles		= $this->db->escape($roles);

		// Query to insert new user
		$query = "INSERT 
					INTO tb_user 
				  VALUES (
							NULL,
							$fullname, 
							$username, 
							$password, 
							$roles, 
							NULL
						)";

		// Execute query
		if ($this->db->query($query)) return $this->db->insert_id();
		return false;
	}
	public function delete_user($ids, $roles)
	{
		// Escaping Queries to prevent SQL Injection
		$id 	= $this->db->escape($ids);
		$roles 	= $this->db->escape($roles);

		// Query to delete user
		$query = "DELETE 
				  	FROM tb_user 
				   WHERE user_id = $id
				   	 AND roles = $roles
				   LIMIT 1";

		// Execute query
		if ($this->db->query($query)) {

			// Remove photo as well
			$this->delete_user_photo($ids);
			return true;
		}
		return false;
	}

	public function delete_user_photo($id)
	{
		$ext = array('jpg', 'jpeg', 'png');
		for ($i = 0; $i < count($ext); $i++) {
			$file = __DIR__.'/../../public/assets/images/users/' . $id . '.' . $ext[$i];
			if (file_exists($file)) unlink($file);
		}
	}
}
