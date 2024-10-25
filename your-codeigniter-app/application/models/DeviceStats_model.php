<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class DeviceStats_model extends CI_Model {
        private $battery_table = 'device_battery_stats';
        private $radio_table = 'device_radio_stats';
        private $esb_table = 'device_esb_stats';

        public function createDeviceBatteryStat($data) {
            if($this->db->insert($this->battery_table, $data)){
                $insert_id = $this->db->insert_id();
            }else{
                $insert_id = false;
            }
            return  $insert_id;
        }

        public function createDeviceRadioStat($data) {
            if($this->db->insert($this->radio_table, $data)){
                $insert_id = $this->db->insert_id();
            }else{
                $insert_id = false;
            }
            return  $insert_id;
        }

        public function createDeviceEsbStat($data) {
            if($this->db->insert($this->esb_table, $data)){
                $insert_id = $this->db->insert_id();
            }else{
                $insert_id = false;
            }
            return  $insert_id;
        }
    }
?>