<?php
	if(!defined('BASEPATH'))
		exit('No direct script access allowed');

	class Resetpassword_model extends CI_Model{
		function __construct(){
			parent::__construct();
	}

	function get_member_by_id($id){
		$query=$this->db->query("SELECT * FROM aff_users WHERE userid='$id'");
		return $query->result();
	}

	function update_kode($email,$data){
		$this->db->where('aff_users.email',$email);
		return $this->db->update('aff_users', $data);
	}

	function cek_kode($kode){
		$query=$this->db->query("SELECT * FROM aff_users WHERE kode='$kode'");
		return $query->result();
	}

	function cek_password_lama($password,$email){
		$query=$this->db->query("SELECT * FROM aff_users WHERE password='$password' and email='$email' ");
		return $query->result();
	}

	function ubah_password($email,$data){
		$this->db->where('aff_users.email',$email);
		return $this->db->update('aff_users', $data);
	}

}

