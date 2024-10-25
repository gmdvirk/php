<?php

class Devices_model extends CI_Model
{
	public function __construct(){
		parent::__construct();
		$this->table = 'devices';
		$this->column_search = array('d.vSerial','d.vType','d.vDescription','d.vCreatedById','m.vFacility','m.vEmail');
		$this->column_order = array(null,'d.vSerial','d.vType','d.vDescription','d.vCreatedById','m.vFacility','m.vEmail');
		$this->order = array('d.iId' => 'asc');
	}

	function getDevices(){
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
		$where_arr = array('vSerial' => $Serial, );
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

	public function getDeviceRows($postData, $facility_id){
		$this->_get_datatables_query($postData, $facility_id);
		if($postData['length'] != -1){
			$this->db->limit($postData['length'], $postData['start']);
		}
		$query = $this->db->get();
		return $query->result();
	}

	private function _get_datatables_query($postData, $facility_id){  				
		$this->db->select('d.*, m.vFacility, m.vEmail')
			->from($this->table.' as d')
			->join('managers as m', 'm.vId = d.vAssigned_Facility_ID','left');
					
		$i = 0;
		foreach($this->column_search as $item){
			if($postData['search']['value']){
				if($i===0){
					$this->db->group_start();
					$this->db->like($item, $postData['search']['value']);
				}else{
					$this->db->or_like($item, $postData['search']['value']);
				}
				if(count($this->column_search) - 1 == $i){
					$this->db->group_end();
				}
			}
			$i++;
		}

		if($facility_id == 'NONE')
			$this->db->where('vAssigned_Facility_ID', '');
		elseif($facility_id != "ALL")
			$this->db->where('vAssigned_Facility_ID', $facility_id);
		if(isset($postData['order'])){
			$this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
		}else if(isset($this->order)){
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	public function countAll(){
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function countFiltered($postData, $facility_id){
		$this->_get_datatables_query($postData, $facility_id);
		$query = $this->db->get();
		return $query->num_rows();
	}
}
