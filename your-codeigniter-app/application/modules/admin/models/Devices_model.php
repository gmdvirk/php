<?php

class Devices_model extends CI_Model
{
	public function __construct(){
		parent::__construct();
		$this->table = 'devices';
		$this->column_search = array('d.vSerial','d.vType','d.vDescription','d.vCreatedById','m.vFacility','m.vEmail');
		$this->column_order = array(null,'d.vSerial','d.vType','m.vFacility','d.vCreatedById');
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
	function markAsDelete() {
		$this->db->truncate('delete_preview_device');
		$this->db->select("d.iId as device_id, d.vSerial as vSerial");
		$this->db->from($this->table.' as d');
		$this->db->join('devices_history as dh', 'dh.vSerial = d.vSerial', 'LEFT');
		$this->db->where('dh.vSerial IS NULL');
		$this->db->where('d.vSerial != "DEF"');
		$devices = $this->db->get()->result_array();
		if(count($devices)){
			return $this->db->insert_batch('delete_preview_device',$devices);
		}else
			return false;
	}

	function getToBeDeleted() {
		return $this->db->select('d.*, dp.ignored')
		->from($this->table.' as d')
		->join('delete_preview_device as dp', 'd.iId = dp.device_id')->get()->result();
	}

	function updateDeletedDevice($id, $data) {
		return $this->db->where('device_id',$id)
			->update('delete_preview_device',$data);
	}

	function deleteSelectedDevices(){
		$sql =	"DELETE d FROM  devices d
  				INNER JOIN delete_preview_device dp ON d.iId = dp.device_id
  				WHERE dp.ignored = ?";
		return $this->db->query($sql, array(0));
	}

	function registerUploadedDevices($users){
		return $this->db->insert_batch($this->table,$users);
	}

}
