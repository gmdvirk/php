<?php

class Customers_model extends CI_Model
{
	
	function verify()
	{	
		$vEmail=$this->input->post('vEmail');
		$vPassword=$this->input->post('vPassword');

		$where_arr= array('vEmail' => $vEmail,'vPassword'=>md5($vPassword),'eStatus'=>'1' );
		$this->db->where($where_arr);

		return $this->db->get('customers')->row();

	}

	function register()
	{
		$data = array(
				'vFirstName' => $this->input->post('vFirstName'),
				'vLastName' => $this->input->post('vLastName'),
				'eGender' => $this->input->post('eGender'),
				'vEmail' => $this->input->post('vEmail'),
				'vPassword' => md5($this->input->post('vPassword')),
				'eStatus'=>'1',
				);
		return $this->db->insert('customers',$data);
	}

	

	

	function getCustomerById($id)
	{
		$this->db->where('iId',$id);
		return $this->db->get('customers')->row();
	}

	

}
