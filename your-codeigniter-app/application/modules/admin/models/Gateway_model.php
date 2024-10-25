<?php
class Gateway_model extends CI_Model
{
    public function __construct(){
		parent::__construct();
		$this->table = 'gateways';
        $this->column_search = array('r.gateway_id','r.name','r.description','r.latitude','r.longitude');
		$this->column_order = array(null,'r.gateway_id','r.name','r.description');
		$this->order = array('r.id' => 'asc');
	}


    function createGateway($data){
		return $this->db->insert($this->table,$data);
	}

    public function getGatewayRows($postData){
		$this->_get_datatables_query($postData);
		if($postData['length'] != -1){
			$this->db->limit($postData['length'], $postData['start']);
		}
		$query = $this->db->get();
		return $query->result();
	}

    private function _get_datatables_query($postData){  				
		$this->db->select('r.*')
			->from($this->table.' as r');
					
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

    public function countFiltered($postData){
		$this->_get_datatables_query($postData);
		$query = $this->db->get();
		return $query->num_rows();
	}


	function getGatewayById($id){
		$this->db->where('id',$id);
		return $this->db->get($this->table)->row();
	}

	function deleteGateway($id){
		$this->db->where('id', $id);
		return $this->db->delete($this->table);
	}
}
?>