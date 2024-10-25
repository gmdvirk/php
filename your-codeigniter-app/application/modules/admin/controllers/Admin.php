<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

	public function __construct()
	{
		$this->load->model('admin_model','admin');
	}

	public function index()
	{
		redirect('admin/login');
	}

	public function login()
	{
        if(!$this->session->userdata('isAdmin')) {
            $this->load->view('login');
        } else {
          redirect('admin/dashboard');
        }
	}


	public function login_verify()
	{
		if($this->session->userdata('isAdmin'))
        {
            redirect('admin/dashboard');
        }
        $this->form_validation->set_rules('vEmail', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('vPassword', 'Password', 'required|trim');
       

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('login');
        }
        else
        {
         		$row=$this->admin->verify();
         		//print_r($row);die;
         		if(!empty($row))
         		{
         			$userData = array('vName' => $row->vFirstName,'fullName' => $row->vFirstName.' '.$row->vLastName,'vEmail'=>$row->vEmail,'iId'=>$row->iId,'Loggedin'=>TRUE,'isAdmin'=>TRUE, 'isManager' =>FALSE, 'isCustomer'=>FALSE  );

         			
         			$this->session->set_userdata($userData);
         			redirect('admin/customers');
         		}   
         		else
         		{
         			$this->session->set_flashdata('error','Invalid Credentials Please Try Again');
         			redirect('admin/login');
         		}
        }
	}


    function logout()
    {
        $this->session->sess_destroy();
        redirect('admin/login');
    }


}
