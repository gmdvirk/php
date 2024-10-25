<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PacketBuffer {
    private static $instance = null;
    private $size;

    private function __construct($size) {
        $this->size = $size;
        if (!apcu_exists('packet_buffer_currentIndex')) {
            apcu_store('packet_buffer_currentIndex', 0);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new PacketBuffer(100);
        }
        return self::$instance;
    }

    public function insert($packet) {
        $currentIndex = apcu_fetch('packet_buffer_currentIndex');
        apcu_store("packet_buffer_" . $currentIndex, $packet);
        $currentIndex = ($currentIndex + 1) % $this->size;
        apcu_store('packet_buffer_currentIndex', $currentIndex);
    }

    public function getBuffer() {
        $buffer = [];
        for ($i = 0; $i < $this->size; $i++) {
            $buffer[$i] = apcu_fetch("packet_buffer_" . $i);
        }
        return $buffer;
    }
}

class Events extends MY_Controller {
    private $packetBuffer;
    
    public function __construct()
    {
        $this->packetBuffer = PacketBuffer::getInstance();
        $this->load->model('events_model','events');
        $this->load->model('HourlyEvents_model','hourlyevents');
    }

    public function index()
    {
        $data = array('pageTitle' =>'Events','events'=>$this->events->getEvents());
        $this->baseloader->adminviews('events',$data);
    }

    function create()
    {
        $data=array('pageTitle'=>'Add Event','action'=>base_url("admin/events/createEvent"));
        $this->baseloader->adminviews('editevent',$data);
    }

	// Check last 100 packets to see if NC packet with approx same time is found
	// If there is already something there, can't inject +/- 30 sec of old packet
    function hasDuplicate($newPacket, $packetBuffer)
    {
        $buffer = $packetBuffer->getBuffer();
        $count = count($buffer);

        for ($i = 0; $i < $count; $i++) {
            if (!empty($buffer[$i]) && $newPacket->Eventnum === $buffer[$i]->Eventnum &&
                $newPacket->BadgeId === $buffer[$i]->BadgeId &&
                $newPacket->ReaderId === $buffer[$i]->ReaderId &&
                $newPacket->Eventtype == 0 &&
                (abs($newPacket->Timestamp - $buffer[$i]->Timestamp) < 60)
            ) {
                return true;
            }
        }
        return false;
    }


