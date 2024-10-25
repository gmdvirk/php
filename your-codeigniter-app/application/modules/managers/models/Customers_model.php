<?php

class Customers_model extends CI_Model
{
	

	function getCustomers($id)
	{
		$this->db->where('vAssigned_Facility_ID',$id);
		$this->db->order_by("iId", "desc");
		return $this->db->get('customers')->result();
	}

	function changeStatus($id,$status)
	{
		$this->db->where('iId',$id);
		$data=array('eStatus'=>$status);
		return $this->db->update('customers',$data);
	}

	function getCustomerBySerial($serial)
	{
		$where_arr = array('vSerial' => $serial, );
		return $this->db->get_where('customers',$where_arr)->row();
	}
	
	function getCustomerById($id)
	{
		$where_arr = array('iId' => $id, );
		return $this->db->get_where('customers',$where_arr)->row();
	}
	
	function getCustomerInfo($id)
	{
		$where_arr = array('iId' => $id, );
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

	function getCustomerByFacility($facility_id) {
		$where_arr = array('vAssigned_Facility_ID' => $facility_id);
		return $this->db->get_where('customers',$where_arr)->result();
	}

}
