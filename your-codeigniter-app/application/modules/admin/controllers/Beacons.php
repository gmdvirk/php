<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Readers are called Beacon
class Beacons extends MY_Controller {

	public function __construct()
	{
		$this->load->model('UserFileUploads_model','fileupload');
		$this->load->model('Unit_model','units');
		if(!$this->session->userdata('isAdmin'))
		{
			redirect('admin/login');
		}
		
		$this->isAdmin=$this->session->userdata('isAdmin');
		if(!$this->isAdmin)
		{
			redirect('admin/login');
		}
		
		$this->load->model('managers_model','manager');
		$this->load->model('readers_model','reader');
		$this->load->model('UploadedReaders_model', 'uploaded_reader');
	}
	
	public function index(){
		$data = array(
			'pageTitle' =>'Beacons',
			'readers'=>$this->reader->getReaders(),
			'managers' => $this->manager->getManagers()
		);
		$this->baseloader->adminviews('readers',$data);
	}
	
	public function create(){
		$this->load->model('invite_model','invite');
		$data = array(
			'pageTitle' => 'Create Beacon',
			'action' => base_url('admin/beacons/create'),
			'managers'=>$this->manager->getManagers(),
			'units' => $this->units->getUnits(),
			'domain' => $this->invite->getDomain()
		);

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$_POST['vDescription'] = $_POST['vUnit']. ' '. $_POST['roomNumber'];
			if(isset($_POST['v2']) && $_POST['v2']== 1){
				$response = $this->insertIntoChirpStack($_POST);
				if($response["status"] == 200){
					$this->insertReader($_POST);
				}else{
					$err = json_decode($response["response"], true);
					$errorMsg = $err["message"];
					if(strtolower($errorMsg) == 'object already exists') {
						$serial = $this->reader->getReaderByOnlySerial($_POST['vReaderSerial']);
						if($serial->vFacility){
							$errorMsg = "Door serial has already been added to ".$serial->vFacility;
						}else {
							$errorMsg = "Door serial has already been added.";
						}
					}elseif(strtolower($errorMsg) == 'lorawan: exactly 8 bytes are expected') {
						$errorMsg = "Invalid serial number: 8 digits required";
					}
					$this->session->set_flashdata('error',$errorMsg);
					$this->baseloader->adminviews('editReader',$data);
				}
			}else{
				$this->insertReader($_POST);
			}
		}
		
