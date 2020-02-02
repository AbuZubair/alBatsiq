<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Stock_card extends MX_Controller {

        public function __construct()
        {
            parent::__construct();
            //Load Dependencies
            $this->load->model('m_stock_card','m_stock_card'); 
            $this->load->model('control/m_satuan','m_satuan'); 
            $this->load->model('control/m_group_item','m_group_item');
        
            $this->load->library('bcrypt');

            $this->title = ($this->lib_menus->get_menu_by_link('inventory/stock/stock_card'))?$this->lib_menus->get_menu_by_link('inventory/stock/stock_card')->program_name : 'Title';
            $this->level = ($this->lib_menus->get_menu_by_link('inventory/stock/stock_card'))?$this->lib_menus->get_menu_by_link('inventory/stock/stock_card')->level_program : 'Level';
            $this->parent = ($this->lib_menus->get_menu_by_link('inventory/stock/stock_card'))?$this->lib_menus->get_menu_by_link('inventory/stock/stock_card')->parent_name : 'Parent';

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

            $this->load->view('inventory/stock/stock_card/index',$data);
            
        }
        
            
        public function getData()
        {
            # code...
            $item = $this->input->get('item');

            $loc = $this->input->get('loc');
            
            $data = $this->m_stock_card->get_data($item,$loc);

           // print_r($data);die;

            echo json_encode($data);
        }

        
        public function export()
        {
           
            $item = $this->input->get('item');

            $loc = $this->input->get('loc');
            
            $data_stock = $this->m_stock_card->get_data($item,$loc);

            $data = array();

            $data['result']=$data_stock;

            $this->load->view('inventory/stock/stock_card/export', $data);
                
        }
       

    public function delete()
    {
        $item_id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        $toArray = explode(',',$item_id);
        //print_r($item_id);print_r($toArray);die;
        if($item_id!=null){
            if($this->m_stock_card->delete_by_id($toArray)){
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