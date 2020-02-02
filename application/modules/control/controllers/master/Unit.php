<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Unit extends MX_Controller {

        public function __construct()
        {
            parent::__construct();
            //Load Dependencies
            $this->load->model('m_unit','m_unit'); 

            $this->load->library('bcrypt');

            $this->title = ($this->lib_menus->get_menu_by_link('control/master/unit'))?$this->lib_menus->get_menu_by_link('control/master/unit')->program_name : 'Title';
            $this->level = ($this->lib_menus->get_menu_by_link('control/master/unit'))?$this->lib_menus->get_menu_by_link('control/master/unit')->level_program : 'Level';
            $this->parent = ($this->lib_menus->get_menu_by_link('control/master/unit'))?$this->lib_menus->get_menu_by_link('control/master/unit')->parent_name : 'Parent';

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

            $this->load->view('control/master/unit/index',$data);
            
        }
        
        public function form($id='')
        {
            /*if id is not null then will show form edit*/
            if( $id != '' ){
                
                /*get value by id*/
                $data['value'] = $this->m_unit->get_by_id($id);
                /*initialize flag for form*/
                $data['flag'] = "update";
            }else{
                /*initialize flag for form add*/
                $data['flag'] = "create";
            }
            /*title header*/         

            $this->load->view('control/master/unit/form', $data);
        }

        public function show($id)
        {
            /*breadcrumbs for view*/
           // $this->breadcrumbs->push('View '.$this->title.'', 'reference/mst_global_param/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$gp_id);
            /*define data variabel*/
            $data['value'] = $this->m_unit->get_by_id($id);
          //  $data['title'] = $this->title;
            $data['flag'] = "read";
 
            $this->load->view('control/master/unit/form', $data);
        }
    
       
        public function get_data()
        {
            /*get data from model*/
            $list = $this->m_unit->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $row[] = '<div class="center"><label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->unit_id.'"/>
                            <span class="lbl"></span>
                        </label></div>';
                $row[] = $row_list->unit_id; 
                $row[] = $row_list->unit_name; 
                $row[] = $row_list->loc_name; 
                $row[] = ($row_list->is_active == 'Y') ? '<div class="center"><span class="label label-sm label-success">Active</span></div>' : '<div class="center"><span class="label label-sm label-danger">Not active</span></div>';
                $row[] = '<div class="center">
                                '.$this->authuser->show_button('control/master/unit','R',$row_list->unit_id,2).'
                                '.$this->authuser->show_button('control/master/unit','U',$row_list->unit_id,1).'
                                '.$this->authuser->show_button('control/master/unit','D',$row_list->unit_id,2).'
                                
                            </div>';
                                               
                $data[] = $row;
            }

            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->m_unit->count_all(),
                            "recordsFiltered" => $this->m_unit->count_filtered(),
                            "data" => $data,
                    );
            //output to json format
            echo json_encode($output);
        }

        public function process()
    {
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('unit_name', 'Unit Name', 'trim|required');
        $val->set_rules('loc_id', 'Location ID', 'trim|required');
        $val->set_rules('loc_name', 'Location Name', 'trim|required');
       
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
                'unit_name' => $this->regex->_genRegex($val->set_value('unit_name'),'RGXQSL'),
                // 'loc_id' => $this->regex->_genRegex($val->set_value('loc_id'),'RGXQSL'),
                'loc_name' => $this->regex->_genRegex($val->set_value('loc_name'),'RGXQSL'),
                'is_active' => $this->input->post('is_active'),
            );
            //print_r($id);echo"<pre>";print_r($dataexc);die;
            if($id == 0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL')));
                /*save post data*/
                $this->m_unit->save($dataexc);
                //$this->logs->save('tmp_user', $newId, 'insert new record', json_encode($dataexc), 'unit_id');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL')));
                /*update record*/
                $this->m_unit->update(array('unit_id' => $id), $dataexc);
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
        $unit_id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        $toArray = explode(',',$unit_id);
        if($unit_id!=null){
            if($this->m_unit->delete_by_id($toArray)){
                //$this->logs->save('tmp_mst_global_parameter', $unit_id, 'delete record', '', 'unit_id');
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