<?php


    defined('BASEPATH') OR exit('No direct script access allowed');

    class M_login extends CI_Model {

        public function ceklogin($table,$where)
        {
            return $this->db->get_where($table,$where);
        }
        
    }

    /* End of file M_login.php */


?>