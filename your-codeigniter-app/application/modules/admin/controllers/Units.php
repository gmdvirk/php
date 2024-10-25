<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Units extends MY_Controller {

    public function __construct() {

        $this->load->model('Unit_model','units');
		if(!$this->session->userdata('isAdmin')) {
			redirect('admin/login');
		}
        $this->load->model('UploadedUnits_model', 'uploaded_units');
    }
    
    function index() {
        $data = array(
			'pageTitle' =>'Units',
            'units' => $this->units->getUnits()
		);
		$this->baseloader->adminviews('units/index',$data);
    }

    function create() {
        $data = array(
			'pageTitle' =>'Create Unit',
            'action' => base_url('admin/units/create'),
		);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $existingUnits = $this->units->searchUnit(['name' => $_POST['unitName']]);
            if(!count($existingUnits)){
                $unitCreated = $this->units->createUnit([
                    'name' => $_POST['unitName'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);
             
                if($unitCreated){
                    $this->session->set_flashdata('success','Unit Created successfully');
                    redirect('admin/units');
                }
            }else{
                $this->session->set_flashdata('error','Unit already exist.');
            }
        }
		$this->baseloader->adminviews('units/create',$data);
    }

    function edit($id) {
        $existingUnits = $this->units->searchUnit(['id' => $id]);
        if(count($existingUnits)) {
            $editedUnit = $existingUnits[0];
            $data = array(
                'pageTitle' =>'Edit Unit',
                'action' => base_url('admin/units/update/'. $id),
                'unit' => $editedUnit
            );
            $this->baseloader->adminviews('units/create',$data);
        }else {
            $this->session->set_flashdata('error','Unit does not exist.');
            redirect('admin/units');
        }
    }

    function update($id) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $existingUnits = $this->units->searchUnit(['name' => $_POST['unitName']]);
            if(!count($existingUnits)){
                $unitUpdated = $this->units->updateUnit($id, [ 
                    'name' => $_POST['unitName'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);
             
                if($unitUpdated){
                    $this->session->set_flashdata('success','Unit Updated successfully');
                    redirect('admin/units');
                }
            }else{
                $this->session->set_flashdata('error','Unit already exist.');
            }
        }
		redirect('admin/units');
    }

    function delete($id) {
        $deleted = $this->units->deleteUnitPk($id);
        if($deleted) {
            $this->session->set_flashdata('success','Unit Deleted successfully.');
        }else {
            $this->session->set_flashdata('error','Unable to deleted unit.');
        }
        redirect('admin/units');
    }

    function upload() {

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
				redirect('admin/units/upload');
            }else {
                $file_data = $this->upload->data();
				$file_name = $file_data['file_name'];
				$file_path = $file_data['full_path'];
                if (!mb_check_encoding(file_get_contents($file_path), 'UTF-8')) {
					$this->session->set_flashdata('error','Detect unicode error, ex UTF-16 -> “CSV type must be UTF-8');
					redirect('admin/units/upload');
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
								redirect('admin/units/upload');
							}
                            $singleUnit = [
                                'upload_id' => $upload_id,
                                'name' => $row[$orgKeys[0]]
                            ];

                            $units_array[] = $singleUnit;

                        }
                        $unitsUploaded = $this->uploaded_units->createBulkUploadedUnits($units_array);
                        if($unitsUploaded){
                            $this->session->set_flashdata('success','File uploaded successfully.');
                            redirect('admin/units/uploadedlist/'.$upload_id);
                        }
                    }else{
						$this->session->set_flashdata('error','Something we wrong, please try later.');
						redirect('admin/units/upload');
					}
                }else{
					$this->session->set_flashdata('error','No data in csv file.');
					redirect('admin/units/upload');
				}
            }
        }

        $data = [
            'pageTitle' => 'Upload Units',
            'action' => 'admin/units/upload'
        ];

        $this->baseloader->adminviews('units/upload', $data);
    }

    public function uploadedlist($upload_id){
		$this->load->model('UserFileUploads_model','fileupload');
		$upload = $this->fileupload->getFileUploadById($upload_id);
		if(!$upload){
			$upload['id'] = '0';
		}
		
		$uploaded_units = $this->uploaded_units->getUploadedUnitsByUploadId($upload_id);
		$data = array(
			'upload'=>$upload,
			'uploaded_units' => $uploaded_units,
			'pageTitle'=>'Uploaded Units'
		);
		$this->baseloader->adminviews('uploaded_units',$data);

	}

    public function uploadedlist_dt($upload_id){
        $this->load->model('UserFileUploads_model','fileupload');
		$data = $row = array();
		$upload = $this->fileupload->getFileUploadById($upload_id);
        $unitsData = $this->uploaded_units->getRows($_POST, $upload_id);
        $i = $_POST['start'];
		$disabled = ($upload['is_processed'] == '1') ? 'disabled' : '';
        foreach($unitsData as $unit){
			$i++;
			$checked = $unit->ignored > 0 ? 'checked' : '';
			$data[] = array(
				$i, 
				$unit->name,
				'<input type="checkbox" class="form-check-input" '. $disabled .' onclick="ignoreunit(this)" id="'.$unit->id.'" '.$checked.'>'
			);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->uploaded_units->countAll(),
            "recordsFiltered" => $this->uploaded_units->countFiltered($_POST, $upload_id),
            "data" => $data,
        );
        
        echo json_encode($output);
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

    public function registerunits($upload_id) {
        $this->load->model('UserFileUploads_model','fileupload');
        $upload = $this->fileupload->getFileUploadById($upload_id);
        if($upload['is_processed'] > 0){
			$this->session->set_flashdata('error','Records already processed.');
			redirect('admin/units/uploadedlist/'.$upload_id);
		}
        $select = $this->uploaded_units->getUnitsToRegister($upload_id);
        // var_dump($select->result_array());exit;
        if($select->num_rows()){
            $inserted = $this->units->registerUploadedUnits($select->result_array());
            if($inserted){
				$this->fileupload->updateFileUpload($upload_id, ['is_processed' => '1']);
				$this->session->set_flashdata('success','Units inserted successfully.');
				redirect('admin/units/uploadedlist/'.$upload_id);
			}else{
				$this->session->set_flashdata('error','Something went wrong, please try later.');
				redirect('admin/units/uploadedlist/'.$upload_id);
			}
        }else{
            $this->session->set_flashdata('error','No unit to insert.');
			redirect('admin/units/uploadedlist/'.$upload_id);
        }
    }
}