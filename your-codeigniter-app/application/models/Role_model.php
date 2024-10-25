<?php

class Role_model extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->table = 'roles';
    }

    function getRoles(){
		return $this->db->get($this->table)->result();
	}

    function createRole($data){
		return $this->db->insert($this->table, $data);
	}
	
	function updateRole($id, $data){
		$this->db->where('id',$id);
		return $this->db->update($this->table, $data);
	}

    function searchRole($where) {
        return $this->db->where($where)->get($this->table)->result();
    }

    function deleteRolePk($id) {
        $this->db->where('id', $id);
		return $this->db->delete($this->table);
    }

    function registerUploadedRoles($roles){
		return $this->db->insert_batch($this->table,$roles);
	}
}

