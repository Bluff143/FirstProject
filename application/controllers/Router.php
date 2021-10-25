<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//This is the Controller for codeigniter crud using ajax application.
class Router extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('router_model');
		date_default_timezone_set("Asia/Calcutta");
	}
	public function index()
	{
		$data['routers']=$this->router_model->get_all_routers();
		
		$this->load->view('router/router',$data);
	}
	public function list_router_based_type()
	{
		$jwt = new JWT();
		$JwtSecretKey = 'Mysecretwordshere';

		$arr = array('AG1','CSS');

		$sap_id = $this->input->post('sap_id');

		if(in_array($sap_id,$arr)) 
		{
			$iat = time(); // current timestamp value
            $nbf = $iat + 10;
            $exp = $iat + 3600;

            $payload = array(
                "iss" => "The_claim",
                "aud" => "The_Aud",
                "iat" => $iat, // issued at
                "nbf" => $nbf, //not before in seconds
                "exp" => $exp, // expire time in seconds
                "data" => $sap_id,
            );

			$token = $jwt->encode($payload,$JwtSecretKey,'HS256');

			$data_router=$this->router_model->get_list_router_based_type($sap_id);
			echo json_encode(array("token" => $token,"message" => $data_router));
		} else {
			echo json_encode(array("message" => "Please enter only AG1 or CSS"));
		}
		
	}
	public function list_router_ip_range()
	{
		$jwt = new JWT();
		$JwtSecretKey = 'Mysecretwordshere';

		$loopback = $this->input->post('loopback');

		$iat = time(); // current timestamp value
        $nbf = $iat + 10;
        $exp = $iat + 3600;

        $payload = array(
            "iss" => "The_claim",
            "aud" => "The_Aud",
            "iat" => $iat, // issued at
            "nbf" => $nbf, //not before in seconds
            "exp" => $exp, // expire time in seconds
            "data" => $loopback,
        );

		$token = $jwt->encode($payload,$JwtSecretKey,'HS256');

		$data_router=$this->router_model->get_list_router_ip_range($loopback);
		echo json_encode(array("token" => $token,"message" => $data_router));
	}
	public function router_add()
	{
		$data = array(
				'sap_id' => $this->input->post('sap_id'),
				'hostname' => $this->input->post('hostname'),
				'loopback' => $this->input->post('loopback'),
				'mac_address' => $this->input->post('mac_address'),
				'created_at' => date('Y-m-d H:i:s'),
			);
		$insert = $this->router_model->router_add($data);
		echo json_encode(array("status" => TRUE));
	}
	public function add_with_api()
	{
		$jwt = new JWT();
		$JwtSecretKey = 'Mysecretwordshere';

		$hostname = $this->input->post('hostname');
		$loopback = $this->input->post('loopback');

		$check_hostname = $this->router_model->check_hostname($hostname);
		$check_loopback = $this->router_model->check_loopback($loopback);

		if(empty($check_hostname)) 
		{
			if(empty($check_loopback)) 
			{
				$data = array(
					'sap_id' => $this->input->post('sap_id'),
					'hostname' => $this->input->post('hostname'),
					'loopback' => $this->input->post('loopback'),
					'mac_address' => $this->input->post('mac_address'),
					'created_at' => date('Y-m-d H:i:s'),
				);
				$insert = $this->router_model->router_add($data);

				$iat = time(); // current timestamp value
	            $nbf = $iat + 10;
	            $exp = $iat + 3600;

	            $payload = array(
	                "iss" => "The_claim",
	                "aud" => "The_Aud",
	                "iat" => $iat, // issued at
	                "nbf" => $nbf, //not before in seconds
	                "exp" => $exp, // expire time in seconds
	                "data" => $data,
	            );

				$token = $jwt->encode($payload,$JwtSecretKey,'HS256');

				echo json_encode(array("token" => $token,"status" => TRUE));
			}
			else
			{
				echo json_encode(array("message" => "Loopback $loopback already exist in the system"));
			}
		}
		else
		{
			echo json_encode(array("message" => "Hostname $hostname already exist in the system"));
		}
	}
	public function ajax_edit($id)
	{
		$data = $this->router_model->get_by_id($id);
		echo json_encode($data);
	}
	
	public function router_update()
	{
		$data = array(
				'sap_id' => $this->input->post('sap_id'),
				'hostname' => $this->input->post('hostname'),
				'loopback' => $this->input->post('loopback'),
				'mac_address' => $this->input->post('mac_address'),
				'updated_at' => date('Y-m-d H:i:s'),
			);
		$this->router_model->router_update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}
 
 	public function update_router_based_ip()
	{
		$jwt = new JWT();
		$JwtSecretKey = 'Mysecretwordshere';

		$loopback = $this->input->post('loopback');
		$check_loopback = $this->router_model->check_loopback($loopback);

		if(!empty($check_loopback))
		{
			$data = array(
					'sap_id' => $this->input->post('sap_id'),
					'hostname' => $this->input->post('hostname'),
					'mac_address' => $this->input->post('mac_address'),
					'updated_at' => date('Y-m-d H:i:s'),
				);
			$this->router_model->router_update(array('loopback' => $loopback), $data);

			$iat = time(); // current timestamp value
            $nbf = $iat + 10;
            $exp = $iat + 3600;

            $payload = array(
                "iss" => "The_claim",
                "aud" => "The_Aud",
                "iat" => $iat, // issued at
                "nbf" => $nbf, //not before in seconds
                "exp" => $exp, // expire time in seconds
                "data" => $data,
            );

			$token = $jwt->encode($payload,$JwtSecretKey,'HS256');
				
			echo json_encode(array("token" => $token,"message" => "Data updated successfully"));
		}
		else
		{
			echo json_encode(array("message" => "Loopback $loopback does not exist in the system"));
		}
	}

	public function router_delete($id)
	{
		$data = array(
				'router_status' => 0,
				'updated_at' => date('Y-m-d H:i:s'),
			);
		$this->router_model->router_update(array('id' => $id), $data);
		echo json_encode(array("status" => TRUE));

		//$this->router_model->delete_by_id($id);
		//echo json_encode(array("status" => TRUE));
	}
 	
 	public function delete_record_based_ip()
	{
		$jwt = new JWT();
		$JwtSecretKey = 'Mysecretwordshere';
		
		$loopback = $this->input->post('loopback');
		$check_loopback = $this->router_model->check_loopback($loopback);

		if(!empty($check_loopback))
		{
			$data = array(
					'router_status' => 0,
					'updated_at' => date('Y-m-d H:i:s'),
				);

			$iat = time(); // current timestamp value
            $nbf = $iat + 10;
            $exp = $iat + 3600;

            $payload = array(
                "iss" => "The_claim",
                "aud" => "The_Aud",
                "iat" => $iat, // issued at
                "nbf" => $nbf, //not before in seconds
                "exp" => $exp, // expire time in seconds
                "data" => $data,
            );

			$token = $jwt->encode($payload,$JwtSecretKey,'HS256');
			$this->router_model->router_update(array('loopback' => $loopback), $data);
			echo json_encode(array("token" => $token,"message" => "Deleted successfully"));
		}
		else
		{
			echo json_encode(array("message" => "Loopback $loopback does not exist in the system"));
		}
		
		//$this->router_model->delete_by_id($id);
		//echo json_encode(array("status" => TRUE));
	}
}