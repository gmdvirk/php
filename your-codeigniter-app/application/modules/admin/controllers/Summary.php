<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Summary extends MY_Controller {
	
	public function __construct()
	{
		if(!$this->session->userdata('isAdmin'))
		{
			redirect('admin/login');
		}
		
		$this->isAdmin=$this->session->userdata('isAdmin');
		if(!$this->isAdmin)
		{
			redirect('admin/login');
		}
		$this->load->model('customers_model','customer');
		$this->load->model('managers_model','manager');
		$this->load->model('devices_model','device');
		$this->load->model('readers_model','reader');
		$this->load->model('events_model','event');
		$this->load->library('form_validation');
	}
	
	public function index(){
		$data = array(
			'pageTitle' =>'Summary',
			'managers'=>$this->manager->getManagers(),
			'summary'=>$this->event->getSummary()
		);
		$this->baseloader->adminviews('summary',$data);	
	}
}
?>
