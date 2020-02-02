<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Temp_body
{

    private $_CI;

    public function __construct()
    {
        $this->_CI =& get_instance();
        //$this->_CI->load->library("firephp");
        $this->_CI->load->model('templates/m_user');

       
    }
    public function get_load($mod = null)
    {
        $this->_CI->load->library('lib_menus');
            $data = array(
                'modul' => $this->_CI->lib_menus->get_modules_by_program_name($mod,$this->_CI->session->userdata('leveluser')),
            );

            $this->_CI->load->view('templates/template_body', $data);
            
        }
}

/* End of file Template.php */
/* Location: ./application/libraries/Template.php */
