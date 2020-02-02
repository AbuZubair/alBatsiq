<?php
    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Home extends MX_Controller {

        function __construct() {
            parent::__construct();
               
            if($this->session->userdata('logged')!=TRUE){
                redirect(base_url().'login');exit;
            }
            
        }
    
        public function index()
        {

            $this->temp_header->get_load();          

        }

    
    }
    
    /* End of file Home.php */
    
?>