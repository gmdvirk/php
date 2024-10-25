<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Passports extends MY_Controller {


	function __construct()
	{
		
		$this->load->model('passports_model','passport');
		if(!$this->session->userdata('isCustomer'))
		{
			redirect('customers/login');
		}
	}

	function index()
	{
		
		$data = array('pageTitle' =>'Manage Passports','passports'=>$this->passport->getAll());

		$this->baseloader->userviews('passports',$data);
	}
	
	function addnew()
	{
		$data=array('pageTitle'=>'Add New Passport','action'=>base_url('customers/passports/create'));

		$this->baseloader->userviews('manage_passports',$data);

	}

	function create()
	{

            $this->form_validation->set_rules('vLastName', 'Last Name', 'required');
            $this->form_validation->set_rules('vFirstName', 'Last Name', 'required');
            $this->form_validation->set_rules('iPacts', 'Pacts', 'required');
            $this->form_validation->set_rules('vPassportType', 'Passport Type', 'required');
            $this->form_validation->set_rules('vOtherPassport', 'Last Name', 'required');
            $this->form_validation->set_rules('vPso', 'Last Name', 'required');
            $this->form_validation->set_rules('dDateReceived', 'Date Recieved', 'required');
            $this->form_validation->set_rules('dDateReturned', 'Date Returned', 'required');

             if ($this->form_validation->run() == FALSE)
                {
                    $this->session->set_flashdata('errors',validation_errors());
                    $data=array('pageTitle'=>'Add New Passport');
					$this->baseloader->userviews('manage_passports',$data);
                }
                else
                {
                       $insert=$this->passport->saveNew();

                       $customer_id=$this->session->userdata('iId');
                       $extra=json_encode(array('passport_id'=>$insert));
                       $this->passport->createActionLog('create new passport',$customer_id,$extra);

                       if($insert)
                       {
                       	  $this->session->set_flashdata('success','New passport record created successfully');
                       	  redirect('customers/passports');
                       }

                }
	}

	function editPassport($id)
	{
		$passport=$this->passport->getPassportById($id);
		$data=array(
			'pageTitle'=>'Edit Passport',
			'passport'=>$passport,
			'action'=>base_url('customers/passports/update')

		);
		$this->baseloader->userviews('manage_passports',$data);
	}

	function update()
	{
			$id=$this->input->post('id');
			$this->form_validation->set_rules('vLastName', 'Last Name', 'required');
            $this->form_validation->set_rules('vFirstName', 'Last Name', 'required');
            $this->form_validation->set_rules('iPacts', 'Pacts', 'required');
            $this->form_validation->set_rules('vPassportType', 'Passport Type', 'required');
            $this->form_validation->set_rules('vOtherPassport', 'Last Name', 'required');
            $this->form_validation->set_rules('vPso', 'Last Name', 'required');
            $this->form_validation->set_rules('dDateReceived', 'Date Recieved', 'required');
            $this->form_validation->set_rules('dDateReturned', 'Date Returned', 'required');

            if ($this->form_validation->run() == FALSE)
                {
                    $this->session->set_flashdata('errors',validation_errors());
                   $passport=$this->passport->getPassportById($id);
					$data=array(
						'pageTitle'=>'Edit Passport',
						'passport'=>$passport,
						'action'=>base_url('customers/passports/update')

					);
					$this->baseloader->userviews('manage_passports',$data);
                }
                else
                {
                       $update=$this->passport->updateNow($id);
                       $customer_id=$this->session->userdata('iId');
                       $extra=json_encode(array('passport_id'=>$id));
                       $this->passport->createActionLog('updated existing passport',$customer_id,$extra);
                       if($update)
                       {
                       	  $this->session->set_flashdata('success','Passport record updated successfully');
                       	  redirect('customers/passports');
                       }

                }

		
	}

	function deletePassport($id)
	{
			$delete=$this->passport->deleteRecordById($id);
			$customer_id=$this->session->userdata('iId');
            $extra=json_encode(array('passport_id'=>$id));
            $this->passport->createActionLog('deleted passport record #'.$id,$customer_id,$extra);
			if($delete)
			{
    		  $this->session->set_flashdata('success','Passport record deleted successfully');
           	  redirect('customers/passports');
			}

	}




	


}
