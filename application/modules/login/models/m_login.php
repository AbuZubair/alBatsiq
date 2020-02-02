<?php


    defined('BASEPATH') OR exit('No direct script access allowed');

    class M_login extends CI_Model {

        public function ceklogin($table,$where)
        {
           // return $this->db->get_where($table,$where);
            $this->db->select(''.$table.'.*,unit.loc_id');
		    $this->db->from($table);
            $this->db->join('unit','unit.unit_id = '.$table.'.unit_id', 'left');
            $this->db->where($where);
            return $this->db->get(); 
        }
        
    }

    /* End of file M_login.php */


?>