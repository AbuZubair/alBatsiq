<?php

    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class User extends MX_Controller {

        public function __construct()
        {
            parent::__construct();
            //Load Dependencies
            $this->load->model('m_user','m_user'); 

            $this->load->library('bcrypt');
            $this->title = ($this->lib_menus->get_menu_by_link('control/user'))?$this->lib_menus->get_menu_by_link('control/user')->program_name : 'Title';
            $this->level = ($this->lib_menus->get_menu_by_link('control/user'))?$this->lib_menus->get_menu_by_link('control/user')->level_program : 'Level';
            $this->parent = ($this->lib_menus->get_menu_by_link('control/user'))?$this->lib_menus->get_menu_by_link('control/user')->parent_name : 'Parent';


            if (!$this->session->userdata['logged'])
            {
             redirect('login/logout');
            }
            
        }
    
        public function index()
        {
            $data = array(
                'title' => $this->title,
                'level' => $this->level,
                'parent' => $this->parent,
            );

            $this->load->view('control/user/index',$data);
            
        }
        
        public function form($id='')
        {
            /*if id is not null then will show form edit*/
            if( $id != '' ){
                
                /*get value by id*/
                $data['value'] = $this->m_user->get_by_id($id);
                /*initialize flag for form*/
                $data['flag'] = "update";
            }else{
                /*initialize flag for form add*/
                $data['flag'] = "create";
            }
            /*title header*/
            $data['level'] = $this->m_user->get_level();
            $data['unit'] = $this->m_user->get_unit();
            

            $this->load->view('control/user/form', $data);
        }

        public function reset_pwd($id='')
        {
            
            $data['value'] = $this->m_user->get_by_id($id);
                  
            $this->load->view('control/user/reset_pwd', $data);
        }

        public function show($id)
        {
            /*breadcrumbs for view*/
           // $this->breadcrumbs->push('View '.$this->title.'', 'reference/mst_global_param/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$gp_id);
            /*define data variabel*/
            $data['value'] = $this->m_user->get_by_id($id);
          //  $data['title'] = $this->title;
            $data['flag'] = "read";
            $data['level'] = $this->m_user->get_level();
            $data['unit'] = $this->m_user->get_unit();
         //   $data['breadcrumbs'] = $this->breadcrumbs->show();
            /*load form view*/
           // $this->template->get_load();

            $this->load->view('control/user/form', $data);
        }
    
       
        public function get_data()
        {
            /*get data from model*/
            $list = $this->m_user->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $row[] = '<div class="center"><label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->user_id.'"/>
                            <span class="lbl"></span>
                        </label></div>';
                $row[] = $row_list->username; 
                $row[] = strtoupper($row_list->nama_lengkap);
                $row[] = $row_list->level_name;
                $row[] = '<div class="center">
                                '.$this->authuser->show_button('control/user','R',$row_list->user_id,2).'
                                '.$this->authuser->show_button('control/user','U',$row_list->user_id,1).'
                                '.$this->authuser->show_button('control/user','D',$row_list->user_id,2).'
                                
                            </div>';
                                               
                $data[] = $row;
            }

            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->m_user->count_all(),
                            "recordsFiltered" => $this->m_user->count_filtered(),
                            "data" => $data,
                    );
            //output to json format
            echo json_encode($output);
        }

        public function process($flag)
    {
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('username', 'Username', 'trim|required');
        $val->set_rules('nama_lengkap', 'Nama Lengkap', 'trim|required');
        $val->set_rules('level_id', 'User Role', 'trim|required');
        if($flag=='create'){
            $val->set_rules('password', 'Password', 'trim|required|min_length[6]');
            $val->set_rules('confirm', 'Password Confirmation', 'trim|required|matches[password]');
            $val->set_rules('username', 'Username', 'is_unique[user.username]');
        } 
        $val->set_rules('unit_id', 'Unit', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");
        $val->set_message('matches', "\"%s\" tidak sesuai dengan password");
        $val->set_message('valid_email', "\"%s\" tidak valid");
        $val->set_message('min_length', "\"%s\" minimal 6 karakter");
        $val->set_message('is_unique', "\"%s\" sudah digunakan");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            $id = ($this->input->post('id'))?$this->regex->_genRegex($this->input->post('id'),'RGXINT'):0;
            $dataexc = array(
                'username' => $this->regex->_genRegex($val->set_value('username'),'RGXQSL'),
                'nama_lengkap' => $this->regex->_genRegex($val->set_value('nama_lengkap'),'RGXQSL'),
                'level_id' => $this->regex->_genRegex($val->set_value('level_id'),'RGXINT'),
                'unit_id' => $this->regex->_genRegex($val->set_value('unit_id'),'RGXQSL'),
                'is_active' => $this->input->post('is_active'),
            );
            $pwd = strtolower($this->input->post('password'));
            $dataexc['password'] = md5( $pwd);
            //print_r($dataexc);
            //print_r($id);die;
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL')));
                /*save post data*/
                $this->m_user->save($dataexc);
                //$this->logs->save('tmp_user', $newId, 'insert new record', json_encode($dataexc), 'user_id');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL')));
                /*update record*/
                $this->m_user->update(array('user_id' => $id), $dataexc);
            }
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
            }
        }
    }

    public function proses_reset_pwd($id)
    {
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $val->set_rules('confirm', 'Password Confirmation', 'trim|required|matches[password]');
       
        $val->set_message('required', "Silahkan isi field \"%s\"");
        $val->set_message('matches', "\"%s\" tidak sesuai dengan password");
        $val->set_message('min_length', "\"%s\" minimal 6 karakter");
     
        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            $pwd = strtolower($this->input->post('password'));
            $dataexc['password'] = md5( $pwd);
           // print_r($dataexc);
           // print_r($id);die;
            
            $dataexc['updated_date'] = date('Y-m-d H:i:s');
            $dataexc['updated_by'] = json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL')));
            /*update record*/
            $this->m_user->update(array('user_id' => $id), $dataexc);
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
            }
        }
    }

    public function delete()
    {
        $user_id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        $toArray = explode(',',$user_id);
        //print_r($user_id);print_r($toArray);die;
        if($user_id!=null){
            if($this->m_user->delete_by_id($toArray)){
                //$this->logs->save('tmp_mst_global_parameter', $user_id, 'delete record', '', 'user_id');
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }
    
    }

    
    
    /* End of file User.php */
    

?>