<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invite extends MY_Controller {
	
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
		$this->load->model('invite_model','invite');
		$this->load->model('managers_model','manager');
		$this->load->library('form_validation');
		$this->load->model('HourlyEvents_model','hourlyevents');
	}
	
	public function index(){
		$data = array(
			'pageTitle' =>'Invite', 
			'managers'=>$this->manager->getManagers(), 
			'invited'=>$this->invite->getInvited(),
			'domain' => $this->invite->getDomain()
		);
		// var_dump($data);
		$this->baseloader->adminviews('invite',$data);
	}
	
	public function invitation(){
		$this->form_validation->set_rules('email', 'Email', 'is_unique[managers.vEmail]');
			  if ($this->form_validation->run() == FALSE)
			{  
					$data = array('pageTitle' =>'Invite', 'managers'=>$this->manager->getManagers(), 'invited'=>$this->invite->getInvited());
					$this->baseloader->adminviews('invite',$data);
			}
			else
			{
				
			//~ $msg = "I am inviting you as the manager on my application. Please <a href='http://intellio-dev.com/managers/register/".$email."/".$facility."'>click here</a>  to register yourself as manager.";   
            //~ $to_email = 'testfordev2018@gmail.com';
			//~ $subject = 'Invitation Card';
			//~ $headers = "From: noreply\r\n";
			//~ $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
			//~ $headers .= "MIME-Version: 1.0\r\n";

			//~ $mail = mail($to_email,$subject,$msg,$headers);
				
				
				
		$config=array(
			'protocol' =>'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => '465',
			'smtp_user' => 'testfordev2018@gmail.com',
			'smtp_pass' => 'testfordev@321#',
			'mailtype' => 'html',
			'charset' => 'iso-8859-1',
			'wordwrap'=> TRUE);
		 $email=$this->input->post('email');
		 $facility=$this->input->post('facility');
         $message = "I am inviting you as the manager on my application. Please <a href='http://intellio-dev.com/managers/register/".$email."/".$facility."'>click here</a>  to register yourself as manager.";
		 
          $this->load->library('email', $config);
          $this->email->initialize($config);
		  $this->email->set_newline("\r\n");
		  $this->email->from('testfordev2018@gmail.com', 'Badge'); // change it to yours
		  $this->email->to($email);// change it to yours
		  $this->email->subject('Manager Registration');
		  $this->email->message($message);
		
			if($this->email->send())
			//~ if($mail)
			{
				$this->invite->invited($email,$facility);
				$this->session->set_flashdata('success','Invitation send successfully');
				redirect('admin/invite');
			}else{
			
				$this->session->set_flashdata('error','Invitation not send');
				redirect('admin/invite');
				}
			}
	}
	
	public function deletedManagers(){
		$data = array('pageTitle' =>'Deleted Managers', 'managers'=>$this->manager->getDeletedManagers());
		$this->baseloader->adminviews('deletedManagers',$data);
	}

	public function domainupdate(){
		$domain = $_POST['domain'];
		$url = 'http://loraserver.intellio-dev.com:1880/api/applications';
		$curlData = [
			"name" => $domain,
			"description" => $domain
		];
		$d = json_encode($curlData);
		$res = new_curl_request('POST', $url, $d);
		if($res['status'] == 200) {
			$result = $this->invite->updateDomain($_POST);
			$eRes = json_decode($res['response']);
			$this->session->set_flashdata('success', $eRes->message);
		}else{
			$eRes = json_decode($res['response']);
			$this->session->set_flashdata('error', $eRes->message);
		}
		redirect('admin/invite');
	}

	public function customreportsettings($id=0) {
		$event_setting = $this->hourlyevents->getGlobalEventSetting();
		if($event_setting){
			$id = $event_setting['id'];
		}else{
			$id = '';
		}
		$pageData = array(
			'pageTitle'=>'Shift Change Time',
			'action'=>"admin/invite/customreportsettings/". $id,
			'event_setting' => $event_setting
		);

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$data = $_POST;
			$start1 = "2022-08-01 " . $data['wStart'] . ":00";
			$end1 = "2022-08-01 " . $data['wEnd'] . ":00";
			$start2 = "2022-08-01 " . $data['w2Start'] . ":00";
			$end2 = "2022-08-01 " . $data['w2End'] . ":00";
			$date_a = new DateTime($start1);
			$date_b = new DateTime($end1);
			$date_c = new DateTime($start2);
			$date_d = new DateTime($end2);
			$diff1 = $date_b->getTimestamp() - $date_a->getTimestamp();
			$diff2 = $date_d->getTimestamp() - $date_c->getTimestamp();
			// var_dump($diff1);exit;
			if(($diff1 < 120 || $diff1 > 1800) || ($diff2 < 120 || $diff2 > 1800)) {
				// Window diff not between 2-30 minutes
				$this->session->set_flashdata('error','Time difference can\'t be less then 2 minutes and greater than 30 minutes.');
				// $this->baseloader->adminviews('globalreportwindows',$pageData);
			}else {
				if($id){
					$data['id'] = $id;
				}
				$date = date('Y-m-d H:i:s');
				$data['created_at'] = $date;
				$data['updated_at'] = $date;
				$result=$this->hourlyevents->updateGlobalReportWindow($data);
				$this->session->set_flashdata('success','Report Window updated successfully');
				redirect('admin/invite');
			}
			
		}
		$this->baseloader->adminviews('globalreportwindows',$pageData);

}
}


?>
