<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	public $isManager;
	
	public function __construct()
	{
		if(!$this->session->userdata('isManager'))
		{
			redirect('managers/login');
		}
		
		$this->isManager=$this->session->userdata('isManager');
		if(!$this->isManager)
		{
			redirect('managers/login');
		}
		
		$this->load->library('form_validation');
		
	}
	public function index(){
		
				$data = array('pageTitle' =>'Dashboard');
		$this->baseloader->managerviews('dashboard',$data);
		
		}
	
}
?>
