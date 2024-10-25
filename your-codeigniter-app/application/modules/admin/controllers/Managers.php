<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Managers extends MY_Controller {



	public function __construct()
	{
		$this->load->model('managers_model','manager');
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

	//~ public function changeStatus($id,$status)
	//~ {
		//~ $result=$this->customer->changeStatus($id,$status);
		//~ if($result)
		//~ {
			//~ redirect('admin/customers');
		//~ }

	//~ }

	function create()
	{
		$data=array('pageTitle'=>'Create Manager','action'=>base_url("admin/managers/createManager"));
		$this->baseloader->adminviews('editManager',$data);
	}

	function createManager()
	{
		$_POST['vPassword']=md5($_POST['vPassword']);
		$_POST['eStatus']='1';
		$_POST['vJsonString'] = trim($_POST['vJsonString']);
		if(!empty($_POST['vJsonString'])  && !is_json_valid($_POST['vJsonString'])){
			$this->session->set_flashdata('error','Invalid Json');
			redirect('admin/managers/create');
		}else{
			$_POST['vJsonString'] = minify_json($_POST['vJsonString']);
		}
		$data=$_POST;
		$create=$this->manager->createManager($data);
		if($create)
		{
			$this->session->set_flashdata('success','Manager created successfully');
			redirect('admin/invite');
		}

	}

	function editManager($id){
		$manager = $this->manager->getManagerById($id);
		$data = array(
			'manager'=>$manager,
			'pageTitle'=>'Edit Manager',
			'action'=>"admin/managers/updateManager/$id"
		);
		$this->baseloader->adminviews('editManager',$data);
	}

	function updateManager($id)
	{	
		if(!empty($_POST['vPassword']))
		{
			$_POST['vPassword']=md5($_POST['vPassword']);
		}
		else
		{
			unset($_POST['vPassword']);
		}
		unset($_POST['vJsonString']);
		// $_POST['vJsonString'] = trim($_POST['vJsonString']);
		// if(!empty($_POST['vJsonString']) && !is_json_valid($_POST['vJsonString'])){
		// 	$this->session->set_flashdata('error','Invalid Json');
		// 	redirect('admin/managers/editManager/'.$id);
		// }else{
		// 	$_POST['vJsonString'] = minify_json($_POST['vJsonString']);
		// }
		$data = $_POST;
		$result=$this->manager->updateManager($id,$data);
		if($result)
		{
			$this->session->set_flashdata('success','Record updated successfully');
			redirect('admin/invite');
		}
	}

	function deleteManager($id)
	{
		$delete=$this->manager->deleteManager($id);
		if($delete)
		{
			$this->session->set_flashdata('success','Record deleted successfully');
			redirect('admin/invite');
		}
	}
	
	public function restoreManager($id){		
		$data = array('eStatus'=> '1');
		$result = $this->manager->updateManager($id,$data);
		if($result){
			$this->session->set_flashdata('success','Manager restored successfully');
			redirect('admin/invite');
		}
	}

	function edit_json($id){
		$manager = $this->manager->getManagerById($id);
		$json_data = json_decode($manager->vJsonString, true);
		$data = array(
			'manager'=>$manager,
			'pageTitle'=>'Default Facility Badge Settings',
			'action'=>"admin/managers/updateJson/$id",
			'json' => $json_data,
			'cancelUrl' => 'admin/managers/editManager/'.$manager->vId
		);
		$this->baseloader->adminviews('edit_json',$data);
	}

	public function updateJson($id){
		$post = $this->input->post();
		// echo '<pre>';var_dump($post);exit;
		$postJson = json_encode($post);
		$result = $this->manager->updateManager($id,[ 'vJsonString' => $postJson]);
		if($result){
			$this->session->set_flashdata('success','Json Data updated successfully');
		}else{
			$this->session->set_flashdata('error','Something went wrong. Please try later.');
		}
		redirect('admin/managers/editManager/'.$id);
	}

	public function uploadFirmware() {
		if($this->input->method(TRUE) == 'POST') {
			# Form Submitted
			$facility = $this->input->post('facility');
			$firmware_details = $this->input->post('firmwareDetails');
			$config['upload_path']          = './uploads/facility_bin/';
			$config['allowed_types']        = 'bin';
			$config['max_size']             = 2048;
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			$pageData['selected_facility'] = $facility;
			$pageData['firmware_details'] = $firmware_details;
			if ( ! $this->upload->do_upload('firmwareFile')) {
				$this->session->set_flashdata('error',$this->upload->display_errors());
			}else{
				$file_data = $this->upload->data();
				$data['firmware_file'] =  $file_data['file_name'];
				if($firmware_details){
					$data['firmware_details'] = $firmware_details;
				}
				$data['firmware_date'] = date('Y-m-d H:i:s');
				$result = $this->manager->updateManager($facility, $data);
				if($result) {
					# Details update
					$this->session->set_flashdata('success','Firmware file uploaded successfully');
					redirect('admin/invite');
				}else{
					# Unable to update and delete file
					unlink(FCPATH.'uploads/facility_bin/'.$file_data['file_name']);
					$this->session->set_flashdata('error','Something went wrong. Please try later.');
				}
			}
		}
		$pageData['pageTitle'] = 'Firmware file upload';
		$pageData['managers'] = $this->manager->getManagers();
		$pageData['action'] = 'admin/managers/upload-firmware';
		$this->baseloader->adminviews('firmware_upload_form', $pageData);
	}
	
	public function reportwindow($id) {
		$manager = $this->manager->getManagerById($id);
		$page_data = array(
			'manager'=>$manager,
			'pageTitle'=>'Manager Report Windows',
			'action'=>"admin/managers/reportwindow/$id"
		);
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data = $_POST;
			$start1 = "2022-08-01 " . $data['w1Start'] . ":00";
			$end1 = "2022-08-01 " . $data['w1End'] . ":00";
			$date_a = new DateTime($start1);
			$date_b = new DateTime($end1);
			$diff1 = $date_b->getTimestamp() - $date_a->getTimestamp();
			$start2 = "2022-08-01 " . $data['w2Start'] . ":00";
			$end2 = "2022-08-01 " . $data['w2End'] . ":00";
			$date_c = new DateTime($start2);
			$date_d = new DateTime($end2);
			$diff2 = $date_d->getTimestamp() - $date_c->getTimestamp();
			if(($diff1 < 120 || $diff1 > 1800) || ($diff2 < 120 || $diff2 > 1800)) {
				// echo $diff1 . " ". $diff2; exit;
				// Window diff not between 2-30 minutes
				$this->session->set_flashdata('error','Time difference can\'t be less then 2 minutes and greater than 30 minutes.');
			}else {
				$result=$this->manager->updateManager($id,$data);
				if($result)
				{
					$this->session->set_flashdata('success','Report Window updated successfully');
					redirect('admin/invite');
				}
			}
			
		}
		
		$this->baseloader->adminviews('reportwindow',$page_data);
	}
}