	function createEvent(){
		// Check and Run event report generation process with Global window time
		if($this->hourlyevents->isDailyGlobalReportProcessed()){
			$gcmd = "nohup php /var/www/html/index.php HourlyEvents generateGlobalCustomReport";
			$gfirst = array();
			$gsecond = array();
			$gpid = proc_open($gcmd,$gfirst,$gsecond);
		}

		// if($this->hourlyevents->isDailyManagerReportProcessed()){
		// 	$mcmd = "nohup php /var/www/html/index.php HourlyEvents generateFullDayManagersReport";
		// 	$mfirst = array();
		// 	$msecond = array();
		// 	$mpid = proc_open($mcmd,$mfirst,$msecond);
		// }

		// Check for last hour visit report
		if(!count($this->hourlyevents->isReportAvailable())){
			// Run last hour report process
			$cmd = "nohup php /var/www/html/index.php HourlyEvents generatehourlyevents";
			$first = array();
			$second = array();
			$pid = proc_open($cmd,$first,$second);
		}

		$dummy_data = '[{"Eventnum" : 123, "BadgeId" : "EF18A1", "ReaderId" : "DEBEEF", "Eventtype" : 7, "Timestamp" : 1556860726},{"Eventnum" : 123, "BadgeId" : "FEEEEF", "ReaderId" : "DEBEEF", "Eventtype" : 7, "Timestamp" : 1556860726},{"Eventnum" : 123, "BadgeId" : "FEEEEF", "ReaderId" : "DEBEEF", "Eventtype" : 7, "Timestamp" : 1556860726}]';		
		$newDummyData = '[{"BadgeId":"654556765", "PeerId":"888", "Eventtype":"10", "Timestamp" : 1556860726}, {"BadgeId":"3424242", "PeerId":"4242424", "Eventtype":"10", "Timestamp" : 1556860110}]';
		$json_data=file_get_contents('php://input');
		if(!empty($json_data)){
			$response_data = json_decode($json_data);
			// var_dump('ss', $response_data);exit;
			// convert to array is a single json
			if(!is_array($response_data)){
				$response_data = array($response_data);
			}
			$save_data = array();
			$rollback = array();
			foreach($response_data as $data){
				$tRaw_save_time = date("Y-m-d H:i:s");
				$tTimestamp = !empty($data->Timestamp) ? $data->Timestamp : null;
				if(isset($tTimestamp)){
					$epoch = $tTimestamp;
					$dt = new DateTime("@$epoch");  // convert UNIX timestamp to PHP DateTime
					$tTimestamp = $dt->format('Y-m-d H:i:s');
				}
				// set as null for now
					$iBadge_id = NULL;
					$iReader_id = NULL;
					$vBadge_type = NULL;
					$vReader_type = NULL;
				
				if(!empty($data))
				{
                //check the request format.				
				if (array_key_exists("Eventnum",$data)){
					unset($save_data);
					//check if the parameters are not empty or not.
					if($data->BadgeId !=='' && $data->ReaderId !== '' && $data->Eventnum !== '' && $data->Eventtype !== '')
					{
						$vBadge_serial = $data->BadgeId;
						$vReader_serial = $data->ReaderId;
						$iEvent_num = $data->Eventnum;
						$vEvent_type = $data->Eventtype;

						// save data
						$orgTtime = $tTimestamp;
						
						// Convert the raw times to DateTime objects
						$current_time = new DateTime($tRaw_save_time);
						$event_time = new DateTime($tTimestamp);

						// Create a copy of the current time to calculate the 30 minutes in the future
						$future_time = clone $current_time;
						$future_time->modify('+30 minutes');

						// Create a copy of the current time to calculate 1 month in the past
						$past_time = clone $current_time;
						$past_time->modify('-1 month');

						// Check if the event time is more than 30 minutes in the future or 1 month in the past
						if ($event_time > $future_time || $event_time < $past_time) {
							$tTimestamp = $tRaw_save_time;
						} else {
							// Keep the event time unchanged
							$tTimestamp = $event_time->format("Y-m-d H:i:s");
						}

						$save_data["tRaw_save_time"] = $orgTtime; // instead of raw db time, we save the HW/data raw timestamp
						$save_data["tTimestamp"] = $tTimestamp;
						$save_data["vBadge_serial"] = $vBadge_serial;
						$save_data["vReader_serial"] = $vReader_serial;
						$save_data["iEvent_num"] = $iEvent_num;
						$save_data["iBadge_id"] = $iBadge_id;
						$save_data["iReader_id"] = $iReader_id;
						$save_data["vBadge_type"] = $vBadge_type;
						$save_data["vReader_type"] = $vReader_type;
						
						// mark "duplicate" NC with type 8
						if ($this->hasDuplicate($data, $this->packetBuffer)) {
							$save_data["vEvent_type"] = "8";
						} else {
							$save_data["vEvent_type"] = $vEvent_type;
							// inject unique packet into buffer
							$this->packetBuffer->insert($data);
						}

						$create = $this->events->createEvent($save_data);
					}
					else {
                        $response['message'] = "All parameters are mandatory";
						echo json_encode($response); 
						die();
                    }
				}
				else {
					unset($save_data);
					//check if the parameters are not empty or not.
					if($data->BadgeId !== '' && $data->PeerId !== '' && $data->Eventtype !== '') {
						//save data
						$save_data['time_stamp'] = $tTimestamp;
						$save_data['badge_id'] = $iBadge_id;
						$save_data['peer_id'] = $data->PeerId;
						$save_data['event_type'] = $data->Eventtype;
						$save_data['created_at'] = $tRaw_save_time;
					    $save_data['badge_serial'] = $data->BadgeId;
						$save_data['badge_type'] = $vBadge_type;
						$create = $this->events->createEventForNewFormat($save_data);
					}
					else {
						$response['message'] = "All fields are mandatory";
						echo json_encode($response); 
						die(); 
					}
				}
					if($create == "success"){
						//
					}else{
						$rollback[] = $create;
					}
				}
                else
				{
					$response['message'] = "All fields are mandatory.";
					echo json_encode($response); die; 
				}					
			}
			if(sizeof($rollback)>0){
				$raw_ids = implode(",",$rollback);
				$response["message"] = "Some records rollback."; 
				$response["Row_ids"] = $raw_ids; 
			}else{
				$response["message"] = "All records added successfully"; 
			}
		}
		else
		{
		  $response['message'] = "All fields are mandatory.";
		}
		
		echo json_encode($response);
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

	public function generatehourlyevents(){
		$date = date('Y-m-d');
		$hour =  date('H')-1;
		$start = $date . " " . $hour . ":00:00";
		$end = $date . " " . $hour . ":59:59";

        $txt = "Script run at ". date('Y-m-d H:s:i');
        file_put_contents('cron_logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
		$events = $this->events->getHourlyEvents('7', $start, $end);

		if(!empty($events)) {
			$newEventArray = [];
			foreach($events as $event){
				$newEventArray[$event['reader']][$event['badge']][] = $event;
			}
			
			$newEventArray2 = [];
			foreach($newEventArray as $sKey => $readerBadges){
				$array = [];
				$array['reader_serial'] = $sKey;

				foreach($readerBadges as $bKey => $bEvents){
					$eee = [];
					$eee['badge_serial'] = $bKey;
					$eee['visits'] = $bEvents;
					$visits = [];
					$total_time = 0;
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
							$total_time+=$diff;
							$x['time_spend'] = $diff;
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
					$eee['total_time_s'] = $total_time;
					$visits = [];
					$total_time = 0;
					$array['badges'][] = $eee;
				}
				$newEventArray2[] = $array;

			}
	
			$res = $this->hourlyevents->generateHourlyEvent($start, $end, json_encode($newEventArray2));

			// if($res){
			// 	$finalArray = [
			// 		"window_start" => strtotime($start),
			// 		"window_stop" => strtotime($end),
			// 		"all_readers" => $newEventArray2
			// 	];
			// 	echo $jsonData = json_encode($finalArray); exit;
			// }else{
			// 	// echo "Something went wrong.";
			// }
		}else{
			// No events in this hour
			// var_dump('No Events in this hour. Betweeen '. $start . ' and ' . $end . ' timezon: '. date_default_timezone_get());
		}
		
	}


	
}
