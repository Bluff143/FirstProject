<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//This is the Book Model for CodeIgniter CRUD using Ajax Application.
class Router_model extends CI_Model
{
	var $table = 'router_details';
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_all_routers()
	{
		$this->db->from('router_details');
		$this->db->where('router_status',1);
		$query=$this->db->get();
		return $query->result();
	}
 
 	public function get_list_router_based_type($sap_id)
	{
		$this->db->from('router_details');
		$this->db->where('sap_id',$sap_id);
		$query=$this->db->get();
		return $query->result();
	}

	public function get_list_router_ip_range($loopback)
	{
		$this->db->from('router_details');
		$this->db->where_in('loopback',$loopback);
		$query=$this->db->get();
		return $query->result();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id',$id);
		$query = $this->db->get();
 
		return $query->row();
	}
 
	public function router_add($data)
	{   //print_r($data);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
 
	public function router_update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}
 
	public function delete_by_id($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->table);
	}

	public function check_hostname($hostname)
	{
		$this->db->from($this->table);
		$this->db->where('hostname',$hostname);
		$query = $this->db->get();
 
		return $query->row();
	}

	public function check_loopback($loopback)
	{
		$this->db->from($this->table);
		$this->db->where('loopback',$loopback);
		$query = $this->db->get();
 
		return $query->row();
	}
}