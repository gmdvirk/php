<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	protected $isAdmin;
	
	public function __construct()
	{
		$this->load->model('admin_model','admin');
		if(!$this->session->userdata('isAdmin'))
		{
			redirect('admin/login');
		}
		$this->isAdmin=$this->session->userdata('isAdmin');
		if(!$this->isAdmin)
		{
			redirect('admin/login');
		}
	}

	public function index()
	{
		$data = array('pageTitle' =>'Dashboard');
		$this->baseloader->adminviews('dashboard',$data);
	}



	
}