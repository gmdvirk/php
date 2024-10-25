<?php
class Readers_model extends CI_Model
{
	public function __construct(){
		parent::__construct();
		$this->table = 'readers';
		$this->column_search = array('r.vReaderSerial','r.vDescription','m.vFacility','m.vEmail');
		$this->column_order = array(null,'r.vReaderSerial','r.vDescription','m.vFacility', 'v2');
		$this->order = array('r.iId' => 'asc');
	}

	function getReaders(){
		return $this->db->get('readers')->result();
	}
	
	function createReader($data){
		return $this->db->insert('readers',$data);
	}
	
	function updateReader($iId, $data){
		$this->db->where('iId',$iId);
		return $this->db->update('readers', $data);
	}
	
	function getReaderBySerial($serial,$fId){
		$where_arr = array('vReaderSerial'=>$serial, 'vFacility'=>$fId);
		return $this->db->get_where('readers',$where_arr)->row();
	}
	function getReaderByOnlySerial($serial){
		$where_arr = array('vReaderSerial'=>$serial);
		return $this->db->join('managers as m', 'm.vId = r.vFacility')->from('readers as r')->where($where_arr)->get()->row();
	}
	
	function getReaderByFac($Fac){
		$this->db->where('vReaderSerial',$Fac);
		return $this->db->get('readers')->row();
	}

	function getReadersById($id){
		$this->db->where('iId',$id);
		return $this->db->get('readers')->row();
	}
	
	function deleteReader($iId){
		$this->db->where('iId', $iId);
		return $this->db->delete('readers');
	}

	public function getReaderRows($postData, $facility_id){
		$this->_get_datatables_query($postData, $facility_id);
		if($postData['length'] != -1){
			$this->db->limit($postData['length'], $postData['start']);
		}
		$query = $this->db->get();
		return $query->result();
	}

	private function _get_datatables_query($postData, $facility_id){  				
		$this->db->select('r.*, m.vFacility, m.vEmail')
			->from($this->table.' as r')
			->join('managers as m', 'm.vId = r.vFacility','left');
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
			$this->db->where('r.vFacility', '');
		elseif($facility_id != "ALL")
			$this->db->where('r.vFacility', $facility_id);
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

	function registerUploadedReaders($beacons){
		return $this->db->insert_batch($this->table,$beacons);
	}
}
?>
