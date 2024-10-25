<?php

class Events_model extends CI_Model
{
	

	function getEvents()
	{
		$this->db->order_by("iId", "desc");
		return $this->db->get('events')->result();
	}

//events model
//get the count of events 
	function getCountForDatatbles($tableName)
	{
		$this->db->select('count(*) as eventCount');
		return $this->db->get($tableName)->row();
	}
	
	 /*
     * Perform the SQL queries needed for an server-side processing requested
     * @param $_POST filter data based on the posted parameters
     */
	 private function _get_datatables_query($postData, $tableName, $column_order, $column_search){
         
        $this->db->from($tableName);
        $order = array('id' => 'desc');
        $i = 0;
        // loop searchable columns 
        foreach($column_search as $item){
            // if datatable send POST for search
            if($postData['search']['value']){
                // first loop
                if($i===0){
                    // open bracket
                    $this->db->group_start();
                    $this->db->like($item, $postData['search']['value']);
                }else{
                    $this->db->or_like($item, $postData['search']['value']);
                }
                
                // last loop
                if(count($column_search) - 1 == $i){
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }
         
        if(isset($postData['order'])){
            $this->db->order_by($column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }


	/*
	 * Fetch members data from the database
	 * @param $_POST filter data based on the posted parameters
	 */
    public function getRows($postData,$tableName,  $column_order, $column_search){
        $this->_get_datatables_query($postData, $tableName,  $column_order, $column_search);
        if($postData['length'] != -1){
            $this->db->limit($postData['length'], $postData['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }
	
	
	function getEventById($id)
	{
		$where_arr = array('iId' => $id, );
		return $this->db->get_where('events',$where_arr)->row();
	}
	function getEventBySerial($s)
	{
		$where_arr = array('vBadge_serial' => $s, );
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
	
	function getSummary(){
		$this->db->limit(100);
		return $this->db->get('summary')->result();
	}

	function markAsDelete($type, $value) {
		$this->db->truncate('delete_preview_events');
		$this->db->select("e.iId as event_id");
		$this->db->from('events as e');
		if($type == 'edate'){
			$this->db->where('dCreated_at >=',  $_POST['vStart']);
			$this->db->where('dCreated_at <=',  $_POST['vEnd']);
		}else if($type == 'udate'){
			$this->db->where('dUpdated_at >=',  $_POST['vStart']);
			$this->db->where('dUpdated_at <=',  $_POST['vEnd']);
		}else if($type == 'rSerial'){
			if($value['type'] == 1){
				$this->db->where('vReader_serial =',  $_POST['rSerial']);
			}else if($value['type'] == 2){
				$this->db->where('vReader_serial >=',  $_POST['vStart']);
				$this->db->where('vReader_serial <=',  $_POST['vEnd']);
			}
		}else if($type == 'bSerial'){
			if($value['type'] == 1){
				$this->db->where('vBadge_serial =',  $_POST['rSerial']);
			}else if($value['type'] == 2){
				$this->db->where('vBadge_serial >=',  $_POST['vStart']);
				$this->db->where('vBadge_serial <=',  $_POST['vEnd']);
			}
		}
		$events = $this->db->get()->result_array();
		if(count($events)){
			return $this->db->insert_batch('delete_preview_events',$events);
		}else
			return false;
	}

	function getToBeDeleted() {
		return $this->db->select('e.*, dp.ignored')
		->from('events as e')
		->join(' delete_preview_events as dp', 'e.iId = dp.event_id')->get()->result();
	}

	function deleteSelectedEvents(){
		$sql =	"DELETE e FROM  events e
  				INNER JOIN delete_preview_events dp ON e.iId = dp.event_id
  				WHERE dp.ignored = ?";
		return $this->db->query($sql, array(0));
	}

	function updateDeletedEvent($id, $data) {
		return $this->db->where('event_id',$id)
			->update('delete_preview_events',$data);
	}

	function getHourlyEvents($start, $end, $maxEid) {
		// return $sql = "SELECT * FROM `events` WHERE `dCreated_at` BETWEEN '$start' AND '$end'";
		$sql = "
		SELECT
			`iId` as event_id,
			`vBadge_serial` as badge,
			`vReader_serial` as reader,
			`vEvent_type` as event_type,
			`tTimestamp` as tTimestamp
		FROM 
			ci_badge.`events` 
		WHERE 
			`tTimestamp` BETWEEN '". $start ."' AND '". $end ."'
		AND
			`vEvent_type` = '7'
		AND `iId` > ". $maxEid ."
		";
		// echo $sql; exit;
		$query = $this->db->query($sql);
    	return $query->result_array();
	}

	function getEventsWithManagers($start, $end, $managerId) {
		$this->db->distinct();
		return $this->db->select('
			d.`vSerial` as badge_id,
			m.`vId` as manager_id,
			m.`vFacility` as manager_name,
			m.`w1Start`,
			m.`w1End`,
			m.`w2Start`,
			m.`w2End`,
			e.`iId` as event_id,
			e.`vBadge_serial` as badge,
			e.`vReader_serial` as reader,
			e.`vEvent_type` as event_type,
			e.`tTimestamp` as event_time
		')
		->from('events as e')
		->join('devices as d', 'e.vBadge_serial = d.vSerial')
		->join('managers as m', 'd.vAssigned_Facility_ID = m.vid')
		->where("`e.tTimestamp` BETWEEN '". $start ."' AND '". $end ."'")
		->where("m.`vId` = ". $managerId )
		->get()->result_array();
	}

	public function getAllEventsWithDate($start, $end, $maxEid) {
		$this->db->distinct();
		return $this->db->select('
			d.`vSerial` as badge_id,
			m.`vId` as manager_id,
			m.`vFacility` as manager_name,
			e.`iId` as event_id,
			e.`vBadge_serial` as badge,
			e.`vReader_serial` as reader,
			e.`vEvent_type` as event_type,
			e.`tTimestamp` as event_time
		')
		->from('events as e')
		->join('devices as d', 'e.vBadge_serial = d.vSerial')
		->join('managers as m', 'd.vAssigned_Facility_ID = m.vid')
		->where("`e.tTimestamp` BETWEEN '". $start ."' AND '". $end ."'")
		->where("`e.vEvent_type` = 7")
		->where("e.`iId` > ". $maxEid)
		->get()->result_array();
	}

	public function getMaxEventId() {
		return $this->db->select('
			MAX(e.`iId`) as max_event_id,
		')->from('events as e')->get()->row();
	}

}
