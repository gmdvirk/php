<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends MY_Controller {

	protected $isAdmin;

	public function __construct()
	{
		$this->load->model('customers_model','customer');
		$this->load->model('devices_model','devices');
		$this->load->model('managers_model','manager');
		$this->load->model('DevicesHistory_model','history');
		$this->load->model('UploadedUsers_model', 'uploaded_user');
		$this->load->model('Role_model','roles');
		$this->load->model('Unit_model','units');
		$this->load->helper('json');
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
			'pageTitle' =>'Users',
			'customers'=>$this->customer->getCustomers(),
			'managers' => $this->manager->getManagers()
		);
		$this->baseloader->adminviews('customers',$data);
	}


	public function changeStatus($id,$status)
	{
		$result=$this->customer->changeStatus($id,$status);
		if($result)
		{
			redirect('admin/customers');
		}

	}

	function create()
	{
		$devices = $this->devices->getDevices();
		$manager = $this->manager->getManagers();
		$data=array(
			'pageTitle'=>'Create User',
			'action'=>base_url("admin/customers/createCustomer"),
			'devices' => $devices, 
			'managers'=>$manager,
			'roles' => $this->roles->getRoles(),
			'units' => $this->units->getUnits()
		);
		$this->baseloader->adminviews('editCustomer',$data);
	}

	function createCustomer()
	{
		if($_POST['vSerial'] == 'unassign') $_POST['vSerial'] = '';
		$this->session->editedCustDetails = $_POST;
		if(isset($_POST['vSerial']) && $_POST['vSerial'] != '' && $this->customer->getCustomerBySerial($_POST['vSerial']) >= 1){
			$this->session->set_flashdata('error','This Device Not Available');
			redirect('admin/customers/create');
		} 
		
		// $_POST['vPassword'] = md5($_POST['vPassword']);
		$_POST['eStatus']='1';
		$_POST['dUpdated_at'] = date("Y-m-d h:i:s");
		$_POST['vCreatedById']='Admin';
		// $_POST['vJsonString'] = trim($_POST['vJsonString']);
		// if(!empty($_POST['vJsonString'])  && !is_json_valid($_POST['vJsonString'])){
		// 	$this->session->set_flashdata('error','Invalid Json');
		// 	redirect('admin/customers/create/');
		// }else{
		// 	$_POST['vJsonString'] = minify_json($_POST['vJsonString']);
		// }
		
		$data=$_POST;
		$customer_id = $this->customer->createCustomer($data);
		if($customer_id)
		{
			$customer = $this->customer->getCustomerById($customer_id);
			$newDevice = $this->devices->getDeviceBySerial($_POST['vSerial']);
			if($newDevice){
				$this->history->createHisory([
					'user_id' => $customer->iId,
					'device_id' => $newDevice->iId,
					'vSerial' => $newDevice->vSerial,
					'assigned_from' => date('Y-m-d H:i:s'),
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
				]);
			}
			$this->session->unset_userdata('editedCustDetails');
			// $this->session->set_flashdata('success','Customer created successfully');
			redirect('admin/customers/success?type=c');
		}

	}

	function editCustomer($id)
	{
		$customer=$this->customer->getCustomerById($id);
		$devices = $this->devices->getDevices();
		$manager = $this->manager->getManagers();
		$data=array(
			'customer'=>$customer,
			'pageTitle'=>'Edit User',
			'action'=>"admin/customers/updateCustomer/$id",
			'devices'=>$devices,
			'managers'=>$manager,
			'roles' => $this->roles->getRoles(),
			'units' => $this->units->getUnits()
		);
		$this->baseloader->adminviews('editCustomer',$data);

	}

	function updateCustomer($id)
	{	
		$this->session->editedCustDetails = $_POST;
		$customer = $this->customer->getCustomerById($id);
		if($this->customer->getUpdateSerial($_POST['vSerial'],$id)>=1 && $_POST['vSerial'] != 'unassign'){
			$this->session->set_flashdata('error','This Device Not Available');
			redirect('admin/customers/editCustomer/'.$id);
		}
		
		// remove old device and update history
		if($customer->vSerial != $_POST['vSerial']){
			if($customer->vSerial != ''){
				$oldDevice = $this->devices->getDeviceBySerial($customer->vSerial);
				$history = $this->history->getDeviceActiveHistory($oldDevice->vSerial,$id);
				$this->history->updateHistory($history->id, [
						'assigned_to' => date('Y-m-d H:i:s'),
						'updated_at' => date('Y-m-d H:i:s')
					]);
			}

			$newDevice = $this->devices->getDeviceBySerial($_POST['vSerial']);
			if($newDevice){
				$this->history->createHisory([
					'user_id' => $id,
					'device_id' => $newDevice->iId,
					'vSerial' => $newDevice->vSerial,
					'assigned_from' => date('Y-m-d H:i:s'),
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
				]);
			}

		}

		$facility= $this->devices->getDeviceBySerial($_POST['vSerial']);
		if(!empty($_POST['vPassword']))
		{
			$_POST['vPassword']=md5($_POST['vPassword']);
		}
		else
		{
			unset($_POST['vPassword']);
		}
		unset($_POST['vJsonString']);
		unset($_POST['jsonAccepted']);
		$_POST['vSerial'] = $_POST['vSerial'] == 'unassign' ? '' : $_POST['vSerial'];
		// $_POST['vJsonString'] = trim($_POST['vJsonString']);
		// if(!empty($_POST['vJsonString']) && !is_json_valid($_POST['vJsonString'])){
		// 	$this->session->set_flashdata('error','Invalid Json');
		// 	redirect('admin/customers/editCustomer/'.$id);
		// }else{
		// 	$_POST['vJsonString'] = minify_json($_POST['vJsonString']);
		// }

		$data=$_POST;
		$result=$this->customer->updateCustomer($id,$data);
		if($result)
		{
			$this->session->unset_userdata('editedCustDetails');
			$this->session->set_flashdata('success','Record updated successfully');
			redirect('admin/customers');
		}
	}

	function deleteCustomer($id)
	{
		$delete=$this->customer->deleteCustomer($id);
		if($delete)
		{
			$this->session->set_flashdata('success','Record deleted successfully');
			redirect('admin/customers');
		}
	}
	
	function fetch_BadgeSerial($vSerial = NULL)
	{
		if($this->input->post('vFacility')!=''){
			echo  $this->customer->BadgeSerial($this->input->post('vFacility'),$vSerial);
		}
	}

	function getCustomersByFacility($vFacility){
		$customers = $this->customer->getCustomerByFacility($vFacility);
		$response = [];
		$response['success'] = true;
		$response['customers'] = $customers;
		echo json_encode($response);
	}

	function edit_json($id){
		$customer = $this->customer->getCustomerById($id);
		// echo '<pre>';
		$manager = $this->manager->getManagerById($customer->vAssigned_Facility_ID);
		$manager_json_data = json_decode($manager->vJsonString, true);
		// var_dump($manager_json_data);exit;
		if(isset($_GET['copy'])){
			$json_data = $manager_json_data;
		}else{
			$json_data = json_decode($customer->vJsonString, true);
		}
		$json_accepted_data = json_decode($customer->jsonAccepted, true);
		
		$data = array(
			'customer'=>$customer,
			'pageTitle'=>'User Badge Settings override',
			'action'=>"admin/customers/updateJson/$id",
			'json' => $json_data,
			'json_accepted' => $json_accepted_data,
			'manager_json' => $manager_json_data,
			'cancelUrl' => 'admin/customers/editCustomer/'.$customer->iId,
			'copyUrl' =>  'admin/customers/edit-json/'.$customer->iId.'?copy=1',
		);
		$this->baseloader->adminviews('edit_json',$data);
	}

	public function updateJson($id){
		$post = $this->input->post();
		// $jsonArray = [
		// 	['ABHR_NO_DOOR' => $post['ABHR_NO_DOOR']],
		// 	['DOOR_NO_ABHR_BEEP' => $post['DOOR_NO_ABHR_BEEP']],
		// 	['MASK_SETTING' => $post['MASK_SETTING']],
		// 	['DEBUG_BMODE' => $post['DEBUG_BMODE']],
		// 	['BEEP_STG' => $post['BEEP_STG']],
		// 	['DOOR_RSSI_THRESHOLD' => $post['DOOR_RSSI_THRESHOLD']],
		// 	['ABHR_RSSI_THRESHOLD' => $post['ABHR_RSSI_THRESHOLD']],
		// 	['SOAP_RSSI_THRESHOLD' => $post['SOAP_RSSI_THRESHOLD']],
		// 	['BADGE_RSSI_THRESHOLD' => $post['BADGE_RSSI_THRESHOLD']],
		// 	['BED_RSSI_THRESHOLD' => $post['BED_RSSI_THRESHOLD']],
		// 	['PROV_RSSI_THRESHOLD' => $post['PROV_RSSI_THRESHOLD']],
		// 	['COMM_RSSI_THRESHOLD' => $post['COMM_RSSI_THRESHOLD']],
		// 	['SOAP_QUALIFY_PERIOD' => $post['SOAP_QUALIFY_PERIOD']],
		// 	['BED_ZONE_PERIOD' => $post['BED_ZONE_PERIOD']],
		// 	['BEEP_ON_RSSIPASS' => $post['BEEP_ON_RSSIPASS']],
		// 	['BEEP_ON_RSSIFAIL' => $post['BEEP_ON_RSSIFAIL']]
		// ];
		// echo '<pre>';
		// var_dump($jsonArray);exit;

		if(isset($post['empty_json'])){
			$postJson = NULL;
		} else {
			$postJson = json_encode($post);
		}
		
		$result = $this->customer->updateCustomer($id,[ 'vJsonString' => $postJson]);
		if($result){
			$this->session->set_flashdata('success','Json Data updated successfully');
		}else{
			$this->session->set_flashdata('error','Something went wrong. Please try later.');
		}
		redirect('admin/customers/editCustomer/'.$id);
	}

	public function customerlist_dt($facility_id = 'ALL'){
		$hideUnassignedUsers = $_GET['hideUnassignedUsers'];
		$this->session->customerFacility = $facility_id;
		$data = $row = array();
		$userData = $this->customer->getCustomerRows($_POST, $facility_id, $hideUnassignedUsers);
		// var_dump($userData);exit;
        $i = $_POST['start'];
        foreach($userData as $user){
			$i++;
			$data[] = array($i, $user->vFirstName, $user->vLastName,$user->vEmail, $user->vRole, $user->vUnit,$user->vFacility,$user->vSerial,
			'<a href="'.base_url('admin/customers/editCustomer/'.$user->iId).'" class="btn btn-flat btn-sm btn-info"><i class="fa fa-edit"></i></a>
			<a onclick="return confirm(\'Are you sure you want to delete this customer?\');" href="'.base_url('admin/customers/deleteCustomer/'.$user->iId).'" class="btn btn-flat btn-sm btn-danger"><i class="fa fa-trash"></i></a>
			<a href="'.base_url('admin/customers/devicehistory/'.$user->iId).'" title="User Device History" class="btn btn-flat btn-sm btn-success"><i class="fa fa-history"></i></a>'
		);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->customer->countAll(),
            "recordsFiltered" => $this->customer->countFiltered($_POST, $facility_id, $hideUnassignedUsers),
            "data" => $data,
        );
        
        echo json_encode($output);
	}

	public function upload(){
		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			// form submitted
			$facility = $this->input->post('vAssigned_Facility_ID');
			$config['upload_path']          = './uploads/facility_bin/';
			$config['allowed_types']        = 'csv';
			$config['max_size']             = 100; //kb
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			// echo '<pre>';
			if ( ! $this->upload->do_upload('csv_file')){
				$fileErr = $this->upload->display_errors();
				if(strpos($fileErr, "filetype you are attempting to upload is not allowed")){
					$fileErr = 'The filetype is not allowed” -> “CSV required, file is not CSV';
				}
				$this->session->set_flashdata('error',$fileErr);
				redirect('admin/customers/upload');
			}else {
				$file_data = $this->upload->data();
				$file_name = $file_data['file_name'];
				$file_path = $file_data['full_path'];
				if (!mb_check_encoding(file_get_contents($file_path), 'UTF-8')) {
					$this->session->set_flashdata('error','Detect unicode error, ex UTF-16 -> “CSV type must be UTF-8');
					redirect('admin/customers/upload');
				}
				$this->load->library('CSVReader');
				$csvData = $this->csvreader->parse_csv($file_path);
				// var_dump($csvData);exit;
				if(!empty($csvData)){
					$this->load->model('UserFileUploads_model','fileupload');
					$data['uploaded_by'] = $this->session->userdata('iId');
					$data['file_name'] = $file_name;
					$upload_id = $this->fileupload->createFileUpload($data);
					if($upload_id){
						$valid_header = ['first','last','role','unit','start','end'];
						foreach($csvData as $row){
							$orgKeys = array_keys($row);
							$csv_header = array_map('trim',array_map('strtolower', $orgKeys));
							if($valid_header !== $csv_header){
								$this->session->set_flashdata('error','Bad columns. Format must be '.implode(', ', $valid_header));
								redirect('admin/customers/upload');
							}
							$customer = [
								'upload_id' => $upload_id,
								'facility_id' => $facility,
								'firstname' => $row[$orgKeys[0]],
								'lastname' => $row[$orgKeys[1]],
								'role' => $row[$orgKeys[2]],
								'unit' => $row[$orgKeys[3]],
								'start_date' => $this->validateDate($row[$orgKeys[4]]) ? $row[$orgKeys[4]] : '',
								'end_date' => $this->validateDate($row[$orgKeys[5]]) ? $row[$orgKeys[5]] : '',
							];
							$customer_array [] = $customer;
						}
						// var_dump($customer_array);exit;
						// $this->load->model('UploadedUsers_model', 'uploaded_user');
						$userUploaded = $this->uploaded_user->createBulkUploadedUsers($customer_array);
						if($userUploaded){
							$this->session->set_flashdata('success','File uploaded successfully.');
							redirect('admin/customers/uploadedlist/'.$upload_id);
						}
					}else{
						$this->session->set_flashdata('error','Something we wrong, please try later.');
						redirect('admin/customers/upload');
					}
				}else{
					$this->session->set_flashdata('error','No data in csv file.');
					redirect('admin/customers/upload');
				}
			}
		}
		$managers = $this->manager->getManagers();
		$data = array(
			'managers'=>$managers,
			'pageTitle'=>'Upload Users',
			'action'=>"admin/customers/upload"
		);
		$this->baseloader->adminviews('upload_users',$data);
	}
	public function validateDate($date, $format = 'Y-m-d')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}

	public function uploadedlist($upload_id){
		$this->load->model('UserFileUploads_model','fileupload');
		// $this->load->model('UploadedUsers_model', 'uploaded_user');
		$upload = $this->fileupload->getFileUploadById($upload_id);
		if(!$upload){
			$upload['id'] = '0';
		}
		$uploaded_users = $this->uploaded_user->getUploadedUsersByUploadId($upload_id);
		$data = array(
			'upload'=>$upload,
			'uploaded_users' => $uploaded_users,
			'pageTitle'=>'Uploaded Users'
		);
		$this->baseloader->adminviews('uploaded_users',$data);

	}

	public function uploadedlist_dt($upload_id){
		$this->load->model('UserFileUploads_model','fileupload');
		// $this->load->model('UploadedUsers_model', 'uploaded_user');
		$data = $row = array();
        $userData = $this->uploaded_user->getRows($_POST, $upload_id);
		$fileStatus = $this->fileupload->getFileUploadById($upload_id)['is_processed'];
		$disabled = ($fileStatus && $fileStatus == '1') ? 'disabled' : '';
        $i = $_POST['start'];
        foreach($userData as $user){
			$i++;
			$checked = $user->ignored > 0 ? 'checked' : '';
			$data[] = array($i, $user->firstname, $user->lastname, $user->role, $user->unit, $user->start_date, $user->end_date,
			'<input type="checkbox" class="form-check-input" '. $disabled .' onclick="ingnoreuser(this)" id="'.$user->id.'" '.$checked.'>'
		);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->uploaded_user->countAll(),
            "recordsFiltered" => $this->uploaded_user->countFiltered($_POST, $upload_id),
            "data" => $data,
        );
        
        echo json_encode($output);
	}

	public function registerusers($upload_id) {
		$this->load->model('UserFileUploads_model','fileupload');
		$upload = $this->fileupload->getFileUploadById($upload_id);
		if($upload['is_processed'] > 0){
			$this->session->set_flashdata('error','Records already processed.');
			redirect('admin/customers/uploadedlist/'.$upload_id);
		}
		$select = $this->uploaded_user->getUsersToRegister($upload_id);
		if($select->num_rows()){
			$inserted = $this->customer->registerUploadedUsers($select->result_array());
			if($inserted){
				$this->fileupload->updateFileUpload($upload_id, ['is_processed' => '1']);
				// $this->session->set_flashdata('success','Users inserted successfully.');
				// redirect('admin/customers/uploadedlist/'.$upload_id);
				redirect('admin/customers/success?type=u');
			}else{
				$this->session->set_flashdata('error','Something went wrong, please try later.');
				redirect('admin/customers/uploadedlist/'.$upload_id);
			}
		}else{
			$this->session->set_flashdata('error','No user to insert.');
			redirect('admin/customers/uploadedlist/'.$upload_id);
		}
	}

	public function ignoreuploaded(){
		$user_id = $this->input->post('user');
		$ignore = $this->input->post('ignore');
		if($this->uploaded_user->updateUploadedUser($user_id, ['ignored' => $ignore]))
			$response['success'] = true;
		else
			$response['success'] = false;
		echo json_encode($response);
	}

	public function deviceHistory($user_id) {
		$data = array(
			'pageTitle' =>'User Badges History',
			'user_id' => $user_id
		);
		$this->baseloader->adminviews('devices_history',$data);
	}

	public function historylist_dt($user_id){
		$data = $row = array();
		$historyData = $this->history->getDeviceHistoryRows($_POST, $user_id, true);
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
            "recordsTotal" => $this->history->countAll($user_id),
            "recordsFiltered" => $this->history->countFiltered($_POST, $user_id, true),
            "data" => $data,
        );        
        echo json_encode($output);
	}

	function bulkDelete($type) {
		$last_selected = '';
		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			if($_POST['bulkDeleteBy'] == 'role'){
				$last_selected = $_POST['vRole'];
				$result = $this->customer->markAsDelete('role',$_POST['vRole']);
			}else if($_POST['bulkDeleteBy'] == 'unit') {
				$last_selected = $_POST['vUnit'];
				$result = $this->customer->markAsDelete('unit',$_POST['vUnit']);
			}else if($_POST['bulkDeleteBy'] == 'date_added') {
				$last_selected = ['start' =>  $_POST['vStart'] , 'end' =>  $_POST['vEnd']];
				$result = $this->customer->markAsDelete('date_added',$last_selected);
			}
			if($result){
				redirect('admin/customers/tobedelted/');
			}else{
				$this->session->set_flashdata('error','Something went wrong.');
			}
		}
		if($type == 'role') {
			$d = $this->customer->getAllRoles();
		}else if($type == 'unit'){
			$d = $this->customer->getAllUnits();
		}else {
			$d = [];
		}
		$data = array(
			'pageTitle'=>'Bulk Delete Users',
			'action'=>"admin/customers/bulkDelete/".$type,
			'type' => $type,
			'data' => $d,
			'lastselected' => $last_selected
		);
		$this->baseloader->adminviews('bulk_delete_users',$data);
	}

	function tobedelted(){
		$users = $this->customer->getToBeDeleted();
		$data = array(
			'pageTitle'=>'Bulk Delete Users',
			'action'=>"admin/customers/bulkDeleteConfirm/",
			'users' => $users,
		);
		$this->baseloader->adminviews('bulk_delete_users_list',$data);
	}

	public function ignoredeleteduser(){
		$user_id = $this->input->post('user');
		$ignore = $this->input->post('ignore');
		if($this->customer->updateDeletedUser($user_id, ['ignored' => $ignore]))
			$response['success'] = true;
		else
			$response['success'] = false;
		echo json_encode($response);
	}

	public function deleteusers() {
		if($this->customer->deleteSelectedUsers())
			$this->session->set_flashdata('success','Records deleted successfully');
		else
			$this->session->set_flashdata('error','Something went wrong.');

		redirect('admin/customers');
	}

	public function success() {
		$type = isset($_GET['type'])?$_GET['type']:'c';
		$data = [
			"pageTitle" => 'Success',
			'heading' => 'Success',
			'message' => $type== 'c' ? "User created successfully.": "User uploaded successfully.",
			"btn_url" => "admin/customers",
			"btn_text" => "GO TO CUSTOMERS"
		];
		$this->baseloader->adminviews('customer_success',$data);
	}

	public function download() {
		$customers = $this->customer->getAllCustomers();
		$filename = "customers.csv";
		$headers = ['first','last','role','unit','start','end'];
		$f = fopen('php://memory', 'w');
		fputcsv($f, $headers, ","); 
		foreach ($customers as $customer) {
			fputcsv($f, $customer, ",");
		}
		fseek($f, 0);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'.$filename.'";');
		fpassthru($f);
	}
}
