<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends MY_Controller {

	protected $isManager;
	protected $isId;
	protected $vFacility;
	public function __construct()
	{
		$this->load->model('customers_model','customer');
		$this->load->model('devices_model','devices');
		$this->load->model('DevicesHistory_model','history');
		if(!$this->session->userdata('isManager') && !$this->session->userdata('vId'))
		{
			redirect('managers/login');
		}

		//print_r($this->session->userdata());die;
		$this->isId=$this->session->userdata('iId');
		$this->isManager=$this->session->userdata('isManager');
		$this->vFacility=$this->session->userdata('vFacility');
		if(!$this->isManager && !$this->isId)
		{
			redirect('managers/login');
		}
	}

	public function index()
	{
		$data = array('pageTitle' =>'Users','customers'=>$this->customer->getCustomers($this->isId));
		$this->baseloader->managerviews('customers',$data);
	}


	public function changeStatus($id,$status)
	{
		$result=$this->customer->changeStatus($id,$status);
		if($result)
		{
			redirect('managers/customers');
		}

	}

	function create()
	{
		$devices = $this->devices->getDevices($this->isId);
		$data=array('pageTitle'=>'Create User','action'=>base_url("managers/customers/createCustomer"),'devices' => $devices);
		$this->baseloader->managerviews('editCustomer',$data);
	}

	function createCustomer()
	{
		$this->session->editedCustDetails = $_POST;
		if($this->customer->getCustomerBySerial($_POST['vSerial'])>=1){
			$this->session->set_flashdata('error','This Device Not Available');
			redirect('managers/customers/create');
		} 
		//echo "test";die;
		$_POST['vCreatedById']=$this->isId;
		$_POST['vAssigned_Facility_ID']=$this->isId;
		//~ $_POST['vPassword']=md5($_POST['vPassword']);
		$_POST['eStatus']='1';
		$_POST['dUpdated_at'] = date("Y-m-d h:i:s");
		$data=$_POST;
		$customer_id = $this->customer->createCustomer($data);
		if($customer_id) {
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
			$this->session->set_flashdata('success','Customer created successfully');
			redirect('managers/customers');
		}

	}

	function editCustomer($id)
	{
		$customer=$this->customer->getCustomerById($id);
		$devices = $this->devices->getDevices($this->isId);
		$data=array('customer'=>$customer,'pageTitle'=>'Edit User','action'=>"managers/customers/updateCustomer/$id",'devices'=>$devices);
		$this->baseloader->managerviews('editCustomer',$data);

	}

	function updateCustomer($id)
	{	
		$this->session->editedCustDetails = $_POST;
		$customer = $this->customer->getCustomerById($id);
		if($this->customer->getUpdateSerial($_POST['vSerial'],$id)>=1 && $_POST['vSerial'] != 'unassign'){
			$this->session->set_flashdata('error','This Device Not Available');
			redirect('managers/customers/editCustomer/'.$id);
		} 
		if(!empty($_POST['vPassword']))
		{
			$_POST['vPassword']=md5($_POST['vPassword']);
		}
		else
		{
			unset($_POST['vPassword']);
		}
		if($customer->vSerial != $_POST['vSerial']){
			if($customer->vSerial != ''){
				$oldDevice = $this->devices->getDeviceBySerial($customer->vSerial);
				$history = $this->history->getDeviceActiveHistory($oldDevice->vSerial,$id);
				$this->history->updateHistory($history->id, [
						'assigned_to' => date('Y-m-d H:i:s'),
						'updated_at' => date('Y-m-d H:i:s')
					]);
			}
			$_POST['vSerial'] = $_POST['vSerial'] == 'unassign' ? '' : $_POST['vSerial'];
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
		
		$data=$_POST;
		$result=$this->customer->updateCustomer($id,$data);
		if($result)
		{
			$this->session->unset_userdata('editedCustDetails');
			$this->session->set_flashdata('success','Record updated successfully');
			redirect('managers/customers');
		}
	}

	function deleteCustomer($id)
	{
		$delete=$this->customer->deleteCustomer($id);
		if($delete)
		{
			$this->session->set_flashdata('success','Record deleted successfully');
			redirect('managers/customers');
		}
	}



	
}
