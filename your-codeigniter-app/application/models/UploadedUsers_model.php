<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class UploadedUsers_model extends CI_Model {

        
        public function __construct(){
            $this->table = 'uploaded_users';
            $this->column_search = array('firstname','lastname','role','unit');
            $this->column_order = array(null, 'firstname','lastname','role','unit');
            $this->order = array('id' => 'asc');
        }

        public function createBulkUploadedUsers($data){
            return $this->db->insert_batch($this->table,$data);
        }

        public function getUploadedUsersByUploadId($upload_id){
            return $this->db
                ->where('upload_id', $upload_id)
                ->get($this->table)
                ->result();
        }


        public function getRows($postData, $upload_id){
            $this->_get_datatables_query($postData, $upload_id);
            if($postData['length'] != -1){
                $this->db->limit($postData['length'], $postData['start']);
            }
            $query = $this->db->get();
            return $query->result();
        }

        private function _get_datatables_query($postData, $upload_id){         
            $this->db->from($this->table);
            $i = 0;
            foreach($this->column_search as $item){
                if($postData['search']['value']){
                    if($i===0){
                        $this->db->group_start();
                        $this->db->like($item, $postData['search']['value']);
                    }else{
                        $this->db->or_like($item, $postData['search']['value']);
                    }
                    if(count($this->column_search) - 1 == $i){
                        $this->db->group_end();
                    }
                }
                $i++;
            }
            $this->db->where('upload_id', $upload_id);             
            if(isset($postData['order'])){
                $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
            }else if(isset($this->order)){
                $order = $this->order;
                $this->db->order_by(key($order), $order[key($order)]);
            }
        }

        public function countAll(){
            $this->db->from($this->table);
            return $this->db->count_all_results();
        }

        public function countFiltered($postData, $upload_id){
            $this->_get_datatables_query($postData, $upload_id);
            $query = $this->db->get();
            return $query->num_rows();
        }

        public function getUsersToRegister($upload_id){
            return $this->db->
                select('c.firstname as vFirstName, c.lastname as vLastName, c.role as vRole, c.unit as vUnit, c.facility_id as vAssigned_Facility_ID, c.start_date as vStart, c.end_date as vEnd')
                ->from($this->table.' as c')
                ->join('user_file_uploads as u', 'u.id = c.upload_id')
                ->where('upload_id', $upload_id)
                ->where('ignored', 0)
                ->get();
        }

        public function updateUploadedUser($id, $data){
            return $this->db->where('id',$id)
                        ->update($this->table,$data);
        }

        public function getUploadedUserById($id){
            return $this->db
                ->where('id', $id)
                ->get($this->table)
                ->row_array(); 
        }
    }
?>