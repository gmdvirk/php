<?php

class Customers_model extends CI_Model
{

	public function __construct(){
		parent::__construct();
		$this->table = 'customers';
		$this->column_search = array('c.vFirstName','c.vLastName','c.vRole','c.vUnit','c.vSerial','c.vEmail','m.vFacility');
		$this->column_order = array(null,'c.vFirstName','c.vLastName','c.vEmail','c.vRole','c.vUnit','m.vFacility','c.vSerial');
		$this->order = array('iId' => 'asc');
	}

	function getCustomers()
	{
		$this->db->order_by("iId", "desc");
		return $this->db->get('customers')->result();
	}

	function changeStatus($id,$status)
	{
		$this->db->where('iId',$id);
		$data=array('eStatus'=>$status);
		return $this->db->update('customers',$data);
	}

	function getCustomerById($id)
	{
		$where_arr = array('iId' => $id, );
		return $this->db->get_where('customers',$where_arr)->row();
	}
	function getCustomerBySerial($serial)
	{
		$where_arr = array('vSerial' => $serial);
		return $this->db->get_where('customers',$where_arr)->row();
	}
	
	function getCustomerInfo($serial)
	{
		$where_arr = array('vSerial' => $serial);
		return $this->db->get_where('customers',$where_arr)->row_array();
	}
    
	function updateCustomer($id,$data)
	{
		$this->db->where('iId',$id);
		return $this->db->update('customers',$data);
	}
	function getUpdateSerial($serial,$id)
	{
		$this->db->where('vSerial',$serial);
		$this->db->where_not_in('iId',$id);
		return $this->db->get('customers')->row();
	}

	function deleteCustomer($id)
	{
		$this->db->where('iId',$id);
		return $this->db->delete('customers');
	}

	function createCustomer($data)
	{
		$this->db->insert('customers',$data);
   		$insert_id = $this->db->insert_id();
   		return  $insert_id;
	}

	function BadgeSerial($facility_id, $vSerial = NULL) {
		
		$selected='selected';
		if($vSerial!=NULL){
			$selected='';
		}
		$this->db->where('vAssigned_Facility_ID', $facility_id);
		$this->db->order_by('vSerial', 'ASC');
		$query = $this->db->get('devices');
		$output = "<option value='unassign' ".$selected.">-No Badge Serial-</option>";
		foreach($query->result() as $row) {
			$require='';
			if($vSerial == $row->vSerial){
				$require='selected';
			}
			$output .= '<option value="'.$row->vSerial.'"'.$require.'>'.$row->vSerial.'</option>';
		}
		return $output;
	}
	
	function getCustomerByFacility($facility_id) {
		$where_arr = array('vAssigned_Facility_ID' => $facility_id);
		return $this->db->get_where('customers',$where_arr)->result();
	}

	public function getCustomerRows($postData, $facility_id, $hideUnassignedUsers=false){
		$this->_get_datatables_query($postData, $facility_id, $hideUnassignedUsers);
		if($postData['length'] != -1){
			$this->db->limit($postData['length'], $postData['start']);
		}
		$query = $this->db->get();
		return $query->result();
	}

	private function _get_datatables_query($postData, $facility_id, $hideUnassignedUsers){
		$this->db->select('c.*, m.vFacility')
			->from($this->table.' as c')
			->join('managers as m', 'm.vId = c.vAssigned_Facility_ID','left');
					
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
		if($hideUnassignedUsers != 'false' || $hideUnassignedUsers == false) {
			$this->db->where("vSerial !=", "");
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

	public function countFiltered($postData, $facility_id, $hideUnassignedUsers = false){
		$this->_get_datatables_query($postData, $facility_id, $hideUnassignedUsers);
		$query = $this->db->get();
		return $query->num_rows();
	}

	function registerUploadedUsers($users){
		return $this->db->insert_batch($this->table,$users);
	}

	function getAllRoles() {
		$this->db->distinct('c.vRole');
		$this->db->select('c.vRole');
		$this->db->from($this->table.' as c');
		$this->db->order_by("c.vRole", "asc");
		return $data = $this->db->get()->result();
	}

	function getAllUnits() {
		$this->db->distinct('c.vUnit');
		$this->db->select('c.vUnit');
		$this->db->from($this->table.' as c');
		$this->db->order_by("c.vUnit", "asc");
		return $data = $this->db->get()->result();
	}

	function markAsDelete($type, $value) {
		$this->db->truncate('delete_preview');
		$this->db->select("'".$type."' as delete_by, c.iId as delete_id");
		$this->db->from($this->table.' as c');
		$this->db->join('devices_history as dh', 'dh.user_id = c.iId', 'left');
		$this->db->where('dh.id IS NULL');
		if($type == 'role'){
			$this->db->where('vRole',$value);
		}else if($type == 'unit'){
			$this->db->where('vUnit',$value);
		}else if($type == 'date_added'){
			$this->db->where('vStart >=',  $_POST['vStart']);
			$this->db->where('vStart <=',  $_POST['vEnd']);
		}
		$users = $this->db->get()->result_array();
		if(count($users)){
			return $this->db->insert_batch('delete_preview',$users);
		}else
			return false;
	}

	function getToBeDeleted() {
		return $this->db->select('c.*, dp.ignored')
		->from($this->table.' as c')
		->join('delete_preview as dp', 'c.iId = dp.delete_id')->get()->result();
	}

	function updateDeletedUser($id, $data) {
		return $this->db->where('delete_id',$id)
			->update('delete_preview',$data);
	}

	function deleteSelectedUsers(){
		$sql =	"DELETE c FROM  customers c
  				INNER JOIN delete_preview dp ON c.iId = dp.delete_id
  				WHERE dp.ignored = ?";
		return $this->db->query($sql, array(0));
	}

	function getAllCustomers() {
		$this->db->select('vFirstName, vLastName, vRole, vUnit, vStart, vEnd');
		$this->db->order_by("iId", "desc");
		return $this->db->get('customers')->result_array();
	}
}


