<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends MY_Controller {

	public function __construct()
	{
		$this->load->model('customers_model','customer');
	}

	public function index()
	{
		redirect('customers/login');
	}

	public function login()
	{
		
        if(!$this->session->userdata('isCustomer'))
        {
            $this->load->view('login');
        }
        else
        {
          redirect('customers/dashboard');
        }
	}


	public function login_verify()
	{
			
        $this->form_validation->set_rules('vEmail', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('vPassword', 'Password', 'required|trim');

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('login');
        }
        else
        {
         		$row=$this->customer->verify();
               
         		if(!empty($row))
         		{
         			$userData = array('vName' => $row->vFirstName.' '.$row->vLastName,'vEmail'=>$row->vEmail,'iId'=>$row->iId,'Loggedin'=>TRUE,'isCustomer'=>TRUE,'isAdmin'=>FALSE, 'isManager'=>FALSE );

         			$this->session->set_userdata($userData);
         			redirect('customers/dashboard');
         		}   
         		else
         		{
         			$this->session->set_flashdata('error','Invalid Credentials Please Try Again');
         			redirect('customers/login');
         		}
        }
	}


    function logout()
    {
        $this->session->sess_destroy();
        redirect('customers/login');
    }

    function register()
    {
        $data = array('pageTitle' => 'Customer | Registration');
        $this->load->view('register',$data);
    }

    function registernew()
    {   
        $this->form_validation->set_rules('vFirstName', 'First Name', 'required|trim');
        $this->form_validation->set_rules('vLastName', 'Last Name', 'required|trim');
        $this->form_validation->set_rules('eGender', 'Gender', 'required');
        $this->form_validation->set_rules('vEmail', 'Gender', 'required|trim|valid_email|is_unique[customers.vEmail]');
        $this->form_validation->set_rules('vPassword', 'Password', 'required');
        $this->form_validation->set_rules('vConfirmPassword', 'Confirm Password', 'required|matches[vPassword]');
        
        if ($this->form_validation->run() == FALSE)
        {
            $data = array('pageTitle' => 'Customer | Registration');
            $this->load->view('register',$data);
        }
        else
        {
            $result=$this->customer->register();
            if($result)
            {
                $this->session->set_flashdata('success','You are registered successfully');
                redirect('customers/login');               
            }
        }

    }


    function update_profile()
    {

        $this->form_validation->set_rules('vFirstName', 'First Name', 'required|trim');
        $this->form_validation->set_rules('vLastName', 'Last Name', 'required|trim');
        $this->form_validation->set_rules('eGender', 'Gender', 'required');
        $this->form_validation->set_rules('vEmail', 'Gender', 'required|trim|valid_email|is_unique[customers.vEmail]');
        if($_POST['vPassword']!=''):
       
        $this->form_validation->set_rules('vConfirmPassword', 'Confirm Password', 'required|matches[vPassword]');
        endif;
        
        if ($this->form_validation->run() == FALSE)
        {
              $customer=$this->customer->getCustomerById($this->session->userdata('iId'));
              $data = array('pageTitle' =>'Settings','customer'=>$customer);
              $this->baseloader->userviews('cust_settings',$data);
        }
        else
        {

        }

    }


    


}
