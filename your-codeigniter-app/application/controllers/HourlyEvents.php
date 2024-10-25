<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HourlyEvents extends MY_Controller {

    public function __construct()
	{
		// call to models
		$this->load->model('events_model','events');
		$this->load->model('HourlyEvents_model','hourlyevents');
	}

	public function generatehourlyevents(){
		$date = date('Y-m-d');
		$hour =  date('H');
		if($hour == '00') {
			$date = date('Y-m-d',strtotime("-1 days"));
			$hour = 24;
		}
		$hour = ($hour - 1);
		$start = $date . " " . $hour . ":00:00";
		$end = $date . " " . $hour . ":59:59";
        $txt = "Script run at ". date('Y-m-d H:i:s');
        file_put_contents('cron_logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
		$maxId = ($this->events->getMaxEventId()->max_event_id - 100000);
		$events = $this->events->getHourlyEvents($start, $end, $maxId);

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
							$x['entry'] = $evt['tTimestamp'];
						}else{
							$x['exit'] = $evt['tTimestamp'];
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
					$eee['total_time_s'] = $total_time == 0 ? 'infinite' : $total_time;
					$visits = [];
					$total_time = 0;
					$array['badges'][] = $eee;
				}
				$newEventArray2[] = $array;

			}
	
			$res = $this->hourlyevents->generateHourlyEvent($start, $end, json_encode($newEventArray2));

			if($res){
				$finalArray = [
					"window_start" => strtotime($start),
					"window_stop" => strtotime($end),
					"all_readers" => $newEventArray2
				];
				echo $jsonData = json_encode($finalArray); exit;
			}else{
				echo "Something went wrong.";
			}
		}else{
			// No events in this hour
			echo 'No Events in this hour. Betweeen '. $start . ' and ' . $end . ' timezon: '. date_default_timezone_get();
		}
		
	}

	// public function generateFullDayManagersReport() {
	// 	$txt = "Manager Window Script run at ". date('Y-m-d H:i:s');
	// 	file_put_contents('cron_logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
	// 	$date = date('Y-m-d',strtotime("-1 days"));
	// 	$allManagerEvents = [];
	// 	$managers = $this->hourlyevents->getAllManagers();
	// 	foreach($managers as $manager) {
	// 		if($manager['w1Start'] && $manager['w1End']){
	// 			$start = $date . " " . trim($manager['w1Start']) .":00";
	// 			$end = $date . " " . trim($manager['w1End']) . ":59";
	// 			$allEvents = $this->events->getEventsWithManagers($start, $end, $manager['vId']);
	// 			if(count($allEvents)){
	// 				$allManagerEvents[$manager['vId']]['w1'] = $allEvents;
	// 			}
	// 		}

	// 		if($manager['w2Start'] && $manager['w2End']){
	// 			$start = $date . " " . trim($manager['w2Start']) .":00";
	// 			$end = $date . " " . trim($manager['w2End']) . ":59";
	// 			$allEvents = $this->events->getEventsWithManagers($start, $end, $manager['vId']);
	// 			if(count($allEvents)){
	// 				$allManagerEvents[$manager['vId']]['w2'] = $allEvents;
	// 			}
	// 		}
	// 	}

	// 	if(count($allManagerEvents)) {
	// 		foreach($allManagerEvents as $managerId => $windows) {
	// 			$allEventsArray = [];
	// 			foreach($windows as $window => $windowData){
					
	// 				$newEventArray = [];
	// 				foreach($windowData as $event){
	// 					$newEventArray[$event['reader']][$event['badge']][] = $event;
	// 				}
					
	// 				$newEventArray2 = [];
	// 				foreach($newEventArray as $sKey => $readerBadges){
	// 					$array = [];
	// 					$array['reader_serial'] = $sKey;
		
	// 					foreach($readerBadges as $bKey => $bEvents){
	// 						$eee = [];
	// 						$eee['badge_serial'] = $bKey;
	// 						$eee['visits'] = $bEvents;
	// 						$visits = [];
	// 						$total_time = 0;
	// 						$x = [];
	// 						foreach($bEvents as $i => $evt) {
	// 							$i++;
	// 							if($i%2 != 0){
	// 								$x['entry'] = $evt['event_time'];
	// 							}else{
	// 								$x['exit'] = $evt['event_time'];
	// 							}
		
	// 							if($i%2 == 0){
	// 								$date_a = new DateTime($x['entry']);
	// 								$date_b = new DateTime($x['exit']);
	// 								$diff = $date_b->getTimestamp() - $date_a->getTimestamp();
	// 								$total_time+=$diff;
	// 								$x['time_spend'] = $diff;
	// 								$x['entry'] = strtotime($x['entry']);
	// 								$x['exit'] = strtotime($x['exit']);
	// 								$visits[] = $x;
	// 								$x = [];
	// 							}elseif($i == count($bEvents)){
	// 								$x['entry'] = strtotime($x['entry']);
	// 								$x['exit'] = 'infinite';
	// 								$x['time_spend'] = 'infinite';
	// 								$visits[] = $x;
	// 							}
	// 						}
			
	// 						$eee['visits'] = $visits;
	// 						$eee['total_time_s'] = $total_time;
	// 						$visits = [];
	// 						$total_time = 0;
	// 						$array['badges'][] = $eee;
	// 					}
	// 					$newEventArray2[] = $array;
		
	// 				}

					
	// 				$res = $this->hourlyevents->generateHourlyManagerEvent($window, $managerId, json_encode($newEventArray2));
	
	// 			}
	// 		}

	// 	}else {
	// 		echo 'No events.'. $start . " " . $end;
	// 	}
	// }

	public function generateGlobalCustomReport_old() {
		$txt = "Global Window Script run at ". date('Y-m-d H:i:s');
		file_put_contents('cron_logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
		$event_setting = $this->hourlyevents->getGlobalEventSetting();
		if($event_setting){
			$date = date('Y-m-d',strtotime("-1 days"));

			if($event_setting['wStart'] && $event_setting['wEnd']) {
				$start = $date . " " . trim($event_setting['wStart']) .":00";
				$end = $date . " " . trim($event_setting['wEnd']) . ":59";
				$this->processGlobalReport($start, $end);
			}

			if($event_setting['w2Start'] && $event_setting['w2End']) {
				$start2 = $date . " " . trim($event_setting['w2Start']) .":00";
				$end2 = $date . " " . trim($event_setting['w2End']) . ":59";
				$this->processGlobalReport($start2, $end2);
			}
		}else{
			echo "Global settings not available.";
			exit;
		}

	}


	public function processGlobalReport_old($start, $end) {
		$maxId = ($this->events->getMaxEventId()->max_event_id - 100000);
		$all_events = $this->events->getAllEventsWithDate($start, $end, $maxId);
		if(count($all_events)) {
			$filtered_events = [];
			foreach($all_events as $event) {
				$filtered_events[$event['manager_id']][$event['badge_id']][] = $event;
			}
			$final_array = [];
			$newEventArray2 = [];
			$array = [];
			foreach($filtered_events as $manager_id => $devices){
				$final_array['window_start'] = $start;
				$final_array['window_stop'] = $end;
				$final_array['facility_id'] = $manager_id;
				foreach($devices as $device_id => $dEvents) {
					$eee = [];
					$eee['badge_serial'] = $device_id;
					$visits = [];
					$total_time = 0;
					$x = [];
					foreach($dEvents as $i => $evt) {
						$i++;
						if($i%2 != 0){
							$x['entry'] = $evt['event_time'];
						}else{
							$x['exit'] = $evt['event_time'];
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
						}elseif($i == count($dEvents)){
							$x['entry'] = strtotime($x['entry']);
							$x['exit'] = 'infinite';
							$x['time_spend'] = 'infinite';
							$visits[] = $x;
						}
					}
					$eee['visits'] = $visits;
					$eee['total_time_s'] = $total_time;
					$array[] = $eee;
					$visits = [];
					$total_time = 0;
				}
				$final_array['badge_json'] = json_encode($array);
				$newEventArray2[] = $final_array;
				$array = [];
				$final_array = [];
			}
			$res = $this->hourlyevents->insertLargeGlobalSettingEvents($newEventArray2);
			if($res) {
				echo $res. ' records inserted.';
			}else{
				echo 'No record instered.';
			}

		}else{
			echo "No event available between ". $start . " " . $end;
		}
	}

	public function generateGlobalCustomReport(){
		$txt = "Global Window Script run at ". date('Y-m-d H:i:s');
		file_put_contents('cron_logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
		$event_setting = $this->hourlyevents->getGlobalEventSetting();
		if($event_setting){
			$date = date('Y-m-d',strtotime("-1 days"));
			$maxId = ($this->events->getMaxEventId()->max_event_id - 100000);

			if($event_setting['wStart'] && $event_setting['wEnd']) {
				$start = $date . " " . trim($event_setting['wStart']) .":00";
				$end = $date . " " . trim($event_setting['wEnd']) . ":59";
				$this->processGlobalReport($start, $end, $maxId);
			}

			if($event_setting['w2Start'] && $event_setting['w2End']) {
				$start2 = $date . " " . trim($event_setting['w2Start']) .":00";
				$end2 = $date . " " . trim($event_setting['w2End']) . ":59";
				$this->processGlobalReport($start2, $end2, $maxId);
			}
		}else{
			echo "Global settings not available.";
			exit;
		}
		//  Old Functionality
		// $date = date('Y-m-d');
		// $hour =  date('H')-1;
		// $start = $date . " " . $hour . ":00:00";
		// $end = $date . " " . $hour . ":59:59";

        // $txt = "Script run at ". date('Y-m-d H:i:s');
        // file_put_contents('cron_logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
		
	}

	public function processGlobalReport($start, $end, $maxId) {
		
		$events = $this->events->getHourlyEvents($start, $end, $maxId);

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
							$x['entry'] = $evt['tTimestamp'];
						}else{
							$x['exit'] = $evt['tTimestamp'];
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
					$eee['total_time_s'] = $total_time == 0 ? 'infinite' : $total_time;
					$visits = [];
					$total_time = 0;
					$array['badges'][] = $eee;
				}
				$newEventArray2[] = $array;

			}
	
			$res = $this->hourlyevents->generateGlobalEvent($start, $end, json_encode($newEventArray2));

			if($res){
				$finalArray = [
					"window_start" => strtotime($start),
					"window_stop" => strtotime($end),
					"all_readers" => $newEventArray2
				];
				echo $jsonData = json_encode($finalArray);
			}else{
				echo "Something went wrong.";
			}
		}else{
			// No events in this hour
			echo 'No Events in this hour. Betweeen '. $start . ' and ' . $end . ' timezon: '. date_default_timezone_get();
		}
		
	}
}