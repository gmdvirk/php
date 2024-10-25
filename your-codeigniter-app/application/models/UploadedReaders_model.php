<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class UploadedReaders_model extends CI_Model {

        
        public function __construct(){
            $this->table = 'uploaded_readers';
            $this->column_search = array('vReaderSerial','vDescription');
            $this->column_order = array(null, 'vReaderSerial','vDescription');
            $this->order = array('id' => 'asc');
        }

        public function createBulkUploadedReaders($data){
            return $this->db->insert_batch($this->table,$data);
        }

        public function getUploadedReadersByUploadId($upload_id){
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

        public function getReadersToRegister($upload_id){
            return $this->db->
                select('c.vReaderSerial as vReaderSerial, c.vDescription as vDescription, c.facility_id as vFacility, c.vUnit as vUnit, c.roomNumber as roomNumber')
                ->from($this->table.' as c')
                ->join('user_file_uploads as u', 'u.id = c.upload_id')
                ->join('readers as r', '(r.vReaderSerial = c.vReaderSerial AND r.vFacility = c.facility_id)', 'left')
                ->where('upload_id', $upload_id)
                ->where('r.vReaderSerial IS NULL')
                ->where('ignored', 0)
                ->get();
        }

        public function updateUploadedReader($id, $data){
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