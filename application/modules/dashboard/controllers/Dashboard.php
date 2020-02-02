<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends MX_Controller {

    function __construct() {
        parent::__construct();
        /*breadcrumb default*/
        //$this->breadcrumbs->push('Home', 'main');

        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');exit;
        }

    }

    public function index() {
        
        //$this->output->enable_profiler(false);
        /*breadcrumb*/
        //$this->breadcrumbs->push('Welcome', 'main/'.strtolower(get_class($this)));
        
        //echo '<pre>';
        //print_r($data);die;
        $mod = (str_replace('_', ' ', strtolower($_GET['mod'])));
        $this->temp_header->get_load($mod);

        $this->temp_body->get_load($mod);
    }

}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

