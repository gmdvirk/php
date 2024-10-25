<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class DeviceFileUploads_model extends CI_Model {
        private $table = 'device_file_uploads';

        public function createFileUpload($data){
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            if($this->db->insert($this->table, $data)){
                $insert_id = $this->db->insert_id();
            }else{
                $insert_id = false;
            }
            return  $insert_id;
        }

        public function getFileUploadById($id){
            return $this->db->where('id', $id)->get($this->table)->row_array(); 
        }

        public function getFileUploads(){
            $this->db->get($this->table, 10, 20);
        }

        public function updateFileUpload($id, $data){
            return $this->db->where('id',$id)
            ->update($this->table,$data);
        }
    }