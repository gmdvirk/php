<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller {

	protected $isAdmin;
	
	public function __construct()
	{
		$this->load->model('admin_model','admin');
		$this->isAdmin=$this->session->userdata('isAdmin');
		if(!$this->isAdmin)
		{
			redirect('admin/login');
		}
	}


	public function index()
	{
		$data = array('pageTitle' => 'Settings', 'account'=>$this->admin->getAccountSettings());
		$this->baseloader->adminviews('settings',$data);
	}


	function save_config()
	{
        $this->form_validation->set_rules('vFirstName', 'First Name', 'required|trim');
        $this->form_validation->set_rules('vLastName', 'Last Name', 'required|trim');
        $this->form_validation->set_rules('vEmail', 'Email', 'required|trim|valid_email');
		
		if($_POST['vPassword']!='')
		{	
			$this->form_validation->set_rules('vPassword','Password','required|trim');
			$this->form_validation->set_rules('vConfirmPassword', 'Confirm Password', 'required|trim|matches[vPassword]');
		}
		
		if ($this->form_validation->run() == FALSE)
		{
			
			$data = array('pageTitle' => 'Settings', 'account'=>$this->admin->getAccountSettings());
			$this->baseloader->adminviews('settings',$data);
			
		}
        else
        {
        	$row=$this->admin->save_settings();
        	if($row)
        	{
				$userData = array('vName' => $row->vFirstName.' '.$row->vLastName,'vEmail'=>$row->vEmail,'iId'=>$row->iId,'Loggedin'=>TRUE,'isAdmin'=>TRUE );
         		$this->session->set_userdata($userData);				
        		$this->session->set_flashdata('success','Settings updated successfully');
        		redirect('admin/settings');
        	}
        }
	}



	
}
