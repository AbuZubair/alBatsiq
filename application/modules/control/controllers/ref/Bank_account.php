
<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Bank_account extends MX_Controller {

        public function __construct()
        {
            parent::__construct();
            //Load Dependencies
            $this->load->model('M_bank_account','m_bank'); 

            $this->load->library('bcrypt');

            $this->title = ($this->lib_menus->get_menu_by_link('control/ref/Bank_account'))?$this->lib_menus->get_menu_by_link('control/ref/Bank_account')->program_name : 'Title';
            $this->level = ($this->lib_menus->get_menu_by_link('control/ref/Bank_account'))?$this->lib_menus->get_menu_by_link('control/ref/Bank_account')->level_program : 'Level';
            $this->parent = ($this->lib_menus->get_menu_by_link('control/ref/Bank_account'))?$this->lib_menus->get_menu_by_link('control/ref/Bank_account')->parent_name : 'Parent';

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

            $this->load->view('control/ref/bank_account/index',$data);
            
        }
        
        public function form($id='')
        {
            /*if id is not null then will show form edit*/
            if( $id != '' ){
                
                /*get value by id*/
                $data['value'] = $this->m_bank->get_by_id($id);
                /*initialize flag for form*/
                $data['flag'] = "update";
            }else{
                /*initialize flag for form add*/
                $data['flag'] = "create";
            }
            /*title header*/         

            $this->load->view('control/ref/bank_account/form', $data);
        }

        public function show($id)
        {
            /*breadcrumbs for view*/
           // $this->breadcrumbs->push('View '.$this->title.'', 'reference/mst_global_param/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$gp_id);
            /*define data variabel*/
            $data['value'] = $this->m_bank->get_by_id($id);
          //  $data['title'] = $this->title;
            $data['flag'] = "read";
 
            $this->load->view('control/ref/bank_account/form', $data);
        }
    
       
        public function get_data()
        {
            /*get data from model*/
            $list = $this->m_bank->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $row[] = '<div class="center"><label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->ba_id.'"/>
                            <span class="lbl"></span>
                        </label></div>';
                $row[] = $row_list->bank_name; 
                $row[] = $row_list->bank_number; 
                $row[] = ($row_list->is_active == 'Y') ? '<div class="center"><span class="label label-sm label-success">Active</span></div>' : '<div class="center"><span class="label label-sm label-danger">Not active</span></div>';
                $row[] = '<div class="center">
                                '.$this->authuser->show_button('control/ref/bank_account','R',$row_list->ba_id,2).'
                                '.$this->authuser->show_button('control/ref/bank_account','U',$row_list->ba_id,1).'
                                '.$this->authuser->show_button('control/ref/bank_account','D',$row_list->ba_id,2).'
                                
                            </div>';
                                               
                $data[] = $row;
            }

            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->m_bank->count_all(),
                            "recordsFiltered" => $this->m_bank->count_filtered(),
                            "data" => $data,
                    );
            //output to json format
            echo json_encode($output);
        }

        public function process()
    {
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('bank_name', 'Bank Name', 'trim|required');
        $val->set_rules('bank_number', 'Number', 'trim');
               
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
                'bank_name' => $this->regex->_genRegex($val->set_value('satuan_name'),'RGXQSL'),
                'bank_number' => $this->regex->_genRegex($val->set_value('bank_number'),'RGXQSL'),
                'is_active' => $this->input->post('is_active'),
            );
            //print_r($id);echo"<pre>";print_r($dataexc);die;
            if($id == 0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL')));
                /*save post data*/
                $this->m_bank->save($dataexc);
                //$this->logs->save('tmp_user', $newId, 'insert new record', json_encode($dataexc), 'ba_id');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL')));
                /*update record*/
                $this->m_bank->update(array('ba_id' => $id), $dataexc);
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
        $ba_id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        $toArray = explode(',',$ba_id);
        if($ba_id!=null){
            if($this->m_bank->delete_by_id($toArray)){
                //$this->logs->save('tmp_mst_global_parameter', $ba_id, 'delete record', '', 'ba_id');
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