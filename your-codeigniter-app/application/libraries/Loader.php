<?php
        
class Baseloader {
        protected $CI;

        // We'll use a constructor, as you can't directly call a function
        // from a property definition.
        public function __construct()
        {
                // Assign the CodeIgniter super-object
                $this->CI =& get_instance();
        }

        function adminviews($viewname,$data=array())
        {
            $this->load->view('common/admin_header',$data);
            $this->load->view($viewname);
            $this->load->view('common/footer');
            
        }

        function userviews($viewname,$data=array())
        {
            $this->load->view('common/header',$data);
            $this->load->view($viewname);
            $this->load->view('common/footer');
            
        }
        function managerviews($viewname,$data=array())
        {
		    $this->CI->load->view('common/managers_header',$data);
		    $this->CI->load->view($viewname);
		    $this->CI->load->view('common/footer.php');
		}

}

?>
