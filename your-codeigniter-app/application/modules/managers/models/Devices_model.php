<?php

class Devices_model extends CI_Model
{
	

	function getDevices($id)
	{
		$this->db->where('vAssigned_Facility_ID',$id);
		$this->db->order_by("iId", "desc");
		return $this->db->get('devices')->result();
	}

	function changeStatus($id,$status)
	{
		$this->db->where('iId',$id);
		$data=array('eStatus'=>$status);
		return $this->db->update('devices',$data);
	}

	function getDeviceById($id)
	{
		$where_arr = array('iId' => $id, );
		return $this->db->get_where('devices',$where_arr)->row();
	}
	
	function getDeviceBySerial($Serial)
	{
		$where_arr = array('vSerial' => $Serial);
		return $this->db->get_where('devices',$where_arr)->row();
	}

	function updateDevice($id,$data)
	{
		$this->db->where('iId',$id);
		return $this->db->update('devices',$data);
	}
	
	function getUpdateSerial($serial, $id)
	{
		$this->db->where('vSerial',$serial);
		$this->db->where_not_in('iId',$id);
		return $this->db->get('devices')->row();
	}
		
	function deleteDevice($id)
	{
		$this->db->where('iId',$id);
		return $this->db->delete('devices');
	}

	function createDevice($data)
	{
		return ($this->db->insert('devices', $data))  ?   $this->db->insert_id()  :   false;
		// return $this->db->insert('devices',$data);
	}

}
