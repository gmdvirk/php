<?php

class Managers_model extends CI_Model
{
	
	function verify()
	{	
		$vEmail=$this->input->post('vEmail');
		$Password=$this->input->post('vPassword');
		$vPassword=md5($Password);
		$where_arr= array('vEmail' => $vEmail,'vPassword'=>$vPassword,'eStatus'=>'1' );
		$this->db->where($where_arr);

		return $this->db->get('managers')->row();

	}

	function register()
	{
		$data = array(
				'vFirstName' => $this->input->post('vFirst'),
				'vLastName' => $this->input->post('vLast'),
				'vEmail' => $this->input->post('vEmail'),
				'vFacility'=>$this->input->post('vFacility'),
				'vPassword' => md5($this->input->post('vPassword')),
				'eStatus'=>'1',
				);
		return $this->db->insert('managers',$data);
	}

	function register_verify(){
		$where_arr= array('vEmail'=>$this->input->post('vEmail'),'vPassword' => md5($this->input->post('vPassword')));
		$this->db->where($where_arr);
		return $this->db->get('managers')->row();
		}

	

	function getCustomerById($id)
	{
		$this->db->where('iId',$id);
		return $this->db->get('customers')->row();
	}

	function delete_invite($email){
		$where_arr = array('vEmail' => $email, );
		if($this->db->get_where('invited',$where_arr)->row()>=1){
		$this->db->where('vEmail',$email);
		return $this->db->delete('invited');
		}
		return true;
		}
	
	function get_manager(){
		$where_arr= array('vEmail'=>$this->input->post('vEmail'));
		$this->db->where($where_arr);
		return $this->db->get('managers')->row();
		}
		
	function updateManager($email,$data)
	{
		$this->db->where('vEmail',$email);
		return $this->db->update('managers',$data);
	}

}
