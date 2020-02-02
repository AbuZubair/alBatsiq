<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Functions extends MX_Controller {

        public function __construct()
        {
            parent::__construct();
            //Load Dependencies
            $this->load->model('m_function','m_function'); 

            $this->load->library('bcrypt');

            $this->title = ($this->lib_menus->get_menu_by_link('control/ref/functions'))?$this->lib_menus->get_menu_by_link('control/ref/functions')->program_name : 'Title';
            $this->level = ($this->lib_menus->get_menu_by_link('control/ref/functions'))?$this->lib_menus->get_menu_by_link('control/ref/functions')->level_program : 'Level';
            $this->parent = ($this->lib_menus->get_menu_by_link('control/ref/functions'))?$this->lib_menus->get_menu_by_link('control/ref/functions')->parent_name : 'Parent';

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

            //echo"<pre>";print_r($data);die;
            $this->load->view('control/ref/functions/index',$data);
            
        }
        
        public function form($id='')
        {
            /*if id is not null then will show form edit*/
            if( $id != '' ){
                
                /*get value by id*/
                $data['value'] = $this->m_function->get_by_id($id);
                /*initialize flag for form*/
                $data['flag'] = "update";
            }else{
                /*initialize flag for form add*/
                $data['flag'] = "create";
            }
            /*title header*/         

            $this->load->view('control/ref/functions/form', $data);
        }

        public function show($id)
        {
            /*breadcrumbs for view*/
           // $this->breadcrumbs->push('View '.$this->title.'', 'reference/mst_global_param/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$gp_id);
            /*define data variabel*/
            $data['value'] = $this->m_function->get_by_id($id);
          //  $data['title'] = $this->title;
            $data['flag'] = "read";
 
            $this->load->view('control/ref/functions/form', $data);
        }
    
       
        public function get_data()
        {
            /*get data from model*/
            $list = $this->m_function->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $row[] = '<div class="center"><label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->function_id.'"/>
                            <span class="lbl"></span>
                        </label></div>';
                $row[] = strtoupper($row_list->code); 
                $row[] = strtoupper($row_list->name);
                $row[] = $row_list->description; 
                $row[] = ($row_list->is_active == 'Y') ? '<div class="center"><span class="label label-sm label-success">Active</span></div>' : '<div class="center"><span class="label label-sm label-danger">Not active</span></div>';
                $row[] = '<div class="center">
                                '.$this->authuser->show_button('control/ref/functions','R',$row_list->function_id,2).'
                                '.$this->authuser->show_button('control/ref/functions','U',$row_list->function_id,1).'
                                '.$this->authuser->show_button('control/ref/functions','D',$row_list->function_id,2).'
                                
                            </div>';
                                               
                $data[] = $row;
            }

            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->m_function->count_all(),
                            "recordsFiltered" => $this->m_function->count_filtered(),
                            "data" => $data,
                    );
            //output to json format
            echo json_encode($output);
        }

        public function process()
    {
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('code', 'Code', 'trim|required');
        $val->set_rules('name', 'Name', 'trim|required');
        $val->set_rules('description', 'Description', 'trim');
               
        $val->set_message('required', "Silahkan isi field \"%s\"");
      
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
                'code' => $this->regex->_genRegex($val->set_value('code'),'RGXQSL'),
                'name' => $this->regex->_genRegex($val->set_value('name'),'RGXQSL'),
                'description' => $this->regex->_genRegex($val->set_value('description'),'RGXQSL'),
                'is_active' => $this->input->post('is_active'),
            );
            //print_r($id);echo"<pre>";print_r($dataexc);die;
            if($id == 0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL')));
                /*save post data*/
                $this->m_function->save($dataexc);
                //$this->logs->save('tmp_user', $newId, 'insert new record', json_encode($dataexc), 'function_id');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL')));
                /*update record*/
                $this->m_function->update(array('function_id' => $id), $dataexc);
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
        $function_id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        $toArray = explode(',',$function_id);
        if($function_id!=null){
            if($this->m_function->delete_by_id($toArray)){
                //$this->logs->save('tmp_mst_global_parameter', $function_id, 'delete record', '', 'function_id');
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