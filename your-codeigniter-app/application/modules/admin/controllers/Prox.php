<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class prox extends MY_Controller {
	
	public function __construct()
	{
		// call to models
		$this->load->model('events_model','events');
	}
	
	//function to show prox table view
	public function index()
	{
		$this->baseloader->adminviews('prox', ['pageTitle'=>'Prox']);
	}
}

?>