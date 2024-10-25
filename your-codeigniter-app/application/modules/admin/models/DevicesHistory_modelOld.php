<?php 

class DevicesHistory_model extends CI_Model {

    private $table = 'devices_history';

    function createHisory($data) {
		return $this->db->insert($this->table,$data);
    }
    
    function updateHistory($id, $data){
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /** 
    * Type : 1 Users, 2 Managers 
    */
    function getDeviceActiveHistory($device_id, $user_id, $type = 1){
        $this->db->where('user_id',$user_id);
        $this->db->where('type',$type);
        $this->db->where('device_id',$device_id);
        $this->db->where('(assigned_to <= "0000-00-00 00:00:00")');
        
        return $this->db->get($this->table)->row();
    }

    function getDeviceHistory($device_id) {
        // $where_arr = array('device_id' => $device_id);
        // return $this->db->get_where($this->table,$where_arr)->result();
        $this->db->select('h.*, c.*, d.iId as device_id, d.vSerial, d.vAssigned_Facility_ID, d.vType, d.vDescription');    
        $this->db->from($this->table . ' as h');
        $this->db->join('customers as c', 'h.user_id = c.iId');
        $this->db->join('devices as d', 'h.device_id = d.iId');
        $this->db->where('h.device_id', $device_id);
        $this->db->order_by('id', 'DESC');
        return $this->db->get()->result();
	}
}
?>