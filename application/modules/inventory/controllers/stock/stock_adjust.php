<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Stock_adjust extends MX_Controller {

        public function __construct()
        {
            parent::__construct();
            //Load Dependencies
            $this->load->model('m_stock_information','m_stock_information'); 
            $this->load->model('control/m_satuan','m_satuan'); 
            $this->load->model('control/m_group_item','m_group_item');
        
            $this->load->library('bcrypt');

            $this->title = ($this->lib_menus->get_menu_by_link('inventory/stock/stock_adjust'))?$this->lib_menus->get_menu_by_link('inventory/stock/stock_adjust')->program_name : 'Title';
            $this->level = ($this->lib_menus->get_menu_by_link('inventory/stock/stock_adjust'))?$this->lib_menus->get_menu_by_link('inventory/stock/stock_adjust')->level_program : 'Level';
            $this->parent = ($this->lib_menus->get_menu_by_link('inventory/stock/stock_adjust'))?$this->lib_menus->get_menu_by_link('inventory/stock/stock_adjust')->parent_name : 'Parent';

            if (!$this->session->userdata['logged'])
            {
             redirect('login/logout');
            }

            $this->load->library('form_validation');
            
        }
    
        public function index()
        {
            $data = array(
                'title' => $this->title,
                'level' => $this->level,
                'parent' => $this->parent,
            );

            $this->load->view('inventory/stock/stock_adjust/index',$data);
            
        }
        
            
        public function getData($item='',$loc='')
        {
            # code...
            $data = $this->m_stock_information->get_data($item,$loc);

            echo json_encode($data);
        }

        public function process()
        {
            $this->form_validation->set_rules('stock_adjust', 'Adjust', 'trim|required');
                
             // set message error
             $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");        
    
             if ($this->form_validation->run() == FALSE)
             {
                 $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
                 //die(validation_errors());
                 echo json_encode(array('status' => 301, 'message' => validation_errors()));
             }
             else
             {            
    
                $this->db->trans_begin();
                                                             
                $this->db->update('stock_item', array('balance' => $this->regex->_genRegex($this->input->post('stock_adjust'),'RGXINT'), 'date' => date("Y-m-d")),array('loc_id' => $this->input->post('locHidden'), 'item_id' => $this->input->post('itemHidden')));                 

                $data_sc = array(
                    'loc_id' =>$this->input->post('locHidden'),
                    'item_id' => $this->input->post('itemHidden'),
                    'transaction_date' => date("Y-m-d"),
                    'transaction_code' => 999,
                    'transaction_no' => 'Stock Adjustment',
                    'stock_in' => 0,
                    'stock_out' => 0, 
                    'stock_before' => $this->input->post('balHidden'),
                    'stock_balance' => $this->input->post('stock_adjust'),
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => $data['created_by'] = json_encode(array('username' => $this->session->userdata('username'), 'nama' => $this->session->userdata('nama'))),
                );

                $this->db->insert('stock_card', $data_sc);
                
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
        //print_r($item_id);print_r($toArray);die;
        if($item_id!=null){
            if($this->m_stock_information->delete_by_id($toArray)){
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