<?php

class Userlogs_model extends CI_Model{
	function getLogs(){
		$t1='ul';
		$t2='customers';
		$get = [
			$t1.'.action',
			$t1.'.action_datetime',
			$t1.'.extra_details',
			$t2.'.vFirstName',
			$t2.'.vLastName',    
		];
		$this->db->select($get);
		$this->db->from('user_logs as ul');
		$this->db->join($t2, $t1.'.customer_id = ' . $t2.'.iId', 'left');
		return $this->db->get()->result();
	}
}