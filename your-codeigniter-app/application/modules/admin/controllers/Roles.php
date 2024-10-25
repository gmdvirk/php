<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends MY_Controller {

    public function __construct() {

        $this->load->model('Role_model','roles');
		if(!$this->session->userdata('isAdmin')) {
			redirect('admin/login');
		}
        $this->load->model('UploadedUnits_model', 'uploaded_units');
    }
    
    function index() {
        $data = array(
			'pageTitle' =>'Roles',
            'roles' => $this->roles->getRoles()
		);
		$this->baseloader->adminviews('roles/index',$data);
    }

    function create() {
        $data = array(
			'pageTitle' =>'Create Role',
            'action' => base_url('admin/roles/create'),
		);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $existingRoles = $this->roles->searchRole(['name' => $_POST['roleName']]);
            if(!count($existingRoles)){
                $roleCreated = $this->roles->createRole([
                    'name' => $_POST['roleName'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);
             
                if($roleCreated){
                    $this->session->set_flashdata('success','Role Created successfully');
                    redirect('admin/roles');
                }
            }else{
                $this->session->set_flashdata('error','Role already exist.');
            }
        }
		$this->baseloader->adminviews('roles/create',$data);
    }

    function edit($id) {
        $existingRoles = $this->roles->searchRole(['id' => $id]);
        if(count($existingRoles)) {
            $editedRole = $existingRoles[0];
            $data = array(
                'pageTitle' =>'Edit Role',
                'action' => base_url('admin/roles/update/'. $id),
                'role' => $editedRole
            );
            $this->baseloader->adminviews('roles/create',$data);
        }else {
            $this->session->set_flashdata('error','Role does not exist.');
            redirect('admin/roles');
        }
    }

    function update($id) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $existingRoles = $this->roles->searchRole(['name' => $_POST['roleName']]);
            if(!count($existingRoles)){
                $roleUpdated = $this->roles->updateRole($id, [ 
                    'name' => $_POST['roleName'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);
             
                if($roleUpdated){
                    $this->session->set_flashdata('success','Role Updated successfully');
                    redirect('admin/roles');
                }
            }else{
                $this->session->set_flashdata('error','Role already exist.');
            }
        }
		redirect('admin/roles');
    }

    function delete($id) {
        $deleted = $this->roles->deleteRolePk($id);
        if($deleted) {
            $this->session->set_flashdata('success','Role Deleted successfully.');
        }else {
            $this->session->set_flashdata('error','Unable to deleted role.');
        }
        redirect('admin/roles');
    }

    function upload() {
        // Role upload is using uploaded_units table to temprory upload.
        // handle form submition
        if ($this->input->server('REQUEST_METHOD') == 'POST'){
            $config = [];
            $config['upload_path']          = './uploads/facility_bin/';
			$config['allowed_types']        = 'csv';
			$config['max_size']             = 100; //kb
			$config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('csv_file')){
                $fileErr = $this->upload->display_errors();
				if(strpos($fileErr, "filetype you are attempting to upload is not allowed")){
					$fileErr = 'The filetype is not allowed” -> “CSV required, file is not CSV';
				}
				$this->session->set_flashdata('error',$fileErr);
				redirect('admin/roles/upload');
            }else {
                $file_data = $this->upload->data();
				$file_name = $file_data['file_name'];
				$file_path = $file_data['full_path'];
                if (!mb_check_encoding(file_get_contents($file_path), 'UTF-8')) {
					$this->session->set_flashdata('error','Detect unicode error, ex UTF-16 -> “CSV type must be UTF-8');
					redirect('admin/roles/upload');
				}

                $this->load->library('CSVReader');
				$csvData = $this->csvreader->parse_csv($file_path);
                // var_dump($csvData);exit;
                if(!empty($csvData)){
                    $this->load->model('UserFileUploads_model','fileupload');
                    $data['uploaded_by'] = $this->session->userdata('iId');
					$data['file_name'] = $file_name;
					$upload_id = $this->fileupload->createFileUpload($data);
                    if($upload_id){
                        $valid_header = ['name'];
                        $units_array = [];
                        foreach($csvData as $row){
                            $orgKeys = array_keys($row);
                            $csv_header = array_map('trim',array_map('strtolower', $orgKeys));
                            if($valid_header !== $csv_header){
								$this->session->set_flashdata('error','Bad columns. Format must be ('.implode(', ', $valid_header) . ')');
								redirect('admin/roles/upload');
							}
                            $singleUnit = [
                                'upload_id' => $upload_id,
                                'name' => $row[$orgKeys[0]]
                            ];

                            $roles_array[] = $singleUnit;

                        }
                        $rolesUploaded = $this->uploaded_units->createBulkUploadedUnits($roles_array);
                        if($rolesUploaded){
                            $this->session->set_flashdata('success','File uploaded successfully.');
                            redirect('admin/roles/uploadedlist/'.$upload_id);
                        }
                    }else{
						$this->session->set_flashdata('error','Something we wrong, please try later.');
						redirect('admin/roles/upload');
					}
                }else{
					$this->session->set_flashdata('error','No data in csv file.');
					redirect('admin/roles/upload');
				}
            }
        }

        $data = [
            'pageTitle' => 'Upload Roles',
            'action' => 'admin/roles/upload'
        ];

        $this->baseloader->adminviews('roles/upload', $data);
    }

    public function uploadedlist($upload_id){
		$this->load->model('UserFileUploads_model','fileupload');
		$upload = $this->fileupload->getFileUploadById($upload_id);
		if(!$upload){
			$upload['id'] = '0';
		}
		
		$uploaded_roles = $this->uploaded_units->getUploadedUnitsByUploadId($upload_id);
		$data = array(
			'upload'=>$upload,
			'uploaded_units' => $uploaded_roles,
			'pageTitle'=>'Uploaded Roles'
		);
		$this->baseloader->adminviews('uploaded_roles',$data);

	}


    public function ignoreuploaded(){
		$unit_id = $this->input->post('unit');
		$ignore = $this->input->post('ignore');
		if($this->uploaded_units->updateUploadedUnit($unit_id, ['ignored' => $ignore]))
			$response['success'] = true;
		else
			$response['success'] = false;
		echo json_encode($response);
	}

    public function registerroles($upload_id) {
        $this->load->model('UserFileUploads_model','fileupload');
        $upload = $this->fileupload->getFileUploadById($upload_id);
        if($upload['is_processed'] > 0){
			$this->session->set_flashdata('error','Records already processed.');
			redirect('admin/roles/uploadedlist/'.$upload_id);
		}
        $select = $this->uploaded_units->getRolesToRegister($upload_id);
        // var_dump($select->result_array());exit;
        if($select->num_rows()){
            $inserted = $this->roles->registerUploadedRoles($select->result_array());
            if($inserted){
				$this->fileupload->updateFileUpload($upload_id, ['is_processed' => '1']);
				$this->session->set_flashdata('success','Roles inserted successfully.');
				redirect('admin/roles/uploadedlist/'.$upload_id);
			}else{
				$this->session->set_flashdata('error','Something went wrong, please try later.');
				redirect('admin/roles/uploadedlist/'.$upload_id);
			}
        }else{
            $this->session->set_flashdata('error','No unit to insert.');
			redirect('admin/roles/uploadedlist/'.$upload_id);
        }
    }

}