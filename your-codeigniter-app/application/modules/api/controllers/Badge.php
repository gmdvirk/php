<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Badge extends MY_Controller {

    public function __construct() {
        $this->load->model('customers_model', 'customer');
        $this->load->model('devices_model', 'device');
        $this->load->model('managers_model', 'manager');
        $this->load->model('readers_model','reader');
        $this->load->helper('api');
        $this->load->helper('json');
    }

    public function index() {

    }

    public function customersJsonSetting() {
        // $request_data = file_get_contents('php://input');
        $badge_serial = $this->input->get('badge_serial');
        $limit = $this->input->get('limit');
        $page = $this->input->get('page');
        if($page && is_numeric($page)) {
            $start = ($page * $limit) - $limit;
            if(!$limit || !is_numeric($limit)){
                $limit = 10;
            }
        }else{
            $start = '';
            $limit = '';
        }
        $success = false;
        $message = "Unable to fetch JSON settings.";
        $data = [];
        $errors = [];

        if(!$badge_serial) {   
            $errors[] =  'Badge serial is required.';
            response($success , $message, $data, $errors);
        }

        $device = $this->device->getDeviceBySerial($badge_serial);
        if(!$device) {
            $errors[] = 'Incorrect badge serial "'.$badge_serial.'"';
            response($success , $message, $data, $errors);
        }

        $facility_id = $device->vAssigned_Facility_ID;
        if($facility_id == "") {
            $errors[] =  "No facility assigned to badge serial";
            response($success , $message, $data, $errors);
        }

        $facility_details = $this->manager->getManagerById($facility_id);
        if(!$facility_details) {
            $errors[] = 'facility '. $facility_id .' not available';
            response($success , $message, $data, $errors);
        }
        $facility_customers = $this->customer->getCustomerJsonByFacilityWithLimit($facility_id, $start, $limit);
        $facility_customers_count = $this->customer->getCustomerJsonByFacilityCount($facility_id);
        $customers = [];
        foreach($facility_customers as $customer) {
            $customer->json_settings = json_decode($customer->json_settings);
            $customers[] = $customer;
        }
        $data['facility'] = [
            'facility_id' => $facility_id,
            'facility_settings' => !is_json_valid($facility_details->vJsonString) ? "" : json_decode($facility_details->vJsonString)
        ];
        $data['total_badge'] = $facility_customers_count;
        $data['badge_settings'] = $customers;
        response(true , 'Customer JSON settings fetched successfully.', $data, $errors);
    }

    public function updateuserbadgesettings() {
        if ($this->input->server('REQUEST_METHOD') == 'POST'){
            $request_json = file_get_contents('php://input');
            $request_data = json_decode($request_json, TRUE);
            if($request_data){

                if(array_key_exists('badge_serial', $request_data) && array_key_exists('badge_settings', $request_data)){
                    $settings_keys = ["AbhrNoDr","DrNoAbhrBp","MskSett","DbgMod","BpStg","DrRssThrs","AbhrRssThrs","SpRssThrs","BgRssThrs","BdRssThrs","PrvRssThrs","CmRssThrs","SpQualPer","BdZnPer","BpOnRssP","BpOnRssF","BdHystPer","SpLeavePer"];
                    $badge_serial = $request_data['badge_serial'];
                    $badge_settings = $request_data['badge_settings'];
                    $keys = array_keys($badge_settings);
                    
                    if($settings_keys !== $keys){
                        $settings_keys[] = "CompliantBeep";
                        $settings_keys[] = "HandWashSucBeep";
                        $settings_keys[] = "HandWashFailBeep";
                        if($settings_keys !== $keys){
                            $errors[] = 'Incorrect Badge setting keys.';
                            response(400 , 'Invalid data.',[],$errors);
                        }
                    }
                    $device = $this->device->getDeviceBySerial($badge_serial);
                    if(!$device) {
                        $errors[] = 'Incorrect badge serial "'.$badge_serial.'"';
                        response(400 , 'Invalid data', [], $errors);
                    }
                    $customer = $this->customer->getCustomerBySerial($badge_serial);
                    if(!$customer) {
                        $errors[] = 'No customer assigned to device "'.$badge_serial.'"';
                        response(400 , 'Device settings not available.', [], $errors);
                    }
                    # Customer available , Device available update JSON values
                    $oldJson = $customer->jsonAccepted;
                    $oldJsonArray = json_decode($oldJson, TRUE);
                    $isValid = $this->validateJsonSettings($badge_settings);
                    if($isValid['success']) {
                        $json = json_encode($badge_settings);
                        if(is_json_valid($json)){
                            $updateData = ['jsonAccepted' => $json];
                            $updated = $this->customer->updateCustomer( $customer->iId,$updateData);
                            if($updated){
                                $customer = $this->customer->getCustomerBySerial($badge_serial);
                                $rdata['badge_settings'] = json_decode($customer->jsonAccepted);
                                response(200 , 'Badge settings updated successfully.', $rdata, []);
                            }else{
                                response(500 , 'Something went wrong, please try after sometime.');
                            }
                        }else{
                            response(false , 'Invalid Json.');
                        }
                    }else{
                        response(false , 'Incorrect JSON values.', [], $isValid['errors']);
                    }
                }else{
                    response(400 , 'Badge Serial or Badge Settings are required.');
                }
            }else{
                $errors[] = 'Invalid or missing request JSON data.';
                response(400 , 'Invalid request data.');
            }
        }else{
            response(400 , 'Incorrect HTTP request method.');
        }
    }

    public function validateJsonSettings($jsonSettingArray) {
        $response = ['success' => true, 'errors' => []];
        $onOff = [0,1];
        if(!isset($jsonSettingArray['AbhrNoDr']) || !is_numeric($jsonSettingArray['AbhrNoDr']) || $jsonSettingArray['AbhrNoDr'] < 0 || $jsonSettingArray['AbhrNoDr'] > 45){
            $response['success'] = false;
            $response['errors'][] = 'AbhrNoDr  is required and should be numeric and between 0-45'; 
        }

        if(!isset($jsonSettingArray['DrNoAbhrBp']) || !is_numeric($jsonSettingArray['DrNoAbhrBp']) || $jsonSettingArray['DrNoAbhrBp'] < 0 || $jsonSettingArray['DrNoAbhrBp'] > 20){
            $response['success'] = false;
            $response['errors'][] = 'DrNoAbhrBp  is required and should be numeric and between 0-20'; 
        }

        if(!isset($jsonSettingArray['MskSett']) || !is_numeric($jsonSettingArray['MskSett']) || $jsonSettingArray['MskSett'] < 0 || $jsonSettingArray['MskSett'] > 20){
            $response['success'] = false;
            $response['errors'][] = 'MskSett  is required and should be numeric and between 0-20'; 
        }

        if(!isset($jsonSettingArray['DbgMod']) || !is_numeric($jsonSettingArray['DbgMod']) || !in_array($jsonSettingArray['DbgMod'], $onOff)){
            $response['success'] = false;
            $response['errors'][] = 'DbgMod is required and should be numeric and between 0-1';
        }

        if(!isset($jsonSettingArray['BpStg']) || !is_numeric($jsonSettingArray['BpStg']) || !in_array($jsonSettingArray['BpStg'], $onOff)){
            $response['success'] = false;
            $response['errors'][] = 'BpStg is required and should be numeric and between 0-1';
        }

        if(!isset($jsonSettingArray['DrRssThrs']) || !is_numeric($jsonSettingArray['DrRssThrs']) || $jsonSettingArray['DrRssThrs'] < 0 || $jsonSettingArray['DrRssThrs'] > 31){
            $response['success'] = false;
            $response['errors'][] = 'DrRssThrs  is required and should be numeric and between 0-31'; 
        }

        if(!isset($jsonSettingArray['AbhrRssThrs']) || !is_numeric($jsonSettingArray['AbhrRssThrs']) || $jsonSettingArray['AbhrRssThrs'] < 0 || $jsonSettingArray['AbhrRssThrs'] > 31){
            $response['success'] = false;
            $response['errors'][] = 'AbhrRssThrs  is required and should be numeric and between 0-31'; 
        }

        if(!isset($jsonSettingArray['SpRssThrs']) || !is_numeric($jsonSettingArray['SpRssThrs']) || $jsonSettingArray['SpRssThrs'] < 0 || $jsonSettingArray['SpRssThrs'] > 31){
            $response['success'] = false;
            $response['errors'][] = 'SpRssThrs  is required and should be numeric and between 0-31'; 
        }

        // Min should be 20 -- but we are no longer using this number, so able to accept 0 as min now. -- HC 12May2021
        if(!isset($jsonSettingArray['BgRssThrs']) || !is_numeric($jsonSettingArray['BgRssThrs']) || $jsonSettingArray['BgRssThrs'] < 0 || $jsonSettingArray['BgRssThrs'] > 80){
            $response['success'] = false;
            $response['errors'][] = 'BgRssThrs  is required and should be numeric and between 20-80'; 
        }

        if(!isset($jsonSettingArray['BdRssThrs']) || !is_numeric($jsonSettingArray['BdRssThrs']) || $jsonSettingArray['BdRssThrs'] < 0 || $jsonSettingArray['BdRssThrs'] > 31){
            $response['success'] = false;
            $response['errors'][] = 'BdRssThrs  is required and should be numeric and between 0-31'; 
        }

        if(!isset($jsonSettingArray['PrvRssThrs']) || !is_numeric($jsonSettingArray['PrvRssThrs']) || $jsonSettingArray['PrvRssThrs'] < 0 || $jsonSettingArray['PrvRssThrs'] > 31){
            $response['success'] = false;
            $response['errors'][] = 'PrvRssThrs  is required and should be numeric and between 0-31'; 
        }

        if(!isset($jsonSettingArray['CmRssThrs']) || !is_numeric($jsonSettingArray['CmRssThrs']) || $jsonSettingArray['CmRssThrs'] < 0 || $jsonSettingArray['CmRssThrs'] > 31){
            $response['success'] = false;
            $response['errors'][] = 'CmRssThrs  is required and should be numeric and between 0-31'; 
        }

        if(!isset($jsonSettingArray['SpQualPer']) || !is_numeric($jsonSettingArray['SpQualPer']) || $jsonSettingArray['SpQualPer'] < 0 || $jsonSettingArray['SpQualPer'] > 20){
            $response['success'] = false;
            $response['errors'][] = 'SpQualPer is required and should be numeric and between 0-20'; 
        }

        if(!isset($jsonSettingArray['BdZnPer']) || !is_numeric($jsonSettingArray['BdZnPer']) || $jsonSettingArray['BdZnPer'] < 0 || $jsonSettingArray['BdZnPer'] > 45){
            $response['success'] = false;
            $response['errors'][] = 'BdZnPer is required and should be numeric and between 0-45'; 
        }

        if(!isset($jsonSettingArray['BpOnRssP']) || !is_numeric($jsonSettingArray['BpOnRssP']) || !in_array($jsonSettingArray['BpOnRssP'], $onOff)){
            $response['success'] = false;
            $response['errors'][] = 'BpOnRssP is required and should be numeric and between 0-1';
        }

        if(!isset($jsonSettingArray['BpOnRssF']) || !is_numeric($jsonSettingArray['BpOnRssF']) || !in_array($jsonSettingArray['BpOnRssF'], $onOff)){
            $response['success'] = false;
            $response['errors'][] = 'BpOnRssF is required and should be numeric and between 0-1';
        }
        
        if(!isset($jsonSettingArray['BdHystPer']) || !is_numeric($jsonSettingArray['BdHystPer']) || $jsonSettingArray['BdHystPer'] < 0 || $jsonSettingArray['BdHystPer'] > 20){
            $response['success'] = false;
            $response['errors'][] = 'BdHystPer is required and should be numeric and between 0-20'; 
        }

        if(!isset($jsonSettingArray['SpLeavePer']) || !is_numeric($jsonSettingArray['SpLeavePer']) || $jsonSettingArray['SpLeavePer'] < 0 || $jsonSettingArray['SpLeavePer'] > 20){
            $response['success'] = false;
            $response['errors'][] = 'SpLeavePer is required and should be numeric and between 0-20'; 
        }

        return $response;
    }

    public function facilityFirmwareFile() {
        $badge_serial = $this->input->get('badge_serial');
        $success = false;
        $message = "Unable to fetch JSON settings.";
        $data = [];
        $errors = [];

        if(!$badge_serial) {   
            $errors[] =  'Badge serial is required.';
            response($success , $message, $data, $errors);
        }

        $device = $this->device->getDeviceBySerial($badge_serial);
        if(!$device) {
            $errors[] = 'Incorrect badge serial "'.$badge_serial.'"';
            response($success , $message, $data, $errors);
        }

        $facility_id = $device->vAssigned_Facility_ID;
        if($facility_id == "") {
            $errors[] =  "No facility assigned to badge serial";
            response($success , $message, $data, $errors);
        }

        $facility_details = $this->manager->getManagerById($facility_id);
        if(!$facility_details) {
            $errors[] = 'facility '. $facility_id .' not available';
            response($success , $message, $data, $errors);
        }
        
        if($facility_details->firmware_file == NULL || $facility_details->firmware_file == "" || !file_exists(FCPATH.'uploads/facility_bin/'.$facility_details->firmware_file)){
            response($success , 'Firmware file not available for facility '.$facility_id, $data, $errors);
        }
        $data['firmware_file'] = base_url().'uploads/facility_bin/'.$facility_details->firmware_file;
        $data['firmware_details'] = $facility_details->firmware_details;
        $data['firmware_sha256'] = hash_file('sha256', FCPATH.'uploads/facility_bin/'.$facility_details->firmware_file);
        response(true , 'firmware details fetched successfully.', $data, $errors);

    }

    public function readersJsonSetting() {
        // $request_data = file_get_contents('php://input');
        $badge_serial = $this->input->get('badge_serial');
        $limit = $this->input->get('limit');
        $page = $this->input->get('page');
        if($page && is_numeric($page)) {
            $start = ($page * $limit) - $limit;
            if(!$limit || !is_numeric($limit)){
                $limit = 10;
            }
        }else{
            $start = '';
            $limit = '';
        }
        $success = false;
        $message = "Unable to fetch JSON settings.";
        $data = [];
        $errors = [];

        if(!$badge_serial) {   
            $errors[] =  'Badge serial is required.';
            response($success , $message, $data, $errors);
        }

        // $device = $this->device->getDeviceBySerial($badge_serial);
        // if(!$device) {
        //     $errors[] = 'Incorrect badge serial "'.$badge_serial.'"';
        //     response($success , $message, $data, $errors);
        // }

        // $facility_id = $device->vAssigned_Facility_ID;
        // if($facility_id == "") {
        //     $errors[] =  "No facility assigned to badge serial";
        //     response($success , $message, $data, $errors);
        // }

        // $facility_details = $this->manager->getManagerById($facility_id);
        
        $reader  = $this->reader->getReaderOnlyBySerial($badge_serial);
        if(!$reader) {
            $errors[] = 'Reader '. $badge_serial .' not available';
            response($success , $message, $data, $errors);
        }
        $facility = $this->manager->getManagerById($reader->vFacility);
        // $facility_customers = $this->customer->getCustomerJsonByFacilityWithLimit($facility_id, $start, $limit);
        // $facility_customers_count = $this->customer->getCustomerJsonByFacilityCount($facility_id);
        // $customers = [];
        // foreach($facility_customers as $customer) {
        //     $customer->json_settings = json_decode($customer->json_settings);
        //     $customers[] = $customer;
        // }
        // $data['facility'] = [
        //     'facility_id' => $facility_id,
        //     'facility_settings' => !is_json_valid($facility_details->vJsonString) ? "" : json_decode($facility_details->vJsonString)
        // ];
        // $data['total_badge'] = $facility_customers_count;
        $data['badge_settings'] =  json_decode($reader->vJsonString);
        $data['facility_settings'] =  json_decode($facility->vJsonString);
        response(true , 'Reader JSON settings fetched successfully.', $data, $errors);
    }

    public function deviceBatteryState() {
        if($this->input->method(true) === 'POST'){
            $request_json = file_get_contents('php://input');
            $request_data = json_decode($request_json, true);
            
            if($this->validateBatteryStatData($request_data)) {
                $this->load->model('DeviceStats_model','devicestats');
                $id = $this->devicestats->createDeviceBatteryStat($request_data);
                if($id) {
                    response(true ,'Device Stat created successfully.', [], []);
                }
            }

            response(false ,'Something went wrong.', [], []);
        }else{
            response(false ,'Unsupported message', [], []);
        }
    }

    public function validateBatteryStatData($data) {
        $errors = [];
        if(!isset($data['door_serial']) || $data['door_serial'] === "") {
            $errors[] = "`door_serial` is required.";
        }
        if(!isset($data['battery_voltage_mv']) || $data['battery_voltage_mv'] === "") {
            $errors[] = "`battery_voltage_mv` is required.";
        }
        if(!isset($data['file_system_size_b']) || $data['file_system_size_b'] === "") {
            $errors[] = "`file_system_size_b` is required.";
        }
        if(!isset($data['firmware_version']) || $data['firmware_version'] === "") {
            $errors[] = "`firmware_version` is required.";
        }
        if(!isset($data['last_uploaded_min']) || $data['last_uploaded_min'] === "") {
            $errors[] = "`last_uploaded_min` is required.";
        }
        if(!isset($data['number_of_resets']) || $data['number_of_resets'] === "") {
            $errors[] = "`number_of_resets` is required.";
        }
        if(!isset($data['run_duration_s']) || $data['run_duration_s'] === "") {
            $errors[] = "`run_duration_s` is required.";
        }

        if(count($errors) > 0){
            response(400 ,'Unable to created device stat.', [],$errors);
        }
        return true;
    }

    public function deviceRadioState() {
        if($this->input->method(true) === 'POST'){
            $request_json = file_get_contents('php://input');
            $request_data = json_decode($request_json, true);
            if($this->validateRadioStatData($request_data)) {
                $this->load->model('DeviceStats_model','devicestats');
                $id = $this->devicestats->createDeviceRadioStat($request_data);
                if($id) {
                    response(true ,'Device Stat created successfully.', [], []);
                }
            }

            response(false ,'Something went wrong.', [], []);
        }else{
            response(false ,'Unsupported message', [], []);
        }
    }

    public function validateRadioStatData($data) {
        $errors = [];
        if(!isset($data['door_serial']) || empty($data['door_serial'])) {
            $errors[] = "`door_serial` is required.";
        }
        if(!isset($data['avg_airtime_ms']) || $data['avg_airtime_ms'] === "") {
            $errors[] = "`avg_airtime_ms` is required.";
        }
        if(!isset($data['avg_rssi_db']) || $data['avg_rssi_db'] === "") {
            $errors[] = "`avg_rssi_db` is required.";
        }
        if(!isset($data['uplink_attempts']) || $data['uplink_attempts'] === "") {
            $errors[] = "`uplink_attempts` is required.";
        }
        if(!isset($data['uplink_failures']) || $data['uplink_failures'] === "") {
            $errors[] = "`uplink_failures` is required.";
        }

        if(count($errors) > 0){
            response(400 ,'Unable to created device stat.', [],$errors);
        }
        return true;
    }

    public function deviceEsbState() {
        if($this->input->method(true) === 'POST'){
            $request_json = file_get_contents('php://input');
            $request_data = json_decode($request_json, true);
            if($this->validateEsbStatData($request_data)) {
                $this->load->model('DeviceStats_model','devicestats');
                $id = $this->devicestats->createDeviceEsbStat($request_data);
                if($id) {
                    response(true ,'Device Stat created successfully.', [], []);
                }
            }

            response(false ,'Something went wrong.', [], []);
        }else{
            response(false ,'Unsupported message', [], []);
        }
    }

    public function validateEsbStatData($data) {
        $errors = [];
        if(!isset($data['door_serial']) || empty($data['door_serial'])) {
            $errors[] = "`door_serial` is required.";
        }
        if(!isset($data['badge_seen_packets']) || $data['badge_seen_packets'] === "") {
            $errors[] = "`badge_seen_packets` is required.";
        }
        if(!isset($data['esb_failures']) || $data['esb_failures'] === "") {
            $errors[] = "`esb_failures` is required.";
        }
        if(!isset($data['packets_exchanged']) || $data['packets_exchanged'] === "") {
            $errors[] = "`packets_exchanged` is required.";
        }
        if(!isset($data['pir_triggers']) || $data['pir_triggers'] === "") {
            $errors[] = "`pir_triggers` is required.";
        }

        if(count($errors) > 0){
            response(400 ,'Unable to created device stat.', [],$errors);
        }
        return true;
    }

    public function badgeBatteryState() {
        if($this->input->method(true) === 'POST'){
            $request_json = file_get_contents('php://input');
            $request_data = json_decode($request_json, true);
            if($this->validateBadgeBatteryState($request_data)) {
                $this->load->model('BadgeBatteryState_model','badgestate');
                $id = $this->badgestate->createBadgeBatteryState($request_data);
                if($id) {
                    response(true ,'Badge Battery State created successfully.', [], []);
                }
            }

            response(false ,'Something went wrong.', [], []);
        }else{
            response(false ,'Unsupported message', [], []);
        }
    }

    public function validateBadgeBatteryState($data) {
        $errors = [];
        if(!isset($data['badge_serial']) || empty($data['badge_serial'])) {
            $errors[] = "`badge_serial` is required.";
        }
        if(!isset($data['b_batt_voltage_v']) || $data['b_batt_voltage_v'] === "") {
            $errors[] = "`b_batt_voltage_v` is required.";
        }
        if(!isset($data['b_firmware_version']) || $data['b_firmware_version'] === "") {
            $errors[] = "`b_firmware_version` is required.";
        }
        if(!isset($data['system_resets']) || $data['system_resets'] === "") {
            $errors[] = "`system_resets` is required.";
        }
        if(!isset($data['wake_duration_s']) || $data['wake_duration_s'] === "") {
            $errors[] = "`wake_duration_s` is required.";
        }

        if(count($errors) > 0){
            response(400 ,'Unable to create Badge Battery State.', [],$errors);
        }
        return true;
    }
     
    public function badgeEsbState() {
        if($this->input->method(true) === 'POST'){
            $request_json = file_get_contents('php://input');
            $request_data = json_decode($request_json, true);
            if($this->validateBadgeEsbState($request_data)) {
                $this->load->model('BadgeBatteryState_model','badgestate');
                $id = $this->badgestate->createBadgeEsbState($request_data);
                if($id) {
                    response(true ,'Badge Esb State created successfully.', [], []);
                }
            }

            response(false ,'Something went wrong.', [], []);
        }else{
            response(false ,'Unsupported message', [], []);
        }
    }

    public function validateBadgeEsbState($data) {
        $errors = [];
        if(!isset($data['badge_serial']) || empty($data['badge_serial'])) {
            $errors[] = "`badge_serial` is required.";
        }
        if(!isset($data['lfr_triggers']) || $data['lfr_triggers'] === "") {
            $errors[] = "`lfr_triggers` is required.";
        }
        if(!isset($data['b_packets_saved']) || $data['b_packets_saved'] === "") {
            $errors[] = "`b_packets_saved` is required.";
        }
        if(!isset($data['b_packets_exchanged']) || $data['b_packets_exchanged'] === "") {
            $errors[] = "`b_packets_exchanged` is required.";
        }
        if(!isset($data['b_crc_failures']) || $data['b_crc_failures'] === "") {
            $errors[] = "`b_crc_failures` is required.";
        }
        if(!isset($data['b_esb_failures']) || $data['b_esb_failures'] === "") {
            $errors[] = "`b_esb_failures` is required.";
        }
        if(!isset($data['b_rssi_failures']) || $data['b_rssi_failures'] === "") {
            $errors[] = "`b_rssi_failures` is required.";
        }

        if(count($errors) > 0){
            response(400 ,'Unable to create Badge Battery State.', [],$errors);
        }
        return true;
    }
     
}
?>