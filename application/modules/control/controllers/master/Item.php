<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Item extends MX_Controller {

        public function __construct()
        {
            parent::__construct();
            //Load Dependencies
            $this->load->model('m_item','m_item'); 
            $this->load->model('m_satuan','m_satuan'); 
            $this->load->model('m_group_item','m_group_item'); 

            $this->load->library('bcrypt');

            $this->title = ($this->lib_menus->get_menu_by_link('control/master/item'))?$this->lib_menus->get_menu_by_link('control/master/item')->program_name : 'Title';
            $this->level = ($this->lib_menus->get_menu_by_link('control/master/item'))?$this->lib_menus->get_menu_by_link('control/master/item')->level_program : 'Level';
            $this->parent = ($this->lib_menus->get_menu_by_link('control/master/item'))?$this->lib_menus->get_menu_by_link('control/master/item')->parent_name : 'Parent';

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

            $this->load->view('control/master/item/index',$data);
            
        }
        
        public function form($id='')
        {
            /*if id is not null then will show form edit*/
            if( $id != '' ){
                
                /*get value by id*/
                $data['value'] = $this->m_item->get_by_id($id);
                /*initialize flag for form*/
                $data['flag'] = "update";
            }else{
                /*initialize flag for form add*/
                $data['flag'] = "create";
            }
            /*title header*/  
            $data['group'] = $this->m_group_item->get_all_group();
            $data['satuan'] = $this->m_satuan->get_all_satuan();       

            $this->load->view('control/master/item/form', $data);
        }

        public function show($id)
        {
            /*breadcrumbs for view*/
           // $this->breadcrumbs->push('View '.$this->title.'', 'reference/mst_global_param/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$gp_id);
            /*define data variabel*/
            $data['value'] = $this->m_item->get_by_id($id);
          //  $data['title'] = $this->title;
            $data['flag'] = "read";
            $data['group'] = $this->m_group_item->get_all_group();
            $data['satuan'] = $this->m_satuan->get_all_satuan();  
 
            $this->load->view('control/master/item/form', $data);
        }
    
       
        public function get_data()
        {
            /*get data from model*/
            $list = $this->m_item->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $row[] = '<div class="center"><label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->item_id.'"/>
                            <span class="lbl"></span>
                        </label></div>';
                $row[] = $row_list->item_id; 
                $row[] = $row_list->item_name; 
                $row[] = $row_list->group_name; 
                $row[] = $row_list->satuan_beli; 
                $row[] = $row_list->satuan_jual; 
                $row[] = ($row_list->is_active == 'Y') ? '<div class="center"><span class="label label-sm label-success">Active</span></div>' : '<div class="center"><span class="label label-sm label-danger">Not active</span></div>';
                $row[] = '<div class="center">
                                '.$this->authuser->show_button('control/master/item','R',$row_list->item_id,2).'
                                '.$this->authuser->show_button('control/master/item','U',$row_list->item_id,1).'
                                '.$this->authuser->show_button('control/master/item','D',"'$row_list->item_id'",2).'
                            </div>';
                                               
                $data[] = $row;
            }

            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->m_item->count_all(),
                            "recordsFiltered" => $this->m_item->count_filtered(),
                            "data" => $data,
                    );
            //output to json format
            echo json_encode($output);
        }

        public function process($flag)
    {
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('id', 'Item ID', 'trim|required');
        $val->set_rules('item_name', 'Item Name', 'trim|required');
        $val->set_rules('group_item_id', 'Item Group', 'trim|required');
        $val->set_rules('satuan_beli_id', 'Satuan Beli', 'trim|required');
        $val->set_rules('satuan_jual_id', 'Satuan Jual', 'trim|required');
        $val->set_rules('konversi', 'Konversi', 'trim|required');
       
        $val->set_message('required', "Silahkan isi field \"%s\"");
      
        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            $id = $this->regex->_genRegex($this->input->post('id'),'RGXQSL');
            $dataexc = array(
                'item_id' => $this->regex->_genRegex($val->set_value('id'),'RGXQSL'),
                'item_name' => $this->regex->_genRegex($val->set_value('item_name'),'RGXQSL'),
                'group_item_id' => $this->regex->_genRegex($val->set_value('group_item_id'),'RGXQSL'),
                'satuan_beli_id' => $this->regex->_genRegex($val->set_value('satuan_beli_id'),'RGXQSL'),
                'satuan_jual_id' => $this->regex->_genRegex($val->set_value('satuan_jual_id'),'RGXQSL'),
                'konversi' => $this->regex->_genRegex($val->set_value('konversi'),'RGXINT'),
                'sales_available' => $this->input->post('sales_available'),
                'is_active' => $this->input->post('is_active'),
            );
            //echo"<pre>";print_r($dataexc);die;
            if($flag=='create'){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL')));
                /*save post data*/
                $this->m_item->save($dataexc);
                //$this->logs->save('tmp_user', $newId, 'insert new record', json_encode($dataexc), 'item_id');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL')));
                /*update record*/
                //echo"<pre>";print_r($dataexc);die;
                $this->m_item->update(array('item_id' => $id), $dataexc);
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
        $item_id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        $toArray = explode(',',$item_id);
        if($item_id!=null){
            if($this->m_item->delete_by_id($toArray)){
                //$this->logs->save('tmp_mst_global_parameter', $item_id, 'delete record', '', 'item_id');
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