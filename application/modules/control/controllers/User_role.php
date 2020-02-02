<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class User_role extends MX_Controller {

        public function __construct()
        {
            parent::__construct();
            //Load Dependencies
            $this->load->model('m_user_role','m_user_role'); 

            $this->load->library('bcrypt');
            $this->title = ($this->lib_menus->get_menu_by_link('control/user_role'))?$this->lib_menus->get_menu_by_link('control/user_role')->program_name : 'Title';
            $this->level = ($this->lib_menus->get_menu_by_link('control/user_role'))?$this->lib_menus->get_menu_by_link('control/user_role')->level_program : 'Level';
            $this->parent = ($this->lib_menus->get_menu_by_link('control/user_role'))?$this->lib_menus->get_menu_by_link('control/user_role')->parent_name : 'Parent';

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

            $this->load->view('control/user_role/index',$data);
            
        }
        
        public function form($id='')
        {
            /*if id is not null then will show form edit*/
            if( $id != '' ){
                
                /*get value by id*/
                $data['value'] = $this->m_user_role->get_by_id($id);
                /*initialize flag for form*/
                $data['flag'] = "update";
            }else{
                /*initialize flag for form add*/
                $data['flag'] = "create";
            }
            /*title header*/
            $data['function'] = $this->db->get_where('tmp_mst_function',array('is_active' => 'Y'))->result();
            $data['menus'] = $this->lib_menus->get_master_menus($id);
           // print_r($data['function']);
            //print_r($data['menus']);die;

            $this->load->view('control/user_role/form', $data);
        }

        public function show($id)
        {
            /*breadcrumbs for view*/
           // $this->breadcrumbs->push('View '.$this->title.'', 'reference/mst_global_param/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$gp_id);
            /*define data variabel*/
            $data['value'] = $this->m_user_role->get_by_id($id);
          //  $data['title'] = $this->title;
            $data['flag'] = "read";
         //   $data['breadcrumbs'] = $this->breadcrumbs->show();
            /*load form view*/
           // $this->template->get_load();
           $data['function'] = $this->db->get_where('tmp_mst_function',array('is_active' => 'Y'))->result();
           $data['menus'] = $this->lib_menus->get_master_menus($id);

            $this->load->view('control/user_role/form', $data);
        }
    
       
        public function get_data()
        {
            /*get data from model*/
            $list = $this->m_user_role->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $row[] = '<div class="center"><label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->level_id.'"/>
                            <span class="lbl"></span>
                        </label></div>';
                $row[] = $row_list->level_name; 
                $row[] = $row_list->description;
                $row[] = '<div style="text-align: justify;margin-left:10px;">
                            '.$this->m_user_role->get_role_menu_by_level_id($row_list->level_id).'
                            </div>';
                $row[] = ($row_list->is_active == 'Y') ? '<div class="center"><span class="label label-sm label-success">Active</span></div>' : '<div class="center"><span class="label label-sm label-danger">Not active</span></div>';
                $row[] = '<div class="center">
                                '.$this->authuser->show_button('control/user_role','R',$row_list->level_id,2).'
                                '.$this->authuser->show_button('control/user_role','U',$row_list->level_id,1).'                              
                                '.$this->authuser->show_button('control/user_role','D',$row_list->level_id,2).'
                            </div>';
                                               
                $data[] = $row;
            }

            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->m_user_role->count_all(),
                            "recordsFiltered" => $this->m_user_role->count_filtered(),
                            "data" => $data,
                    );
            //output to json format
            echo json_encode($output);
        }

        public function process()
    {
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('id', 'Level', 'trim|required');
        $val->set_rules('level_name', 'Level Name', 'trim|required');
        $val->set_rules('description', 'Description', 'trim');
       
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
                'level_name' => $this->regex->_genRegex($val->set_value('level_name'),'RGXQSL'),
                'description' => $this->regex->_genRegex($val->set_value('description'),'RGXQSL'),
                'is_active' => $this->input->post('is_active'),
            );
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL')));
                /*save post data*/
                $id = $this->m_user_role->save($dataexc);
                //$this->logs->save('tmp_user', $newId, 'insert new record', json_encode($dataexc), 'user_id');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL')));
                /*update record*/
                $this->m_user_role->update(array('level_id' => $id), $dataexc);
            }

            $program_id = $this->input->post('program_id');
            //print_r($program_id);die;  
            if($program_id){
                /*drop menu role*/
                $this->db->delete('user_role', array('level_id' => $id));
                foreach ($program_id as $key => $val_program_id) {
                if($this->regex->_genRegex($this->input->post($val_program_id),'RGXINT')){
                        $get_menu_data = $this->db->get_where('app_program', array('program_id'=>$val_program_id))->row();
                        $get_parent_menu_data = $this->db->get_where('app_program', array('program_id'=>$get_menu_data->program_parent_id))->row();
                        /*if not main menu*/
                        if($get_menu_data->program_type == 'Menu'){
                            /*check main menu is exist*/
                            $main_menu = $this->db->query("SELECT * FROM user_role WHERE program_id= (SELECT program_parent_id FROM app_program WHERE program_id='".$val_program_id."' AND level_id=".$id.")")->row();
                            /*if empty main menu*/
                            if(empty($main_menu)){
                                /*then insert first main menu to role has menu*/
                                $data = array(
                                    'level_id' => $this->regex->_genRegex($id,'RGXINT'),
                                    'program_id' => $this->regex->_genRegex($get_menu_data->program_parent_id,'RGXQSL'),
                                    'role' => 'C,R,U,D',
                                );                   
                                $this->db->insert('user_role', $data);
                            }

                            $arr = $this->input->post($val_program_id)?$this->input->post($val_program_id):[];
                            if(count($arr) > 0){
                                $role = implode(',',$arr);
                                $data = array(
                                    'level_id' => $this->regex->_genRegex($id,'RGXINT'),
                                    'program_id' => $this->regex->_genRegex($val_program_id,'RGXQSL'),
                                    'role' => $this->regex->_genRegex($role,'RGXQSL'),
                                );         
                                $this->db->insert('user_role', $data);
                            }

                        }else if($get_menu_data->program_type == 'Submenu'){
                            /*check main menu is exist*/
                             $main_parent_menu = $this->db->query("SELECT * FROM user_role WHERE program_id= (SELECT program_parent_id FROM app_program WHERE program_id=(SELECT program_parent_id FROM app_program WHERE program_id='".$val_program_id."' AND level_id=".$id."))")->row();
                             $main_menu = $this->db->query("SELECT * FROM user_role WHERE program_id= (SELECT program_parent_id FROM app_program WHERE program_id='".$val_program_id."' AND level_id=".$id.")")->row();
                             /*if empty main parent menu*/
                             if(empty($main_parent_menu)){
                                 /*then insert first main menu to role has menu*/
                                 $data = array(
                                     'level_id' => $this->regex->_genRegex($id,'RGXINT'),
                                     'program_id' => $this->regex->_genRegex($get_parent_menu_data->program_parent_id,'RGXQSL'),
                                     'role' => 'C,R,U,D',
                                 );                   
                                 $this->db->insert('user_role', $data);
                             }

                            /*if empty main menu*/
                            if(empty($main_menu)){
                                /*then insert first main menu to role has menu*/
                                $data = array(
                                    'level_id' => $this->regex->_genRegex($id,'RGXINT'),
                                    'program_id' => $this->regex->_genRegex($get_menu_data->program_parent_id,'RGXQSL'),
                                    'role' => 'C,R,U,D',
                                );                   
                                $this->db->insert('user_role', $data);
                            }
 
                             $arr = $this->input->post($val_program_id)?$this->input->post($val_program_id):[];
                            
                             if(count($arr) > 0){
                                 $role = implode(',',$arr);
                                 $data = array(
                                     'level_id' => $this->regex->_genRegex($id,'RGXINT'),
                                     'program_id' => $this->regex->_genRegex($val_program_id,'RGXQSL'),
                                     'role' => $this->regex->_genRegex($role,'RGXQSL'),
                                 );                  
                                 $this->db->insert('user_role', $data);
                             }
                        }else{
                            $arr = $this->input->post($val_program_id)?$this->input->post($val_program_id):[];
                            if(count($arr) > 0){
                                $role = implode(',',$arr);
                                $data = array(
                                    'level_id' => $this->regex->_genRegex($id,'RGXINT'),
                                    'program_id' => $this->regex->_genRegex($val_program_id,'RGXQSL'),
                                    'role' => $this->regex->_genRegex($role,'RGXQSL'),
                                );                   
                                $this->db->insert('user_role', $data);
                            }
                        }
                    }                   
                }
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

    

    public function delete()
    {
        $user_id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        $toArray = explode(',',$user_id);
        if($user_id!=null){
            if($this->m_user_role->delete_by_id($toArray)){
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