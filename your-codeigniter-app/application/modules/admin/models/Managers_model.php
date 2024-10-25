<?php
class Managers_model extends CI_Model
{
	

	function getManagers()
	{
		$this->db->order_by("vId", "desc");
		$this->db->where('eStatus','1');
		return $this->db->get('managers')->result();
	}
	
	function getDeletedManagers()
	{
		$this->db->order_by("vId", "desc");
		$this->db->where('eStatus','0');
		return $this->db->get('managers')->result();
	}
	
	function deleteManager($id){
		$data=array('eStatus'=>'0');
		$this->db->where('vId',$id);
		return $this->db->update('managers',$data);
	}
	
	function createManager($data){
		return $this->db->insert('managers',$data);
		}
		
	function updateManager($id,$data){
		if($data['firmware_file']){
			$data['firmware_file'] = $this->db->escape_str($data['firmware_file']);
		}
		$this->db->where('vId',$id);
		return $this->db->update('managers',$data);
	}
		
	function getManagerById($id)
	{
		$this->db->where('vId',$id);
		return $this->db->get('managers')->row();
		
	}
}
?>
