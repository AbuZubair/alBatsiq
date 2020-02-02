<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Temp_header
{

    private $_CI;

    public function __construct()
    {
        $this->_CI =& get_instance();
        //$this->_CI->load->library("firephp");
        $this->_CI->load->model('control/m_user');

       
    }
    public function get_load($mod = null)
    {
        $this->_CI->load->library('lib_menus');
            $data = array(
                'user' => $this->_CI->m_user->get_by_id($this->_CI->session->userdata('username')),
                'modul' => $this->_CI->lib_menus->get_modules_by_user_id($this->_CI->session->userdata('username')),
                'mod' => $mod,
            );

            $this->_CI->load->view('templates/template', $data);

            //$this->_CI->templates->template($data);
            
        }
}

/* End of file Template.php */
/* Location: ./application/libraries/Template.php */
