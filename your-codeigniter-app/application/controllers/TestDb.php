// In application/controllers/TestDb.php
defined('BASEPATH') OR exit('No direct script access allowed');

class TestDb extends CI_Controller {

    public function index() {
        $this->load->database();

        if ($this->db->conn_id) {
            echo "Database connection successful!";
        } else {
            echo "Database connection failed!";
        }
    }
}
