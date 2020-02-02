<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Distribusi extends MX_Controller {

        public function __construct()
        {
            parent::__construct();
            //Load Dependencies
            $this->load->model('m_distribusi','m_distribusi'); 
            $this->load->model('control/m_satuan','m_satuan'); 
            $this->load->model('control/m_group_item','m_group_item');
        
            $this->load->library('bcrypt');

            $this->title = ($this->lib_menus->get_menu_by_link('inventory/distribusi'))?$this->lib_menus->get_menu_by_link('inventory/distribusi')->program_name : 'Title';
            $this->level = ($this->lib_menus->get_menu_by_link('inventory/distribusi'))?$this->lib_menus->get_menu_by_link('inventory/distribusi')->level_program : 'Level';
            $this->parent = ($this->lib_menus->get_menu_by_link('inventory/distribusi'))?$this->lib_menus->get_menu_by_link('inventory/distribusi')->parent_name : 'Parent';

            if (!$this->session->userdata['logged'])
            {
             redirect('login/logout');
            }

            $this->load->library('form_validation');
            $this->load->library('stock');
            
        }
    
        public function index()
        {
            $data = array(
                'title' => $this->title,
                'level' => $this->level,
                'parent' => $this->parent,
            );

            $this->load->view('inventory/distribusi/index',$data);
            
        }
        
        public function form($id='')
        {
            /*if id is not null then will show form edit*/
            if( $id != '' ){
                
                /*get value by id*/
                $data['value'] = $this->m_distribusi->get_by_id($id);
                $data['value_detail'] = $this->m_distribusi->get_detail_by_id($id);
                $data['value_numrow'] = $this->m_distribusi->get_numrow_detail_by_id($id);
                /*initialize flag for form*/
                $data['flag'] = "update";
            }else{
                /*initialize flag for form add*/
                $data['flag'] = "create";
                $data['value_numrow'] = 0;
                $data['trans_no'] = $this->master->getMaxTransNo('warehouse_transaction',array('transaction_code' => '100', 'YEAR(transaction_date)' => date('Y'), 'MONTH(transaction_date)' => date('m')),'DST');
              
            }
            /*title header*/  
            
            $this->load->view('inventory/distribusi/form', $data);
        }

        public function show($id)
        {
            /*breadcrumbs for view*/
           // $this->breadcrumbs->push('View '.$this->title.'', 'reference/mst_global_param/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$gp_id);
            /*define data variabel*/
            $data['value'] = $this->m_distribusi->get_by_id($id);
            $data['value_detail'] = $this->m_distribusi->get_detail_by_id($id);
          //  $data['title'] = $this->title;
            $data['flag'] = "read";
           
            //print_r($data);die; 
            $this->load->view('inventory/distribusi/form', $data);
        }
    
       
        public function get_data()
        {
            /*get data from model*/
            $list = $this->m_distribusi->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $row[] = '<div class="center"><label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->transaction_no.'"/>
                            <span class="lbl"></span>
                        </label></div>';
                $row[] = $row_list->transaction_no; 
                $row[] = $row_list->transaction_date; 
                $row[] = $row_list->note; 
                $row[] = '<div class="center">
                                '.date("d-m-Y", strtotime($row_list->created_date)).'
                            </div>';
                $row[] = '<div class="center">
                            '.$row_list->created_by.'
                            </div>';
                $row[] = '<div class="center">
                                '.$this->authuser->show_button('inventory/distribusi','R',$row_list->transaction_no,2).'
                                
                            </div>';
                                               
                $data[] = $row;
            }

            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->m_distribusi->count_all(),
                            "recordsFiltered" => $this->m_distribusi->count_filtered(),
                            "data" => $data,
                    );
            //output to json format
            echo json_encode($output);
        }

        public function process()
    {
        $this->form_validation->set_rules('insertlocunit', 'From Unit', 'trim|required');
        $this->form_validation->set_rules('insertTolocunit', 'To Unit', 'trim|required');
        //$this->form_validation->set_rules('jmlcell', 'Purchase Order No', 'trim|required');

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
        
            $sum = 0;

            for($i=0;$i<=$_POST['jmlcell'];$i++){
                
                $index1="itemID".$i;
                if(isset($_POST[$index1])){
                    $itemID = explode(" ",$_POST[$index1]);	
                    $itemIDs[$i] = $itemID[0];		
                    $check[$i]=!empty($itemIDs[$i]);
                }
                            
                $index2="qty".$i;
                if(isset($_POST[$index2]))$qty[$i]=$_POST[$index2];
                
                $index3="satuan".$i;
                if(isset($_POST[$index3]))$satuan[$i]=$_POST[$index3];
                
                               
            }	
                                        
            $id = ($this->input->post('id'))?$this->regex->_genRegex($this->input->post('id'),'RGXINT'):0;

            //print_r($_POST['jmlcell']);die;
            
            $dataexc = array(
                'transaction_no' => $this->regex->_genRegex($this->input->post('distnumber'),'RGXQSL'),
                'transaction_code' => '100',
                'transaction_date' => date("Y-m-d"),
                'note' => $this->regex->_genRegex($this->input->post('insertNote'),'RGXQSL'),
            );
            
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL')));                     

                for($i=0;$i<=$_POST['jmlcell'];$i++)
                {
                    
                    if(isset($itemIDs[$i])){    
                        $datadetail = array(
                            'transaction_no' => $dataexc['transaction_no'],
                            'item_id' => $itemIDs[$i],
                            'quantity' => $qty[$i],
                            'satuan' => $satuan[$i],
                        );
                        /*save transaksi item detail*/
                        $this->m_distribusi->save('warehouse_transaction_detail',$datadetail);

                        /* update balance item to unit*/
                        if (isset($_POST['insertlocunit'])){

                            $fetchbalanceFrom = $this->db->get_where('stock_item', array('loc_id' => $this->input->post('insertlocunit'), 'item_id' => $itemIDs[$i]))->row();
                            $cekbalance = $this->db->get_where('stock_item', array('loc_id' => $this->input->post('insertTolocunit'), 'item_id' => $itemIDs[$i]));
                            $num = $cekbalance->num_rows();
                            $fetchbalance = $cekbalance->row();
                            //print_r($this->db->last_query());print_r($num);die;
                            $data_balance_from = array(
                                'loc_id' => $this->input->post('insertlocunit'),
                                'item_id' => $itemIDs[$i],
                                'balance' => $qty[$i],
                                'satuan' => $satuan[$i],
                                'date' => date("Y-m-d"),
                                'updated_date' => date('Y-m-d H:i:s'),
                                'updated_by' => json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL'))),
                            );

                            $data_balance_to = array(
                                'loc_id' => $this->input->post('insertTolocunit'),
                                'item_id' => $itemIDs[$i],
                                'balance' => $qty[$i],
                                'satuan' => $satuan[$i],
                                'date' => date("Y-m-d"),
                                'updated_date' => date('Y-m-d H:i:s'),
                                'updated_by' => json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL'))),
                            );

                            /*update unit from */
                            $this->stock->update_stock($data_balance_from, $fetchbalanceFrom,'100',$dataexc['transaction_no'],'remove',$this->input->post('insertTolocunit'));

                            if($num == 0){
                               
                                /*save balance item*/
                                $this->stock->save_stock($data_balance_to,'100',$dataexc['transaction_no'],$this->input->post('insertlocunit'));

                            }else{
                                
                                $this->stock->update_stock($data_balance_to, $fetchbalance,'100',$dataexc['transaction_no'],'add',$this->input->post('insertlocunit'));
                               
                            }	
                        }

                    }    

                }      

                $dataexc['to_loc_id'] = $this->input->post('insertTolocunit');
                $dataexc['from_loc_id'] = $this->input->post('insertlocunit');
                
                /*save transaksi item*/
                $this->m_distribusi->save('warehouse_transaction',$dataexc);
               
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
            if($this->m_distribusi->delete_by_id($toArray)){
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