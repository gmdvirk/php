<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gateway extends MY_Controller {

	public function __construct()
	{
		$this->load->model('gateway_model','gateway');
	}

    public function index(){
        $data = array(
			'pageTitle' =>'Gateways',
		);
		$this->baseloader->adminviews('gateways',$data);
    }

    public function create(){
		$data = array(
			'pageTitle' => 'Create Gateway',
			'action' => base_url('admin/gateway/create'),
		);
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$response = $this->insertIntoChirpStack($_POST);
			if($response["status"] == 200){
				// Insert gateway in local database
				$gateway_insert = $this->insertGateway($_POST);
				if($gateway_insert){
					$this->session->set_flashdata('success','Gateway Create successfully');
					redirect('admin/gateway');
				}else{
					// Delete from chirpStack
					$response = $this->deleteFromChirpStack($data['gateway_id']);
					$this->session->set_flashdata('error','Server error.');
					redirect('admin/gateway');
				}
			}else{
				$err = json_decode($response["response"], true);
				$msg = $err["message"];
				if($msg == "object already exists"){
					$msg = 'Gateway has already been added.';
				}
				$this->session->set_flashdata('error',$msg);
				return $this->baseloader->adminviews('editGateway',$data);
			}
		}
		$this->baseloader->adminviews('editGateway',$data);
	}

	// public function createGateway(){
	// 	$data = array(
	// 		'pageTitle' => 'Create Gateway',
	// 		'action' => base_url('admin/gateway/createGateway'),
	// 	);
		
	// 	$curlData = [
	// 		"name" => $_POST["name"],
	// 		"description" => $_POST["description"],
	// 		"location" => [
	// 			'latitude' => $_POST["latitude"],
	// 			'longitude' => $_POST["longitude"]
	// 		],
	// 		"id" => $_POST["gateway_id"],
	// 	];
	// 	$url = 'http://loraserver.intellio-dev.com:1880/api/gateways';
	// 	$d = json_encode($curlData);
	// 	echo $response = new_curl_request('POST', $url, $d);exit;
	// 	if($response["status"] == 200){
	// 		// Insert gateway in local database
	// 		$this->insertGateway($_POST);
	// 	}else{
	// 		$err = json_decode($response["response"], true);
	// 		$this->session->set_flashdata('error',$err["message"]);
	// 		return $this->baseloader->adminviews('editGateway',$data);
	// 	}
	// }

	public function insertGateway($data){
		$gateway_insert = $this->gateway->createGateway($data);
		return $gateway_insert;
	}


	public function gatewaylist_dt(){
		$data = $row = array();
		$gatewayData = $this->gateway->getGatewayRows($_POST);
        $i = $_POST['start'];
        foreach($gatewayData as $gateway){
			$i++;			
			$data[] = array(
				$i,
				$gateway->gateway_id, 
				$gateway->name,
				$gateway->description,
				$gateway->latitude, 
				$gateway->longitude,
				'<a href="'.base_url('admin/gateway/editGateway/'.$gateway->id).'" class="btn btn-flat btn-sm btn-info"><i class="fa fa-edit"></i></a>
				<a onclick="return confirm(\'Are you sure you want to delete this Gateway?\');" href="'.base_url('admin/gateway/deleteGateway/'.$gateway->id).'" class="btn btn-flat btn-sm btn-danger"><i class="fa fa-trash"></i></a>
				'
			);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->gateway->countAll(),
            "recordsFiltered" => $this->gateway->countFiltered($_POST),
            "data" => $data,
        );        
        echo json_encode($output);
	}


	public function deleteGateway($id){
		if(!$id) {
			$this->session->set_flashdata('error','Gateway Id is required');
			redirect('admin/gateway');
		}

		$gatewayToDelete = $this->gateway->getGatewayById($id);
		if($gatewayToDelete){
			$response = $this->deleteFromChirpStack($gatewayToDelete->gateway_id);
			if($response["status"] == 200){
				$delete_gateway = $this->gateway->deleteGateway($id);
				if($delete_gateway){
					$this->session->set_flashdata('success','Gateway Deleted successfully');
					redirect('admin/gateway');
				}else{
					$this->session->set_flashdata('error','Server error.');
					redirect('admin/gateway');
				}
			}else{
				$err = json_decode($response["response"], true);
				$this->session->set_flashdata('error',$err["message"]);
				redirect('admin/gateway');
			}
		}else{
			$this->session->set_flashdata('error','Gateway not available.');
			redirect('admin/gateway');
		}
		
	}

	public function editGateway($id){
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$gatewayToUpdate = $this->gateway->getGatewayById($id);
			if($gatewayToUpdate){
				$dResponse = $this->deleteFromChirpStack($gatewayToUpdate->gateway_id);
				if($dResponse["status"] == 200){
					$delete_gateway = $this->gateway->deleteGateway($id);
					if($delete_gateway){
						$response = $this->insertIntoChirpStack($_POST);
						if($response["status"] == 200){
							// Insert gateway in local database
							$gateway_insert = $this->insertGateway($_POST);
							if($gateway_insert){
								$this->session->set_flashdata('success','Gateway Updated successfully');
								redirect('admin/gateway');
							}else{
								// Delete from chirpStack
								$response = $this->deleteFromChirpStack($_POST['gateway_id']);
								$this->session->set_flashdata('error','Server error.');
								redirect('admin/gateway');
							}
						}else{
							$err = json_decode($response["response"], true);
							$this->session->set_flashdata('error',$err["message"]);
							redirect('admin/gateway');
						}
					}else{
						$this->session->set_flashdata('error','Server error.');
						redirect('admin/gateway');
					}
				}else{
					$err = json_decode($dResponse["response"], true);
					$this->session->set_flashdata('error',$err["message"]);
					redirect('admin/gateway');
				}
			}else{
				$this->session->set_flashdata('error','Gateway not available.');
				redirect('admin/gateway');
			}
		}
		$data = array(
			'pageTitle'=> 'Edit Gateway', 
			'action' => base_url("admin/gateway/editGateway/".$id),
			'gateway'=> $this->gateway->getGatewayById($id),
		);
		$this->baseloader->adminviews('editGateway',$data);
	}

	public function deleteFromChirpStack($chirpStackId){
		$curlData = [
			"id" => $chirpStackId,
		];
		$url = 'http://loraserver.intellio-dev.com:1880/api/gateways';
		$d = json_encode($curlData);
		$response = new_curl_request('DELETE', $url, $d);
		return $response;
	}

	public function insertIntoChirpStack($postData){
		$curlData = [
			"name" => $postData["name"],
			"description" => $postData["description"],
			"location" => [
				'latitude' => $postData["latitude"],
				'longitude' => $postData["longitude"]
			],
			"id" => $postData["gateway_id"],
		];
		$url = 'http://loraserver.intellio-dev.com:1880/api/gateways';
		$d = json_encode($curlData);
		$response = new_curl_request('POST', $url, $d);
		return $response;
	}
}
?>