<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Devices extends MY_Controller {

	protected $isAdmin;

	public function __construct()
	{
		$this->load->model('devices_model','devices');
		$this->load->model('managers_model','manager');
		$this->load->model('customers_model','customer');
		$this->load->model('DevicesHistory_model','history');
		if(!$this->session->userdata('isAdmin'))
		{
			redirect('admin/login');
		}

		//print_r($this->session->userdata());die;
		$this->isAdmin=$this->session->userdata('isAdmin');
		if(!$this->isAdmin)
		{
			redirect('admin/login');
		}
	}

	public function index()
	{
		$data = array(
			'pageTitle' =>'Badges',
			'devices'=>$this->devices->getDevices(),
			'managers' => $this->manager->getManagers()
		);
		$this->baseloader->adminviews('devices',$data);
	}

	function create()
	{
		$manager=$this->manager->getManagers();
		$data=array('pageTitle'=>'Add Badge','action'=>base_url("admin/devices/createDevice"), 'managers'=>$manager);
		$this->baseloader->adminviews('editDevice',$data);
	}

	function createDevice()
	{
		
		if($this->devices->getDeviceBySerial($_POST['vSerial'])>=1){
			$this->session->set_flashdata('error','This Device Already Exist');
			redirect('admin/devices/create');
		}
		$_POST['dUpdated_at'] = date("Y-m-d h:i:s");
		$_POST['vCreatedById']='Admin';
		$assigned_user = $_POST['vAssigned_User_ID'];
		unset($_POST['vAssigned_User_ID']);
		$data=$_POST;
		$create=$this->devices->createDevice($data);
		if($create)
		{
			if($assigned_user != "" && $assigned_user != '-Select User-'){
				$device = $this->devices->getDeviceById($create);
				$assignedCust = $this->customer->getCustomerById($assigned_user);
				# if customer already has a device assigned
				if($assignedCust->vSerial != ''){
					# get device id and find device
					// $existingCustDevice = $assignedCust->vSerial;
					$existingDevice = $this->devices->getDeviceBySerial($assignedCust->vSerial);
					// if($existingDevice){
						$history = $this->history->getDeviceActiveHistory($existingDevice->vSerial,$assignedCust->iId);
						# remove device
						$this->customer->updateCustomer($assignedCust->iId, ['vSerial' => '']);
						# update existing device history
						if($history){
							$this->history->updateHistory($history->id, [
								'assigned_to' => date('Y-m-d H:i:s'),
								'updated_at' => date('Y-m-d H:i:s')
								]
							);
						}
					// }
				}
				$this->customer->updateCustomer($assignedCust->iId, ['vSerial' => $device->vSerial]);
				# Assign new device
				$this->history->createHisory([
					'user_id' => $assignedCust->iId,
					'device_id' => $device->iId,
					'vSerial' => $device->vSerial,
					'assigned_from' => date('Y-m-d H:i:s'),
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
				]);
			}
			$this->session->set_flashdata('success','Device Added successfully');
			redirect('admin/devices');
		}

	}

	function editDevice($id)
	{ 
		$devices = $this->devices->getDeviceById($id);
		$data = array(
			'devices'=>$devices,
			'pageTitle'=>'Edit Badge',
			'action'=>"admin/devices/updateDevice/$id", 
			'managers'=>$this->manager->getManagers(),
			'customers'=> $this->customer->getCustomerByFacility($devices->vAssigned_Facility_ID)
		);
		$this->baseloader->adminviews('editDevice',$data);
	}

	function updateDevice($id)
	{	
		$this->session->editedDeviceDetails = $_POST;
		if($this->devices->getUpdateSerial($_POST['vSerial'],$id)>=1){
			$this->session->set_flashdata('error','This Device Already Exist');
			redirect('admin/devices/editDevice/'.$id);
		}
		if($_POST['vAssigned_User_ID'] != ""){
			// Assign this device to selected Userid
			$device = $this->devices->getDeviceById($id);
			$existingCust = $this->customer->getCustomerBySerial($device->vSerial);
			$newCust = $this->customer->getCustomerById($_POST['vAssigned_User_ID']);
			if($existingCust){
				// Remove from existing user and update history - update history
				$this->customer->updateCustomer($existingCust->iId, ['vSerial' => '']);
				$history = $this->history->getDeviceActiveHistory($device->vSerial,$existingCust->iId);
				if($history){
					$this->history->updateHistory($history->id, [
						'assigned_to' => date('Y-m-d H:i:s'),
						'updated_at' => date('Y-m-d H:i:s')
						]
					);
				}
			}
			if($newCust->vSerial != ''){
				// find the existing device, remove the existing device and create history 
				$existingDevice = $this->devices->getDeviceBySerial($newCust->vSerial);
				$history = $this->history->getDeviceActiveHistory($existingDevice->vSerial,$newCust->iId);
				$this->customer->updateCustomer($newCust->iId, ['vSerial' => '']);
				if($history){
					$this->history->updateHistory($history->id, [
						'assigned_to' => date('Y-m-d H:i:s'),
						'updated_at' => date('Y-m-d H:i:s')
						]
					);
				}
			}
			// Assign VSerial to new user - Create new history
			$this->customer->updateCustomer($_POST['vAssigned_User_ID'], ['vSerial' => $device->vSerial]);
			$this->history->createHisory([
				'user_id' => $_POST['vAssigned_User_ID'],
				'device_id' => $device->iId,
				'vSerial' => $device->vSerial,
				'assigned_from' => date('Y-m-d H:i:s'),
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
				]);
		}
		unset($_POST['vAssigned_User_ID']);
		$data=$_POST;
		$result=$this->devices->updateDevice($id,$data);
		if($result)
		{
			$this->session->unset_userdata('editedDeviceDetails');
			$this->session->set_flashdata('success','Device updated successfully');
			redirect('admin/devices');
		}
	}

	function deleteDevice($id)
	{
		$device = $this->devices->getDeviceById($id);
		$customer = $this->customer->getCustomerBySerial($device->vSerial);
		
		if($customer) {
			$history = $this->history->getDeviceActiveHistory($device->vSerial, $customer->iId);
			$this->history->updateHistory($history->id, [
					'assigned_to' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
			]);
			$this->customer->updateCustomer($customer->iId, ['vSerial' => '']);
		}

		$delete = $this->devices->deleteDevice($id);
		if($delete) {
			$this->session->set_flashdata('success','Device deleted successfully');
			redirect('admin/devices');
		}
	}

	function history($vSerial){
		// $history = $this->history->getDeviceHistory($vSerial);
		$data = array(
			'pageTitle' =>'Badges History',
			// 'history' => $history,
			'vSerial' => $vSerial
		);
		$this->baseloader->adminviews('devices_history',$data);;
	}

	public function historylist_dt($vSerial){
		$data = $row = array();
		$historyData = $this->history->getDeviceHistoryRows($_POST, $vSerial);
        $i = $_POST['start'];
        foreach($historyData as $history){
			$i++;
			$assigned = $history->assigned_to;
			if($history->assigned_to == '0000-00-00 00:00:00' || $history->assigned_to == null){
				$assigned =  'Still Assigned';
			}

			$data[] = array(
				$i, 
				$history->vSerial, 
				$history->vFirstName.' '.$history->vLastName,
				$history->vRole,
				$history->assigned_from,
				$assigned
			);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->history->countAll($vSerial),
            "recordsFiltered" => $this->history->countFiltered($_POST, $vSerial),
            "data" => $data,
        );        
        echo json_encode($output);
	}

	function getDevicesByFacility($vFacility){
		$devices = $this->devices->getDevices($vFacility);
		$response = [];
		$response['success'] = true;
		$response['devices'] = $devices;
		echo json_encode($response);
	}

	public function devicelist_dt($facility_id = 'ALL'){
		$this->session->deviceFacility = $facility_id;
		$data = $row = array();
		$deviceData = $this->devices->getDeviceRows($_POST, $facility_id);
		// var_dump($userData);exit;
        $i = $_POST['start'];
        foreach($deviceData as $device){
			$i++;
			
			if($device->vCreatedById == 'Admin'){
				$created_by =  $device->vCreatedById;
			}else{
				if($device->vCreatedById == $device->vAssigned_Facility_ID){
					$created_by = $device->vEmail;
				}else{
					if($createdby = $this->manager->getManagerById($device->vCreatedById)){
						$created_by = $createdby->vEmail;} 
					else { 
						$created_by = "(Manager Deleted)";
					}
				}
			}
			$data[] = array(
				$i, $device->vSerial,$device->vFacility,$created_by,'
					<a href="'.base_url('admin/devices/editDevice/'.$device->iId).'" class="btn btn-flat btn-sm btn-info"><i class="fa fa-edit"></i></a>
            		<a onclick="return confirm(\'Are you sure you want to delete this device?\');" href="'.base_url('admin/devices/deleteDevice/'.$device->iId).'" class="btn btn-flat btn-sm btn-danger"><i class="fa fa-trash"></i></a>
					<a href="'.base_url('admin/devices/history/'.$device->vSerial).'" title="Device History" class="btn btn-flat btn-sm btn-success"><i class="fa fa-history"></i></a>
				'
			);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->devices->countAll(),
            "recordsFiltered" => $this->devices->countFiltered($_POST, $facility_id),
            "data" => $data,
        );
        
        echo json_encode($output);
	}

	function bulkDelete() {
		$result = $this->devices->markAsDelete();
		if($result){
			redirect('admin/devices/tobedelted/');
		}else{
			$this->session->set_flashdata('error','No Record Found.');
			redirect('admin/devices');
		}
	}

	function tobedelted(){
		$devices = $this->devices->getToBeDeleted();
		$data = array(
			'pageTitle'=>'Bulk Delete Devices',
			'action'=>"admin/devices/bulkDeleteConfirm/",
			'devices' => $devices,
		);
		$this->baseloader->adminviews('bulk_delete_devices_list',$data);
	}
	
	public function upload() {

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$facility = $this->input->post('vAssigned_Facility_ID');
			$config['upload_path']          = './uploads/facility_bin/';
			$config['allowed_types']        = 'csv';
			$config['max_size']             = 100; //kb
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('csv_file')){
				$fileErr = $this->upload->display_errors();
				if(strpos($fileErr, "filetype you are attempting to upload is not allowed")){
					$fileErr = 'The filetype is not allowed” -> “CSV required, file is not CSV';
				}
				$this->session->set_flashdata('error',$fileErr);
				redirect('admin/devices/upload');
			}else {
				$file_data = $this->upload->data();
				$file_name = $file_data['file_name'];
				$file_path = $file_data['full_path'];
				if (!mb_check_encoding(file_get_contents($file_path), 'UTF-8')) {
					$this->session->set_flashdata('error','Detect unicode error, ex UTF-16 -> “CSV type must be UTF-8');
					redirect('admin/devices/upload');
				}
				$this->load->library('CSVReader');
				$csvData = $this->csvreader->parse_csv($file_path);
				if(!empty($csvData)){
					$this->load->model('DeviceFileUploads_model','fileupload');
					$this->load->model('UploadedDevice_model','devicePreview');
					$data['uploaded_by'] = $this->session->userdata('iId');
					$data['file_name'] = $file_name;
					$upload_id = $this->fileupload->createFileUpload($data);
					if($upload_id){
						$valid_header = ['vserial'];
						foreach($csvData as $row){
							$orgKeys = array_keys($row);
							$csv_header = array_map('trim',array_map('strtolower', $orgKeys));
							if($valid_header !== $csv_header){
								$this->session->set_flashdata('error','Bad columns. Format must be '.implode(', ', $valid_header));
								redirect('admin/devices/upload');
							}
							$ifExistingDevice = $this->devices->getDeviceBySerial($row[$orgKeys[0]]);
							if($ifExistingDevice){
								// continue;
							}
							// -----------------------------------------------------------------------
							// $updated_at = date("Y-m-d h:i:s");
							// $device = [
							// 	'vSerial' => trim($row['vSerial']),
							// 	'vAssigned_Facility_ID'=> $facility,
							// 	'dUpdated_at' => $updated_at,
							// 	'vCreatedById' => 'Admin',
							// ];

							// $create = $this->devices->createDevice($device);
							// -------------------------------------------------------------------------
							$device =  [
								'upload_id' => $upload_id,
								'device_id' => $row[$orgKeys[0]],
								'vAssigned_Facility_ID'=> $facility,
								'ignored' => 0
							];
							$create = $this->devicePreview->createDevice($device);


							// if($create){
								// if(!empty($assigned_user)){
								// 	$device = $this->devices->getDeviceById($create);
								// 	$assignedCust = $this->customer->getCustomerById($assigned_user);
								// 	if($assignedCust && $assignedCust->vSerial != ''){
								// 		# get device id and find device
								// 		// $existingCustDevice = $assignedCust->vSerial;
								// 		$existingDevice = $this->devices->getDeviceBySerial($assignedCust->vSerial);
								// 			$history = $this->history->getDeviceActiveHistory($existingDevice->vSerial,$assignedCust->iId);
								// 			# remove device
								// 			$this->customer->updateCustomer($assignedCust->iId, ['vSerial' => '']);
								// 			# update existing device history
								// 			if($history){
								// 				$this->history->updateHistory($history->id, [
								// 					'assigned_to' => date('Y-m-d H:i:s'),
								// 					'updated_at' => date('Y-m-d H:i:s')
								// 					]
								// 				);
								// 			}
								// 			$this->customer->updateCustomer($assignedCust->iId, ['vSerial' => $device->vSerial]);
								// 			# Assign new device
								// 			$this->history->createHisory([
								// 				'user_id' => $assignedCust->iId,
								// 				'device_id' => $device->iId,
								// 				'vSerial' => $device->vSerial,
								// 				'assigned_from' => date('Y-m-d H:i:s'),
								// 				'created_at' => date('Y-m-d H:i:s'),
								// 				'updated_at' => date('Y-m-d H:i:s')
								// 			]);
								// 	}
								// }
							// }
						}
						$this->session->set_flashdata('success','CSV uploaded successfully.');
						redirect('admin/devices/uploadpreview/'.$upload_id);
					}else{
						$this->session->set_flashdata('error','Something we wrong, please try later.');
						redirect('admin/devices/upload');
					}
				}else{
					$this->session->set_flashdata('error','No data in csv file.');
					redirect('admin/devices/upload');
				}
			}
		}
		$managers = $this->manager->getManagers();
		$data = array(
			'managers'=>$managers,
			'pageTitle'=>'Upload Devices',
			'action'=>"admin/devices/upload"
		);
		$this->baseloader->adminviews('upload_devices',$data);
	}

	public function ignoredeleteddevice(){
		$device = $this->input->post('device');
		$ignore = $this->input->post('ignore');
		if($this->devices->updateDeletedDevice($device, ['ignored' => $ignore]))
			$response['success'] = true;
		else
			$response['success'] = false;
		echo json_encode($response);
	}

	public function deletedevices() {
		if($this->devices->deleteSelectedDevices())
			$this->session->set_flashdata('success','Records deleted successfully');
		else
			$this->session->set_flashdata('error','Something went wrong.');

		redirect('admin/devices');
	}

	public function uploadpreview($id){
		$this->load->model('DeviceFileUploads_model','deviceFile');
		$this->load->model('UploadedDevice_model','devicePreview');
		$upload = $this->deviceFile->getFileUploadById($id);
		if($upload['is_processed'] > 0){
			$this->session->set_flashdata('error','Records already processed.');
			redirect('admin/devices/uploadpreview/'.$upload_id);
		}
		$devices = $this->devicePreview->getUploadedDevicesByUploadId($id);
		$data = array(
			'upload'=>$upload,
			'uploaded_devices' => $devices,
			'pageTitle'=>'Uploaded Devices'
		);

		$this->baseloader->adminviews('uploaded_devices',$data);
	}

	public function uploadedlist_dt($upload_id){
		$this->load->model('DeviceFileUploads_model','deviceFile');
		$this->load->model('UploadedDevice_model','devicePreview');
		$data = $row = array();
        $deviceData = $this->devicePreview->getRows($_POST, $upload_id);
		$fileStatus = $this->deviceFile->getFileUploadById($upload_id)['is_processed'];
		$disabled = ($fileStatus && $fileStatus == '1') ? 'disabled' : '';
        $i = $_POST['start'];
        foreach($deviceData as $device){
			$i++;
			$checked = $device->ignored > 0 ? 'checked' : '';
			$data[] = array($i, $device->device_id,
			'<input type="checkbox" class="form-check-input" '. $disabled .' onclick="ingnoredevice(this)" id="'.$device->id.'" '.$checked.'>'
		);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->devicePreview->countAll(),
            "recordsFiltered" => $this->devicePreview->countFiltered($_POST, $upload_id),
            "data" => $data,
        );
        
        echo json_encode($output);
	}

	public function ignoreuploaded(){
		$this->load->model('UploadedDevice_model','devicePreview');
		$device_id = $this->input->post('device');
		$ignore = $this->input->post('ignore');
		if($this->devicePreview->updateUploadedDevice($device_id, ['ignored' => $ignore]))
			$response['success'] = true;
		else
			$response['success'] = false;
		echo json_encode($response);
	}

	public function registerdevices($upload_id) {
		$this->load->model('DeviceFileUploads_model','deviceFile');
		$this->load->model('UploadedDevice_model','devicePreview');
		$upload = $this->deviceFile->getFileUploadById($upload_id);
		if($upload['is_processed'] > 0){
			$this->session->set_flashdata('error','Records already processed.');
			redirect('admin/devices/uploadpreview/'.$upload_id);
		}
		$select = $this->devicePreview->getDevicesToRegister($upload_id);
		if($select->num_rows()){
			$inserted = $this->devices->registerUploadedDevices($select->result_array());
			if($inserted){
				$this->deviceFile->updateFileUpload($upload_id, ['is_processed' => '1']);
				$this->session->set_flashdata('success','Devices inserted successfully.');
				redirect('admin/devices');
			}else{
				echo 2; exit;
				$this->session->set_flashdata('error','Something went wrong, please try later.');
				redirect('admin/devices/uploadpreview/'.$upload_id);
			}
		}else{
			$this->session->set_flashdata('error','No device to insert.');
			redirect('admin/devices/uploadpreview/'.$upload_id);
		}
	}
}
