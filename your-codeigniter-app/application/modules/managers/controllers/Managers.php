<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Managers extends MY_Controller {

	public function __construct()
	{
		$this->load->model('managers_model','manager');
	}

	public function index()
	{
		redirect('managers/login');
	}

	public function login()
	{
		
        if(!$this->session->userdata('isManager'))
        {
            $this->load->view('login');
        }
        else
        {
          redirect('managers/customers');
        }
	}
	
	public function login_verify()
	{
			if($this->session->userdata('isManager'))
        {
            redirect('managers/customers');
        }
        $this->form_validation->set_rules('vEmail', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('vPassword', 'Password', 'required|trim');

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('login');
        }
        else
        {
         		$row=$this->manager->verify();
               
         		if(!empty($row))
         		{
					$userData = array('vName' => $row->vFirstName.' '.$row->vLastName,'vEmail'=>$row->vEmail,'vFacility'=>$row->vFacility,'iId'=>$row->vId,'Loggedin'=>TRUE,'isManager'=>TRUE, 'isAdmin'=>FALSE, 'isCustomer'=>FALSE );
         			$this->session->set_userdata($userData);
         			redirect('managers/customers');
         		}   
         		else
         		{
         			$this->session->set_flashdata('error','Invalid Credentials Please Try Again');
         			redirect('managers/login');
         		}
        }
	}
	public function logout()
		{
			$this->session->sess_destroy();
			redirect('managers/login');
		}
		
	public function register($email=NULL, $facility=NULL){
		        if(!$this->session->userdata('isManager'))
        {
			if(!empty($email) && !empty($facility)){
			$session=array('email'=>$email, 'facility'=>urldecode($facility));
		    $this->session->set_userdata($session);
			}else{
				redirect('error_404.php');
			}

          $this->load->view('register');
        }
        else
        {
          redirect('managers/customers');
        }
		
		}
		
	public function create_manager(){
		$this->form_validation->set_rules('vFirst', 'First Name', 'required|trim');
        $this->form_validation->set_rules('vLast', 'Last Name', 'required|trim');
		$this->form_validation->set_rules('vEmail', 'Email', 'required|trim|valid_email|is_unique[managers.vEmail]');
		$this->form_validation->set_rules('vPassword', 'Password', 'required|trim');
        $this->form_validation->set_rules('vPassword2', 'Confirm Password', 'required|trim|matches[vPassword]');
         if ($this->form_validation->run() == FALSE)
        {
             $this->load->view('register');
        }else{
			$register=$this->manager->register();
			if($register){
			$row=$this->manager->register_verify();
				if(!empty($row)){
				$userData = array('vName' => $row->vFirstName.' '.$row->vLastName,'vEmail'=>$row->vEmail, 'vFacility'=>$row->vFacility,'iId'=>$row->vId,'Loggedin'=>TRUE,'isManager'=>TRUE, 'isAdmin'=>FALSE, 'isCustomer'=>FALSE );
						$this->session->set_userdata($userData);
						$this->manager->delete_invite($row->vEmail);
						if($this->session->userdata('iId')){
						redirect('managers/customers');
						}
				}
			}
		}
		}
		
	public function forgot_password(){
		
		$this->load->view('forgotpassword');
		}
	
	public function reset_password(){
		$this->form_validation->set_rules('vEmail', 'Email', 'required|trim|valid_email');
		
		  if ($this->form_validation->run() == FALSE)
        {
             $this->load->view('forgotpassword');
        }elseif(empty($this->manager->get_manager()))
        {
			$this->session->set_flashdata('error','Manager not Exist');
			redirect('managers/forgot_password');
		}else{
			
			$config=array(
			'protocol' =>'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => '465',
			'smtp_user' => 'testfordev2018@gmail.com',
			'smtp_pass' => 'testfordev@321#',
			'mailtype' => 'html',
			'charset' => 'iso-8859-1',
			'wordwrap'=> TRUE);
		 $email=$this->input->post('vEmail');
		 $admin_mail="hassan789@gmail.com";
         $message = "Reset Password <br><a href='http://52.15.144.171/managers/reset/".$email."'>click here</a> for the reset $email Badge Manager acount Password.";
       
          $this->load->library('email', $config);
		  $this->email->set_newline("\r\n");
		  $this->email->from('testfordev2018@gmail.com', 'Badge'); // change it to yours
		  $this->email->to($admin_mail);// change it to yours
		  $this->email->subject("Reset .$email. Password");
		  $this->email->message($message);
			if($this->email->send())
			{
				$this->session->set_flashdata('success','Reset Password Link send to the Email');
				redirect('managers/');
			}else{
				$this->session->set_flashdata('error','Unable to reset password.');
				redirect('managers/');
				}
			}
	}
		
		
	public function reset($email=NULL){
	if($email==NULL){
			redirect('managers/');
			}
		$reset_email=array('reset_email'=>$email);
		    $this->session->set_userdata($reset_email);
		$this->load->view('reset');
	}
	
	public function reset_verify(){
		if(!$this->session->userdata('reset_email')){
			redirect('managers/');
			}
	    $this->form_validation->set_rules('vPassword1', 'Password', 'required|trim');
        $this->form_validation->set_rules('vPassword2', 'Re-Enter Password', 'required|trim|matches[vPassword1]');

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('reset');
        }
        else{
			$email= $this->session->userdata('reset_email');
			$data['vPassword'] = md5($this->input->post('vPassword1'));
			
			if(!empty($this->manager->updateManager($email, $data))){
			$this->session->set_flashdata('success','Password Reset Please Login');
				  $this->load->view('login');
			 }
			 else
			 {
				 $this->session->set_flashdata('Error','Password not reset');
				  $this->load->view('login');
			 }
		}
        
	}
}
?>
