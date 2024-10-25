<?php

class Events_model extends CI_Model
{
	

	function getEvents()
	{
		return $this->db->get('events')->result();
	}

	function getEventById($id)
	{
		$where_arr = array('iId' => $id, );
		return $this->db->get_where('events',$where_arr)->row();
	}

	function updateEvent($id,$data)
	{
		$this->db->where('iId',$id);
		return $this->db->update('events',$data);
	}

	function deleteEvent($id)
	{
		$this->db->where('iId',$id);
		return $this->db->delete('events');
	}

	function createEvent($data)
	{
		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		$this->db->insert('events',$data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			return $data->iRaw_id;
		} 
		else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			return "success";
		}
	}
	
	//Query to insert the data to database using new format
	function createEventForNewFormat($data)
	{
		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		$this->db->insert('prox',$data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			return $data->iRaw_id;
		} 
		else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			return "success";
		}		
	}

	function getHourlyEvents($eventType, $start, $end) {
		// return $sql = "SELECT * FROM `events` WHERE `dCreated_at` BETWEEN '$start' AND '$end'";
		$sql = "
		SELECT
			`iId` as event_id,
			`vBadge_serial` as badge,
			`vReader_serial` as reader,
			`vEvent_type` as event_type,
			`dCreated_at` as created_at
		FROM 
			ci_badge.`events` 
		WHERE 
			`tTimestamp` BETWEEN '". $start ."' AND '". $end ."'
		AND
			`vEvent_type` = '". $eventType ."'
		";
		// echo $sql; exit;
		$query = $this->db->query($sql);
    	return $query->result_array();
	}

}