		$this->baseloader->adminviews('editReader',$data);
	}
	
	public function createReader(){
		if(isset($data['v2']) && $data['v2']== 1){
			$response = $this->insertIntoChirpStack($_POST);
			if($response["status"] == 200){
				$this->insertReader($_POST);
			}else{
				$err = json_decode($response["response"], true);
				$this->session->set_flashdata('error',$err["message"]);
				redirect('admin/beacons/create');
			}

		}else{
			$this->insertReader($_POST);
		}
	
	}

	public function insertReader($data){
		unset($data['applicationName']);
		$oldSerial = $this->reader->getReaderBySerial($data['vReaderSerial'], $data['vFacility']);
		if($oldSerial){
			$this->session->set_flashdata('error','This reader already exists in this facility.');
			redirect('admin/beacons/create');
		}
		

		$reader_insert = $this->reader->createReader($data);
		if($reader_insert){
			$this->session->set_flashdata('success','Reader Create successfully');
			redirect('admin/beacons');
		}
	}
	
	public function editReader($iId){
		$this->load->model('invite_model','invite');
		$data = array(
			'pageTitle'=> 'Edit Beacon', 
			'action' => base_url("admin/beacons/editReader/".$iId), 
			'managers'=>$this->manager->getManagers(), 
			'readers'=>$this->reader->getReadersById($iId),
			'units' => $this->units->getUnits(),
			'domain' => $this->invite->getDomain()
		);

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$_POST['vDescription'] = $_POST['vUnit']. ' '. $_POST['roomNumber'];
			$readerToUpdate = $this->reader->getReadersById($iId);
			if($readerToUpdate){
				if($readerToUpdate->v2 == 1)
					$dResponse = $this->deleteFromChirpStack($readerToUpdate->vReaderSerial);
				else
					$dResponse["status"] = 200;
				
				if($dResponse["status"] == 200){
					// $delete_reader = $this->reader->deleteReader($iId);
					// if($delete_reader){
					if($_POST['v2']) 
						$response = $this->insertIntoChirpStack($_POST);
					else 
						$response["status"] = 200;
					
					if($response["status"] == 200){
						$d = $_POST;
						unset($d['applicationName']);
						$reader_update = $this->reader->updateReader($iId, $d);
						if($reader_update){
							$this->session->set_flashdata('success','Reader Updated successfully');
							redirect('admin/beacons');
						}else{
							// Delete from chirpStack
							if($_POST['v2']) 
								$this->deleteFromChirpStack($_POST['vReaderSerial']);
							$this->session->set_flashdata('error','Server error.');
							$this->baseloader->adminviews('editReader',$data);
						}
					}else{
						$err = json_decode($response["response"], true);
						$this->session->set_flashdata('error',$err["message"]);
						$this->baseloader->adminviews('editReader',$data);
					}
					// }else{
					// 	$this->session->set_flashdata('error','Server error.');
					// 	$this->baseloader->adminviews('editReader',$data);
					// }
				}else{
					$err = json_decode($dResponse["response"], true);
					$this->session->set_flashdata('error',$err["message"]);
					$this->baseloader->adminviews('editReader',$data);
				}
			}else{
				$this->session->set_flashdata('error','Reader not available.');
				$this->baseloader->adminviews('editReader',$data);
			}
		}
		
		$this->baseloader->adminviews('editReader',$data);
	}
	
	public function updateReader($iId){
		$data = $_POST;
		$update_reader = $this->reader->updateReader($iId, $data);
		if($update_reader){
			$this->session->set_flashdata('success','Reader Updated successfully');
			redirect('admin/beacons');
		}
	}
	
	public function deleteReader($iId){
		$readerToDelete = $this->reader->getReadersById($iId);
		if($readerToDelete->v2 == 1)
			$response = $this->deleteFromChirpStack($readerToDelete->vReaderSerial);
		else
			$response["status"] = 200;
		
		if($response["status"] == 200){
			$delete_reader = $this->reader->deleteReader($iId);
			if($delete_reader){
				$this->session->set_flashdata('success','Reader Deleted successfully');
				redirect('admin/beacons');
			}
		}else{
			$err = json_decode($response["response"], true);
			$this->session->set_flashdata('error',$err["message"]);
			redirect('admin/beacons');
		}
	}

	public function readerslist_dt($facility_id = 'ALL'){
		$this->session->readerFacility = $facility_id;
		$data = $row = array();
		$readerData = $this->reader->getReaderRows($_POST, $facility_id);
        $i = $_POST['start'];
        foreach($readerData as $reader){
			$i++;			
			$data[] = array(
				$i,
				$reader->vReaderSerial,
				$reader->roomNumber,
				$reader->vUnit,
				$reader->vDescription,
				$reader->vFacility,
				$reader->v2 == 1 ? 'Yes' : 'No',
				'
				<a href="'.base_url('admin/beacons/editReader/'.$reader->iId).'" class="btn btn-flat btn-sm btn-info"><i class="fa fa-edit"></i></a>
				<a onclick="return confirm(\'Are you sure you want to delete this beacon?\');" href="'.base_url('admin/beacons/deleteReader/'.$reader->iId).'" class="btn btn-flat btn-sm btn-danger"><i class="fa fa-trash"></i></a>
				'
			);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->reader->countAll(),
            "recordsFiltered" => $this->reader->countFiltered($_POST, $facility_id),
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
			if ( ! $this->upload->do_upload('csv_file')){
				$fileErr = $this->upload->display_errors();
				if(strpos($fileErr, "filetype you are attempting to upload is not allowed")){
					$fileErr = 'The filetype is not allowed” -> “CSV required, file is not CSV';
				}
				$this->session->set_flashdata('error',$fileErr);
				redirect('admin/beacons/upload');
			}else {
				$file_data = $this->upload->data();
				$file_name = $file_data['file_name'];
				$file_path = $file_data['full_path'];
				if (!mb_check_encoding(file_get_contents($file_path), 'UTF-8')) {
					$this->session->set_flashdata('error','Detect unicode error, ex UTF-16 -> “CSV type must be UTF-8');
					redirect('admin/beacons/upload');
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
						$valid_header = ['serial','room', 'unit'];
						foreach($csvData as $row){
							$orgKeys = array_keys($row);
							$csv_header = array_map('trim',array_map('strtolower', $orgKeys));
							if($valid_header !== $csv_header){
								$this->session->set_flashdata('error','Bad columns. Format must be '.implode(', ', $valid_header));
								redirect('admin/beacons/upload');
							}
							$reader = [
								'upload_id' => $upload_id,
								'facility_id' => $facility,
								'vReaderSerial' => $row[$orgKeys[0]],
								'roomNumber' => $row[$orgKeys[1]],
								'vUnit' => $row[$orgKeys[2]],
								'vDescription' => $row[$orgKeys[2]]. ' '. $row[$orgKeys[1]],
							];
							$reader_array[] = $reader;
						}

						$readerUploaded = $this->uploaded_reader->createBulkUploadedReaders($reader_array);
						if($readerUploaded){
							$this->session->set_flashdata('success','File uploaded successfully.');
							redirect('admin/beacons/uploadedlist/'.$upload_id);
						}
					}else{
						$this->session->set_flashdata('error','Something we wrong, please try later.');
						redirect('admin/beacons/upload');
					}
				}else{
					$this->session->set_flashdata('error','No data in csv file.');
					redirect('admin/beacons/upload');
				}
			}			
		}
		$managers = $this->manager->getManagers();
		$data = array(
			'managers'=>$managers,
			'pageTitle'=>'Upload Beacons',
			'action'=>"admin/beacons/upload"
		);
		$this->baseloader->adminviews('upload_readers',$data);
	}

	public function uploadedlist($upload_id){
		$this->load->model('UserFileUploads_model','fileupload');
		// $this->load->model('UploadedUsers_model', 'uploaded_user');
		$upload = $this->fileupload->getFileUploadById($upload_id);
		if(!$upload){
			$upload['id'] = '0';
		}
		
		$uploaded_readers = $this->uploaded_reader->getUploadedReadersByUploadId($upload_id);
		$data = array(
			'upload'=>$upload,
			'uploaded_users' => $uploaded_readers,
			'pageTitle'=>'Uploaded Beacons'
		);
		$this->baseloader->adminviews('uploaded_readers',$data);

	}

	public function uploadedlist_dt($upload_id){
		$data = $row = array();
		$upload = $this->fileupload->getFileUploadById($upload_id);
        $readerData = $this->uploaded_reader->getRows($_POST, $upload_id);
        $i = $_POST['start'];
		$disabled = ($upload['is_processed'] == '1') ? 'disabled' : '';
        foreach($readerData as $reader){
			$i++;
			$checked = $reader->ignored > 0 ? 'checked' : '';
			$data[] = array(
				$i, 
				$reader->vReaderSerial,
				$reader->roomNumber, 
				$reader->vUnit, 
				$reader->vDescription,
				'<input type="checkbox" class="form-check-input" '. $disabled .' onclick="ingnorereader(this)" id="'.$reader->id.'" '.$checked.'>'
			);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->uploaded_reader->countAll(),
            "recordsFiltered" => $this->uploaded_reader->countFiltered($_POST, $upload_id),
            "data" => $data,
        );
        
        echo json_encode($output);
	}

	public function ignoreuploaded(){
		$reader_id = $this->input->post('reader');
		$ignore = $this->input->post('ignore');
		if($this->uploaded_reader->updateUploadedReader($reader_id, ['ignored' => $ignore]))
			$response['success'] = true;
		else
			$response['success'] = false;
		echo json_encode($response);
	}

	public function registerreaders($upload_id) {
		$this->load->model('UserFileUploads_model','fileupload');
		$upload = $this->fileupload->getFileUploadById($upload_id);
		if($upload['is_processed'] > 0){
			$this->session->set_flashdata('error','Records already processed.');
			redirect('admin/beacons/uploadedlist/'.$upload_id);
		}
		$select = $this->uploaded_reader->getReadersToRegister($upload_id);
		// var_dump($select->result_array());exit;
		if($select->num_rows()){
			$inserted = $this->reader->registerUploadedReaders($select->result_array());
			if($inserted){
				$this->fileupload->updateFileUpload($upload_id, ['is_processed' => '1']);
				$this->session->set_flashdata('success','Beacons inserted successfully.');
				redirect('admin/beacons/uploadedlist/'.$upload_id);
			}else{
				$this->session->set_flashdata('error','Something went wrong, please try later.');
				redirect('admin/beacons/uploadedlist/'.$upload_id);
			}
		}else{
			$this->session->set_flashdata('error','No beacons to insert.');
			redirect('admin/beacons/uploadedlist/'.$upload_id);
		}
	}


	public function deleteFromChirpStack($chirpStackId){
		$curlData = [
			"devAddr" => $chirpStackId,
		];
		$url = 'http://loraserver.intellio-dev.com:1880/api/devices';
		$d = json_encode($curlData);
		return new_curl_request('DELETE', $url, $d);
	}
	
	public function insertIntoChirpStack($postData){
		$curlData = [
			"applicationName" => $postData["applicationName"],
			"devAddr" => $postData["vReaderSerial"],
			"name" => $postData["vReaderSerial"],
			"description" => $postData["vDescription"],
		];
		$url = 'http://loraserver.intellio-dev.com:1880/api/devices';
		$d = json_encode($curlData);
		return new_curl_request('POST', $url, $d);
	}

	function editsettings($id){
		$reader = $this->reader->getReadersById($id);
		if(!$reader || $reader->v2 != 1) return redirect('admin/beacons'); 
		$manager = $this->manager->getManagerById($reader->	vFacility);
		$manager_json_data = json_decode($manager->vJsonString, true);
		if(isset($_GET['copy'])){
			$json_data = $manager_json_data;
		}else{
			$json_data = json_decode($reader->vJsonString, true);
		}

		$json_accepted_data = json_decode($reader->jsonAccepted, true);
		
		$data = array(
			'reader'=>$reader,
			'pageTitle'=>'Reader Badge Settings override',
			'action'=>"admin/beacons/updateSettings/$id",
			'json' => $json_data,
			'json_accepted' => $json_accepted_data,
			'manager_json' => $manager_json_data,
			'cancelUrl' => 'admin/beacons/editReader/'.$reader->iId,
			'copyUrl' =>  'admin/beacons/editsettings/'.$reader->iId.'?copy=1',
		);
		$this->baseloader->adminviews('edit_json',$data);
	}

	public function updateSettings($id){
		$post = $this->input->post();
		// if(isset($post['empty_json'])){
		// 	$postJson = NULL;
		// } else {
		$postJson = json_encode($post);
		// }

		$result = $this->reader->updateReader($id,[ 'vJsonString' => $postJson]);
		if($result){
			$this->session->set_flashdata('success','Json Settings updated successfully');
		}else{
			$this->session->set_flashdata('error','Something went wrong. Please try later.');
		}
		redirect('admin/beacons/editReader/'.$id);
	}
}
?>
