<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class BadgeBatteryState_model extends CI_Model {
        private $battery_table = 'badge_battery_state';
        private $esb_table = 'badge_esb_state';

        public function createBadgeBatteryState($data) {
            if($this->db->insert($this->battery_table, $data)){
                $insert_id = $this->db->insert_id();
            }else{
                $insert_id = false;
            }
            return  $insert_id;
        }

        public function createBadgeEsbState($data) {
            if($this->db->insert($this->esb_table, $data)){
                $insert_id = $this->db->insert_id();
            }else{
                $insert_id = false;
            }
            return  $insert_id;
        }
    }
?>