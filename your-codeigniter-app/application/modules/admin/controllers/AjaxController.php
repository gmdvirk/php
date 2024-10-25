<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AjaxController extends MY_Controller {
	
	
	public function __construct()
	{
		// call to models
		$this->load->model('events_model','events');
	}
	
	//function to fetch the data for events table
	public function fetchEventForDatatables()
	{
		$limit = $_POST['length'];
        $offset = $_POST['start']; 
		$draw = $_POST['draw'];
		
		$totalRecords = $this->events->getCountForDatatbles('events'); 
		$column_order = array( 'iId','vBadge_serial', 'vReader_serial', 'iEvent_num','vEvent_type', 'dCreated_at', 'dUpdated_at');
		$column_search = array( 'iId', 'vBadge_serial', 'vReader_serial','iEvent_num','vEvent_type', 'dCreated_at', 'dUpdated_at');
		
		$datatables = $this->events->getRows($_POST, 'events', $column_order, $column_search);
		$response = array(
		  "draw" => intval($draw),
		  "iTotalRecords" => $totalRecords->eventCount,
		  "iTotalDisplayRecords" => $totalRecords->eventCount,
		  "aaData" => $datatables
		);
		 
		echo json_encode($response);
		
	}
	
	
	//function to fetch the data for prox table
	public function fetchProxForDatatables()
	{
		$limit = $_POST['length'];
        $offset = $_POST['start']; 
		$draw = $_POST['draw'];
		
		$totalRecords = $this->events->getCountForDatatbles('prox'); 
		$column_order = array( 'id', 'time_stamp', 'peer_id', 'event_type', 'badge_serial', 'created_at', 'updated_at');
		$column_search = array( 'id', 'time_stamp', 'peer_id', 'event_type', 'badge_serial', 'created_at', 'updated_at');
		
		$datatables = $this->events->getRows($_POST, 'prox', $column_order, $column_search);
		$response = array(
		  "draw" => intval($draw),
		  "iTotalRecords" => $totalRecords->eventCount,
		  "iTotalDisplayRecords" => $totalRecords->eventCount,
		  "aaData" => $datatables
		);
		 
		echo json_encode($response);
		
	}

}

?>