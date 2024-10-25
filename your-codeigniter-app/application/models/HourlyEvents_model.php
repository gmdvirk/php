<?php
    
class HourlyEvents_model extends CI_Model
{

    public function __construct() {
        $this->table2 = 'hourly_staff_rounding';
        $this->table = 'hourly_reader_events';
    }

    /*
    * $hrs : period of hour from 00 to 23
    * $date : Date for which we need to insert data
    * $readerSerial: Number or reader serial for which we inserting data
    * $data: json data of events  
    */
    public function createHourlyEvent($hrs, $date, $readerSerial, $data) {
        $hrs = sprintf("%02d", $hrs);
        return $this->db->insert($this->table2, [ $hrs."_hrs" => $data, "reader_serial" => $readerSerial, "date" => $date]);
    }

    public function updateHourlyEvent($hrs, $date, $readerSerial, $data) {
        $hrs = sprintf("%02d", $hrs);
        $d = [ $hrs."_hrs" => $data ];

        return $this->db->where(["date" => $date, "reader_serial" => $readerSerial])
			->update($this->table2, $d);
    }

    public function getSerialHourlyEvent($date, $readerSerial) {
        // $hour = sprintf("%02d", $hour);
        $where_arr = ["date" => $date, "reader_serial" => $readerSerial];
        // $this->db->select($hour. "_hrs");
        return $this->db->get_where($this->table2 ,$where_arr)->result_array();
	}

    public function generateHourlyEvent($start, $end, $data) {
        return $this->db->insert($this->table, [ "window_start" => $start, "window_stop" => $end, "json_data" => $data]);
    }

    public function isReportAvailable() {
        $date = date('Y-m-d');
		$hour =  date('H');
		if($hour == '00') {
			$date = date('Y-m-d',strtotime("-1 days"));
			$hour = 24;
		}
		$hour = ($hour - 1);
		$start = $date . " " . $hour . ":00:00";
		$end = $date . " " . $hour . ":59:59";
        $where_arr = ["window_start" => $start, "window_stop" => $end];
        return $this->db->get_where($this->table ,$where_arr)->result_array();
    }

    public function getAllManagers() {
        return $this->db->get('managers')->result_array();
    }

    public function generateHourlyManagerEvent($window, $managerId, $data) {
        return $this->db->insert('manager_window_events', [ "window" => $window, "facility_id" => $managerId, "badge_json" => $data]);
    }

    public function updateGlobalReportWindow($data) {
        return $this->db->replace('event_window_global_settings', $data);
    }

    public function getGlobalEventSetting() {
        return $this->db->from('event_window_global_settings')->order_by('id','desc')->limit(1,0)->get()->row_array();
    }

    public function insertLargeGlobalSettingEvents($event_data) {
        return $this->db->insert_batch('global_window_events', $event_data); 
    }

    public function isDailyGlobalReportProcessed() {
        $date = date('Y-m-d');
        return $this->db->get_where('global_window_events' ,['date(created_at)' => $date])->num_rows();
    }

    public function isDailyManagerReportProcessed() {
        $date = date('Y-m-d');
        return $this->db->get_where('manager_window_events' ,['date(created_at)' => $date])->num_rows();
    }

    public function generateGlobalEvent($start, $end, $data) {
        return $this->db->insert('global_window_events', [ "window_start" => $start, "window_stop" => $end, "json_data" => $data]);
    }
}