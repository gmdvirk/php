<?php
class Invite_model extends CI_Model
{
	
	public function invited($email,$facility){
		date_default_timezone_set('Asia/Kolkata');
		$time = date("Y-m-d h:i:s");
		$data = array(
			'vEmail' => $email,
			'vFacility' => $facility,
			'vTime' => $time,
			'eStatus' => 'Pending'
		);
		return $this->db->insert('invited',$data);
	}
	public function getInvited(){
		$this->db->order_by("vId", "desc");
		return $this->db->get('invited')->result();
	}

	public function updateDomain($data){
		$this->db->where('domain !=', '');
		$setting =  $this->db->get('global_settings')->row();
		if($setting)
			return $this->db->update('global_settings',$data);
		else
			return $this->db->insert('global_settings',$data);
	}

	public function getDomain(){
		$this->db->where('domain !=', '');
		$setting =  $this->db->get('global_settings')->row();
		if($setting)
			return $setting->domain;
		else
			return false;
	}
}
?>
