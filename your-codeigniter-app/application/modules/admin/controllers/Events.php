<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends MY_Controller {

	protected $isAdmin;

	public function __construct()
	{
		// call to models
		$this->load->model('events_model','events');
		$this->load->model('devices_model','devices');
		$this->load->model('Customers_model','users');
		$this->load->model('Readers_model','reader');
		$this->load->model('HourlyEvents_model','hourlyevents');
		if(!$this->session->userdata('isAdmin'))
		{
			redirect('admin/login');
		}
		$this->isAdmin=$this->session->userdata('isAdmin');
		if(!$this->isAdmin)
		{
			redirect('admin/login');
		}
	}

	public function index()
	{
		$this->baseloader->adminviews('events', ['pageTitle'=>'Events']);
	}

	function create()
	{
		$data=array('pageTitle'=>'Add Event','action'=>base_url("admin/events/createEvent"));
		$this->baseloader->adminviews('editevent',$data);
	}

	function createEvent(){
			return "";
	}
	function editEvent($id)
	{
		$events=$this->events->getEventById($id);

		$data=array('events'=>$events,'pageTitle'=>'Edit Event','action'=>"admin/events/updateEvent/$id");
		$this->baseloader->adminviews('editEvent',$data);

	}
	
	function updateEvent($id)
	{	
		$data=$_POST;
		$result=$this->customer->updateEvent($id,$data);
		if($result)
		{
			$this->session->set_flashdata('success','Event updated successfully');
			redirect('admin/events');
		}
	}

	function deleteEvent($id)
	{
		$delete=$this->events->deleteEvent($id);
		if($delete)
		{
			$this->session->set_flashdata('success','Event deleted successfully');
			redirect('admin/events');
		}
	}

	function bulkDelete($type) {
		$d = [];
		$last_selected = '';
		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			if($_POST['bulkDeleteBy'] == 'edate'){
				$last_selected = ['start' =>  $_POST['vStart'] , 'end' =>  $_POST['vEnd']];
				$result = $this->events->markAsDelete('edate',$last_selected);
			}else if($_POST['bulkDeleteBy'] == 'udate') {
				$last_selected = ['start' =>  $_POST['vStart'] , 'end' =>  $_POST['vEnd']];
				$result = $this->events->markAsDelete('udate',$last_selected);
			}else{
				$t = explode('?',$_POST['bulkDeleteBy'])[0];
				// var_dump($_POST);exit;
				if($t == 'rserial'){
					$type = $_POST['rserialType'];
					if($_POST['rSerial']){
						$last_selected = ['type' =>  1 , 'serial' =>  $_POST['rSerial']];
						$result = $this->events->markAsDelete('rSerial',$last_selected);
					}else{
						$last_selected = ['type' => 2 ,'start' =>  $_POST['vStart'] , 'end' =>  $_POST['vEnd']];
						$result = $this->events->markAsDelete('rSerial',$last_selected);
					}
				}else{
					$type = $_POST['bserialType'];
					if($_POST['rSerial']){
						$last_selected = ['type' =>  1 , 'serial' =>  $_POST['rSerial']];
						$result = $this->events->markAsDelete('bSerial',$last_selected);
					}else{
						$last_selected = ['type' => 2 ,'start' =>  $_POST['vStart'] , 'end' =>  $_POST['vEnd']];
						$result = $this->events->markAsDelete('bSerial',$last_selected);
					}
				}
			}
			if($result){
				redirect('admin/events/tobedelted/');
			}else{
				$this->session->set_flashdata('error','No record found.');
			}
		}
		
		// if($type == 'rserial' || $type == 'bserial'){
		// 	$type = $type.'?type='.$_GET['type'];
		// }
		$data = array(
			'pageTitle'=>'Bulk Delete Events',
			'action'=>"admin/events/bulkDelete/".$type,
			'type' => $type,
			'data' => $d,
			'lastselected' => $last_selected
		);
		$this->baseloader->adminviews('bulk_delete_events',$data);
	}

	function tobedelted(){
		$events = $this->events->getToBeDeleted();
		$data = array(
			'pageTitle'=>'Bulk Delete Events',
			'action'=>"admin/events/bulkDeleteConfirm/",
			'events' => $events,
		);
		$this->baseloader->adminviews('bulk_delete_events_list',$data);
	}
	
	public function deleteevents() {
		if($this->events->deleteSelectedEvents())
			$this->session->set_flashdata('success','Records deleted successfully');
		else
			$this->session->set_flashdata('error','Something went wrong.');

		redirect('admin/events');
	}

	public function ignoredeletedevent(){
		$event_id = $this->input->post('event');
		$ignore = $this->input->post('ignore');
		if($this->events->updateDeletedEvent($event_id, ['ignored' => $ignore]))
			$response['success'] = true;
		else
			$response['success'] = false;
		echo json_encode($response);
	}

	public function createhourlyevents(){
		// echo date_default_timezone_get(); exit;
		$date = date('Y-m-d');
		$hour =  date('H')-1;
		$start = $date . " " . $hour . ":00:00";
		$end = $date . " " . $hour . ":59:59";
		// $events = $this->events->getHourlyEvents('7', '2022-08-02 03:00:01', '2022-08-02 03:59:59');
		$events = $this->events->getHourlyEvents('7', $start, $end);
		// echo '<pre>';
		if(!empty($events)) {
			$newEventArray = [];
			foreach($events as $event){
				$newEventArray[$event['reader']][$event['badge']][] = $event;
			}
			// var_dump($newEventArray);exit;
			$newEventArray2 = [];
			foreach($newEventArray as $sKey => $readerBadges){
				$array = [];
				$array['reader_serial'] = $sKey;
				// $array['badges'] = $readerBadges;
				

				foreach($readerBadges as $bKey => $bEvents){
					$eee = [];
					$eee['badge_serial'] = $bKey;
					$eee['visits'] = $bEvents;
					$visits = [];
					$x = [];
					foreach($bEvents as $i => $evt) {
						$i++;
						if($i%2 != 0){
							$x['entry'] = $evt['created_at'];
						}else{
							$x['exit'] = $evt['created_at'];
						}

						if($i%2 == 0){
							$date_a = new DateTime($x['entry']);
							$date_b = new DateTime($x['exit']);
							$diff = $date_b->getTimestamp() - $date_a->getTimestamp();
							// $interval = date_diff($date_a,$date_b);

							$x['time_spend'] = $diff; //$interval->format('%h:%i:%s');
							$x['entry'] = strtotime($x['entry']);
							$x['exit'] = strtotime($x['exit']);
							$visits[] = $x;
							$x = [];
						}elseif($i == count($bEvents)){
							$x['entry'] = strtotime($x['entry']);
							$x['exit'] = 'infinite';
							$x['time_spend'] = 'infinite';
							$visits[] = $x;
						}
					}
	
					$eee['visits'] = $visits;
					$visits = [];
					$array['badges'][] = $eee;
				}
				$newEventArray2[] = $array;

			}
			// echo json_encode($newEventArray2);exit;
			// foreach($newEventArray2 as $e){
			// 	if($this->hourlyevents->getSerialHourlyEvent($date, $e['reader_serial']) == NULL){
			// 		$this->hourlyevents->createHourlyEvent($hour, $date, $e['reader_serial'], json_encode($e['badges']));
			// 	}else{
			// 		$this->hourlyevents->updateHourlyEvent($hour, $date, $e['reader_serial'], json_encode($e['badges']));
			// 	}
			// }
			$finalArray = [
				"window_start" => strtotime($start),
				"window_stop" => strtotime($end),
				"all_readers" => $newEventArray2
			];
			$jsonData = json_encode($finalArray);
			$exitingEvts = $this->hourlyevents->getSerialHourlyEvent($date, $hour);
			if(count($exitingEvts)){
				var_dump($this->hourlyevents->updateHourlyEvent($hour, $date, $jsonData));
			}else{
				var_dump($this->hourlyevents->createHourlyEvent($hour, $date, $jsonData));
			}
			echo($jsonData);
			exit;
		}else{
			// No events in this hour
			var_dump('No Events in this hour. Betweeen'. $start . ' and ' . $end . ' timezon: '. date_default_timezone_get());
		}
		
	}

	public function gethourlyevents() {
		$date = $_GET['date'];
		$hour = $_GET['hour'];
		if(!$date){
			echo "Date is required"; exit;
		}

		$evts = $this->hourlyevents->getSerialHourlyEvent($date, $hour);
		$res = [];
		$x = [];
		foreach($evts as $e) {
			$x["reader_serial"] = $e["reader_serial"];
			$x["badges"] = json_decode($e[$hour.'_hrs'], true);

			$res[] = $x;
			$x = [];
		}
		$start = $date . " " . $hour . ":00:00";
		$end = $date . " " . $hour . ":59:59"; 
		$finalArray = [
				"window_start" => strtotime($start),
				"window_stop" => strtotime($end),
				"all_readers" => $res
			];
		echo(json_encode($finalArray));
		// var_dump($res);
		exit;
	}

	public function customreportsettings($id=0) {
			$event_setting = $this->hourlyevents->getGlobalEventSetting();
			if($event_setting){
				$id = $event_setting['id'];
			}else{
				$id = '';
			}
			$pageData = array(
				'pageTitle'=>'Manager Report Windows',
				'action'=>"admin/events/customreportsettins/". $id,
				'event_setting' => $event_setting
			);

			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				$data = $_POST;
				$start1 = "2022-08-01 " . $data['wStart'] . ":00";
				$end1 = "2022-08-01 " . $data['wEnd'] . ":00";
				$date_a = new DateTime($start1);
				$date_b = new DateTime($end1);
				$diff1 = $date_b->getTimestamp() - $date_a->getTimestamp();
				// var_dump($diff1);exit;
				if($diff1 < 120 || $diff1 > 1800) {
					// Window diff not between 2-30 minutes
					$this->session->set_flashdata('error','Time difference can\'t be less then 2 minutes and greater than 30 minutes.');
					// $this->baseloader->adminviews('globalreportwindows',$pageData);
				}else {
					if($id){
						$data['id'] = $id;
					}
					$date = date('Y-m-d H:i:s');
					$data['created_at'] = $date;
					$data['updated_at'] = $date;
					$result=$this->hourlyevents->updateGlobalReportWindow($data);
					// var_dump($result);exit;
					// if($result) {
					$this->session->set_flashdata('success','Report Window updated successfully');
					redirect('admin/events');
					// }else {
						// $this->session->set_flashdata('error','Something went wrong. Please try after sometime.');
						// $this->baseloader->adminviews('globalreportwindows',$pageData);
					// }
				}
				
			}
			// var_dump($id);exit;
			$this->baseloader->adminviews('globalreportwindows',$pageData);

	}
}
