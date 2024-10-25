<?php 

class DevicesHistory_model extends CI_Model {

    public function __construct(){
		parent::__construct();
		$this->table = 'devices_history';
		$this->column_search = array('d.vSerial','c.vFirstName','c.vLastName','c.vRole');
		$this->column_order = array(null,'d.vSerial','c.vFirstName','c.vLastName','c.vRole');
		$this->order = array('h.id' => 'DESC');
	}

    function createHisory($data) {
		// if (!array_key_exists("assigned_to",$data)){
		// 	$data['assigned_to'] = '1970-01-01 00:00:01';
		// }
		return $this->db->insert($this->table,$data);
    }
    
    function updateHistory($id, $data){
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /** 
    * Type : 1 Users, 2 Managers 
    */
    function getDeviceActiveHistory($vSerial, $user_id, $type = 1){
		if($vSerial == '' || $vSerial == NULL){
			return false;
		}
        $this->db->where('user_id',$user_id);
        $this->db->where('type',$type);
        $this->db->where('vSerial',$vSerial);
        $this->db->where('(assigned_to <= "1970-01-01 00:00:01" OR assigned_to IS NULL)');
        
        return $this->db->get($this->table)->row();
    }

    function getDeviceHistory($vSerial) {
        $this->db->select('h.*, c.*, d.iId as device_id, d.vSerial, d.vAssigned_Facility_ID, d.vType, d.vDescription');    
        $this->db->from($this->table . ' as h');
        $this->db->join('customers as c', 'h.user_id = c.iId');
        $this->db->join('devices as d', 'h.vSerial = d.vSerial');
        $this->db->where('h.vSerial', $vSerial);
        $this->db->order_by('id', 'DESC');
        return $this->db->get()->result();
    }
    
    public function getDeviceHistoryRows($postData, $vSerial, $byCustomer = false){
		// if by customer is true vSerial is customer iId
		$this->_get_datatables_query($postData, $vSerial, $byCustomer);
		if($postData['length'] != -1){
			$this->db->limit($postData['length'], $postData['start']);
		}
		$query = $this->db->get();
		return $query->result();
	}

	private function _get_datatables_query($postData, $vSerial, $byCustomer){  				
		$this->db->select('h.*, c.*, d.iId as device_id, d.vSerial, d.vAssigned_Facility_ID, d.vType, d.vDescription')
            ->from($this->table . ' as h')
			->join('customers as c', 'h.user_id = c.iId')
            ->join('devices as d', 'h.vSerial = d.vSerial');
					
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

		if($byCustomer){
			$this->db->where('h.user_id', $vSerial);
			$this->db->where('h.type', '1');
		}else {
			$this->db->where('h.vSerial', $vSerial);
		}
		if(isset($postData['order'])){
			$this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
		}else if(isset($this->order)){
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	public function countAll($vSerial){
        $this->db->from($this->table);
        $this->db->where('vSerial', $vSerial);
		return $this->db->count_all_results();
	}

	public function countFiltered($postData, $vSerial, $byCustomer = false){
		$this->_get_datatables_query($postData, $vSerial, $byCustomer);
		$query = $this->db->get();
		return $query->num_rows();
	}
}
?>