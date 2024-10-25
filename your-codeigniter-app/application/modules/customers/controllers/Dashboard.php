<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {


	function __construct()
	{
		
		$this->load->model('customers_model','customer');
		if(!$this->session->userdata('isCustomer'))
		{
			redirect('customers/login');
		}
	}

	function index()
	{
		
		$data = array('pageTitle' =>'Dashboard');

		$this->baseloader->userviews('dashboard',$data);
	}
	

	function settings()
	{
		$customer=$this->customer->getCustomerById($this->session->userdata('iId'));
		$data = array('pageTitle' =>'Settings','customer'=>$customer);
		$this->baseloader->userviews('cust_settings',$data);
	}




	


}
