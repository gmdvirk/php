<?php

class Unit_model extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->table = 'units';
    }

    function getUnits(){
		return $this->db->get($this->table)->result();
	}

    function createUnit($data){
		return $this->db->insert($this->table, $data);
	}
	
	function updateUnit($id, $data){
		$this->db->where('id',$id);
		return $this->db->update($this->table, $data);
	}

    function searchUnit($where) {
        return $this->db->where($where)->get($this->table)->result();
    }

    function deleteUnitPk($id) {
        $this->db->where('id', $id);
		return $this->db->delete($this->table);
    }

    function registerUploadedUnits($units){
		return $this->db->insert_batch($this->table,$units);
	}
}

