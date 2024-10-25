<?php
class Managers_model extends CI_Model
{
	

	function getManagers() {
		$this->db->order_by("vId", "desc");
		$this->db->where('eStatus','1');
		return $this->db->get('managers')->result();
	}
	
	function getDeletedManagers() {
		$this->db->order_by("vId", "desc");
		$this->db->where('eStatus','0');
		return $this->db->get('managers')->result();
	}
	
	function deleteManager($id) {
		$data=array('eStatus'=>'0');
		$this->db->where('vId',$id);
		return $this->db->update('managers',$data);
	}
	
	function createManager($data){
		return $this->db->insert('managers',$data);
	}
		
	function updateManager($id,$data) {
		$this->db->where('vId',$id);
		return $this->db->update('managers',$data);
	}
		
	function getManagerById($id) {
		$this->db->where('vId',$id);
		return $this->db->get('managers')->row();
    }
	
	function getJsonSettingByFacilityWithLimit($facility_id, $start, $limit) {
        $this->db->select('vSerial as "badge_serial", vJsonString as "json_settings"');
        $this->db->where(['vAssigned_Facility_ID' => $facility_id]);
        $this->db->where('vSerial != ""');
		$this->db->where('vJsonString IS NOT NULL AND vJsonString != ""');
		if(is_numeric($start) && is_numeric($limit))
        	$this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
        //  echo $this->db->last_query(); die;
    }
}
?>
