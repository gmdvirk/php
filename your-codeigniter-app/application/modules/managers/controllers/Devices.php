<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Devices extends MY_Controller {

	protected $isManager;
	public $iId;
	protected $vFacility;
	public function __construct()
	{
		$this->load->model('devices_model','devices');
		$this->load->model('customers_model','customer');
		$this->load->model('DevicesHistory_model','history');
		if(!$this->session->userdata('isManager'))
		{
			redirect('managers/login');
		}

		//print_r($this->session->userdata());die;
		$this->isManager=$this->session->userdata('isManager');
		$this->iId = $this->session->userdata('iId');
		$this->vFacility=$this->session->userdata('vFacility');
		if(!$this->isManager && !$this->iId)
		{
			redirect('managers/login');
		}
		
}
	public function index()
	{
		$data = array('pageTitle' =>'Badges','devices'=>$this->devices->getDevices($this->iId));
		$this->baseloader->managerviews('devices',$data);
	}

	function create()
	{
		$data = array(
			'pageTitle'=>'Add Badge',
			'action'=>base_url("managers/devices/createDevice"),
			'customers'=> $this->customer->getCustomerByFacility($this->iId)
		);
		$this->baseloader->managerviews('editDevice',$data);
	}

	function createDevice()
	{
		
		if($this->devices->getDeviceBySerial($_POST['vSerial'])>=1){
			$this->session->set_flashdata('error','This Device Already Exist');
			redirect('managers/devices/create');
		}
		$_POST['dUpdated_at'] = date("Y-m-d h:i:s");
		$_POST['vCreatedById']=$this->iId;
		$_POST['vAssigned_Facility_ID']=$this->iId;
		$assigned_user = $_POST['vAssigned_User_ID'];
		unset($_POST['vAssigned_User_ID']);
		$data=$_POST;
		$create=$this->devices->createDevice($data);
		if($create)
		{	
			if($assigned_user != ""){
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
				$this->customer->updateCustomer($assigned_user, ['vSerial' => $device->vSerial]);
				$this->history->createHisory([
					'user_id' => $assigned_user,
					'device_id' => $device->iId,
					'vSerial' => $device->vSerial,
					'assigned_from' => date('Y-m-d H:i:s'),
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
					]);
			}
			$this->session->set_flashdata('success','Device Added successfully');
			redirect('managers/devices');
		}

	}

	function editDevice($id)
	{
		$devices = $this->devices->getDeviceById($id);		
		$data = array(
			'devices'=>$devices,
			'pageTitle'=>'Edit Badge',
			'action'=>"managers/devices/updateDevice/$id",
			'customers'=> $this->customer->getCustomerByFacility($this->iId)
		);
		$this->baseloader->managerviews('editDevice',$data);

	}

	function updateDevice($id)
	{	
		if($this->devices->getUpdateSerial($_POST['vSerial'],$id)>=1){
			$this->session->set_flashdata('error','This Device Already Exist');
			redirect('managers/devices/editDevice/'.$id);
		}
		if($_POST['vAssigned_User_ID'] != ""){
			$device = $this->devices->getDeviceById($id);
			$existingCust = $this->customer->getCustomerBySerial($device->vSerial);
			$newCust = $this->customer->getCustomerById($_POST['vAssigned_User_ID']);
			if($existingCust){
				$this->customer->updateCustomer($existingCust->iId, ['vSerial' => '']);
				$history = $this->history->getDeviceActiveHistory($device->iId,$existingCust->iId);
				$this->history->updateHistory($history->id, [
					'assigned_to' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
					]);
			}
			if($newCust->vSerial != ''){
				// find the existing device, remove the existing device and create history 
				$existingDevice = $this->devices->getDeviceBySerial($newCust->vSerial);
				$history = $this->history->getDeviceActiveHistory($existingDevice->iId,$newCust->iId);
				$this->customer->updateCustomer($newCust->iId, ['vSerial' => '']);
				$this->history->updateHistory($history->id, [
					'assigned_to' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
					]);
			}
			// Assign VSerial to new user - Create new history
			$this->customer->updateCustomer($_POST['vAssigned_User_ID'], ['vSerial' => $device->vSerial]);
			$this->history->createHisory([
				'user_id' => $_POST['vAssigned_User_ID'],
				'device_id' => $device->iId,
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
			$this->session->set_flashdata('success','Device updated successfully');
			redirect('managers/devices');
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
		if($delete)
		{
			$this->session->set_flashdata('success','Device deleted successfully');
			redirect('managers/devices');
		}
	}



	
}
