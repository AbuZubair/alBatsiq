<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Tariff_item extends MX_Controller {

        public function __construct()
        {
            parent::__construct();
            //Load Dependencies
            $this->load->model('m_tariff_item','m_tariff_item'); 
            $this->load->model('m_satuan','m_satuan'); 
            $this->load->model('m_group_item','m_group_item');
        
            $this->load->library('bcrypt');

            $this->title = ($this->lib_menus->get_menu_by_link('control/master/tariff_item'))?$this->lib_menus->get_menu_by_link('control/master/tariff_item')->program_name : 'Title';
            $this->level = ($this->lib_menus->get_menu_by_link('control/master/tariff_item'))?$this->lib_menus->get_menu_by_link('control/master/tariff_item')->level_program : 'Level';
            $this->parent = ($this->lib_menus->get_menu_by_link('control/master/tariff_item'))?$this->lib_menus->get_menu_by_link('control/master/tariff_item')->parent_name : 'Parent';

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

            $this->load->view('control/master/tariff_item/index',$data);
            
        }
        
        public function form($id='')
        {
            /*if id is not null then will show form edit*/
            if( $id != '' ){
                
                /*get value by id*/
                $data['value'] = $this->m_tariff_item->get_by_id($id);
                /*initialize flag for form*/
                $data['flag'] = "update";
            }else{
                /*initialize flag for form add*/
                $data['flag'] = "create";
            }
            /*title header*/  
            $data['item'] = $this->m_tariff_item->get_item();  

            $this->load->view('control/master/tariff_item/form', $data);
        }

        public function show($id)
        {
            /*breadcrumbs for view*/
           // $this->breadcrumbs->push('View '.$this->title.'', 'reference/mst_global_param/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$gp_id);
            /*define data variabel*/
            $data['value'] = $this->m_tariff_item->get_by_id($id);
          //  $data['title'] = $this->title;
            $data['flag'] = "read";
            $data['group'] = $this->m_group_item->get_all_group();
            $data['satuan'] = $this->m_satuan->get_all_satuan(); 
             
            $this->load->view('control/master/tariff_item/form', $data);
        }
    
       
        public function get_data()
        {
            /*get data from model*/
            $list = $this->m_tariff_item->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $row[] = '<div class="center"><label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->tariff_id.'"/>
                            <span class="lbl"></span>
                        </label></div>';
                $row[] = $row_list->item_id; 
                $row[] = $row_list->item_name; 
                $row[] = $row_list->harga_jual; 
                $row[] = '<div class="center">
                                '.date("d-m-Y", strtotime($row_list->created_date)).'
                            </div>';
                $row[] = '<div class="center">
                            '.$row_list->created_by.'
                            </div>';
                $row[] = '<div class="center">
                                '.$this->authuser->show_button('control/master/tariff_item','R',$row_list->tariff_id,2).'
                            </div>';
                                               
                $data[] = $row;
            }

            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->m_tariff_item->count_all(),
                            "recordsFiltered" => $this->m_tariff_item->count_filtered(),
                            "data" => $data,
                    );
            //output to json format
            echo json_encode($output);
        }

        public function process()
    {
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('item', 'Item', 'trim|required');
        $val->set_rules('harga_jual', 'Harga Jual', 'trim|required');
        $val->set_rules('tgl_item_tarif', 'Tanggal', 'trim');
              
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
                'item_id' => $this->regex->_genRegex($val->set_value('item'),'RGXQSL'),
                'harga_jual' => $this->regex->_genRegex($val->set_value('harga_jual'),'RGXINT'),
            );
            //echo"<pre>";print_r($dataexc);die;
           
            $dataexc['created_date'] = date('Y-m-d H:i:s');
            $dataexc['created_by'] = json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL')));
            /*save post data*/
            $this->m_tariff_item->save($dataexc);

            /*update item*/
            $this->db->update('item',array('harga_jual' => $this->regex->_genRegex($val->set_value('harga_jual'),'RGXINT')),array('item_id' => $this->regex->_genRegex($val->set_value('item'),'RGXQSL')));
            //$this->logs->save('tmp_user', $newId, 'insert new record', json_encode($dataexc), 'item_id');
            
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
            if($this->m_tariff_item->delete_by_id($toArray)){
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