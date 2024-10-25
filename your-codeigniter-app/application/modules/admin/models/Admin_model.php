<?php

class Admin_model extends CI_Model
{
	
	function verify()
	{	
		$vEmail=$this->input->post('vEmail');
		$vPassword=$this->input->post('vPassword');

		$where_arr= array('vEmail' => $vEmail,'vPassword'=>md5($vPassword) );
		$this->db->where($where_arr);
		return $this->db->get('admin')->row();

	}

	function save_settings()
	{
		$userdata= array('vFirstName' =>$this->input->post('vFirstName') ,'vLastName'=>$this->input->post('vLastName'),'vEmail'=>$this->input->post('vEmail') );
		if($this->input->post('vPassword')!='' && $_POST['vPassword'] == $_POST['vConfirmPassword'])
		{
			$userdata['vPassword'] = md5($this->input->post('vPassword'));
		}
		
		$this->db->where('iId',$this->session->userdata('iId'));
		$this->db->update('admin',$userdata);
		$this->db->where('iId',$this->session->userdata('iId'));
		return $this->db->get('admin')->row();
	}

	function getTwitterSettings()
	{
		$this->db->where('iId',1);
		return $this->db->get('twitter_settings')->row();
	}

	function getAccountSettings()
	{
		$this->db->where('iId',$this->session->userdata('iId'));
		return $this->db->get('admin')->row();
	}

}
