<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Purchase_order extends MX_Controller {

        public function __construct()
        {
            parent::__construct();
            //Load Dependencies
            $this->load->model('m_purchase_order','m_purchase_order'); 
            $this->load->model('control/m_satuan','m_satuan'); 
            $this->load->model('control/m_group_item','m_group_item');
        
            $this->load->library('bcrypt');

            $this->title = ($this->lib_menus->get_menu_by_link('inventory/transaksi_item/purchase_order'))?$this->lib_menus->get_menu_by_link('inventory/transaksi_item/purchase_order')->program_name : 'Title';
            $this->level = ($this->lib_menus->get_menu_by_link('inventory/transaksi_item/purchase_order'))?$this->lib_menus->get_menu_by_link('inventory/transaksi_item/purchase_order')->level_program : 'Level';
            $this->parent = ($this->lib_menus->get_menu_by_link('inventory/transaksi_item/purchase_order'))?$this->lib_menus->get_menu_by_link('inventory/transaksi_item/purchase_order')->parent_name : 'Parent';

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

            $this->load->view('inventory/transaksi_item/purchase_order/index',$data);
            
        }
        
        public function form($id='')
        {
            /*if id is not null then will show form edit*/
            if( $id != '' ){
                
                /*get value by id*/
                $data['value'] = $this->m_purchase_order->get_by_id($id);
                $data['value_detail'] = $this->m_purchase_order->get_detail_by_id($id);
                $data['value_numrow'] = $this->m_purchase_order->get_numrow_detail_by_id($id);
                /*initialize flag for form*/
                $data['flag'] = "update";
            }else{
                /*initialize flag for form add*/
                $data['flag'] = "create";
                $data['value_numrow'] = 0;
                $data['trans_no'] = $this->master->getMaxTransNo('item_transaction',array('transaction_code' => '001', 'YEAR(transaction_date)' => date('Y'), 'MONTH(transaction_date)' => date('m')),'PO');
              
            }
            /*title header*/  
            $this->load->view('inventory/transaksi_item/purchase_order/form', $data);
        }

        public function show($id)
        {
            /*breadcrumbs for view*/
           // $this->breadcrumbs->push('View '.$this->title.'', 'reference/mst_global_param/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$gp_id);
            /*define data variabel*/
            $data['value'] = $this->m_purchase_order->get_by_id($id);
            $data['value_detail'] = $this->m_purchase_order->get_detail_by_id($id);

          //  $data['title'] = $this->title;
            $data['flag'] = "read";
           
            //print_r($data);die; 
            $this->load->view('inventory/transaksi_item/purchase_order/form', $data);
        }
    
       
        public function get_data()
        {
            /*get data from model*/
            $list = $this->m_purchase_order->get_datatables();
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
                                '.date("Y-m-d", strtotime($row_list->created_date)).'
                            </div>';
                $row[] = '<div class="center">
                            '.$row_list->created_by.'
                            </div>';

                $cekpo = $this->m_purchase_order->get_po($row_list->transaction_no);
                if($cekpo==0){
                    $row[] = '<div class="center">
                                '.$this->authuser->show_button('inventory/transaksi_item/purchase_order','R',$row_list->transaction_no,2).'
                                <button class="btn btn-xs btn-success" disabled><i class="ace-icon fa fa-pencil bigger-50"></i>Edit</button>
                            </div>';
                }else{
                    $row[] = '<div class="center">
                                '.$this->authuser->show_button('inventory/transaksi_item/purchase_order','R',$row_list->transaction_no,2).'
                                '.$this->authuser->show_button('inventory/transaksi_item/purchase_order','U',$row_list->transaction_no,1).'
                            </div>';
                }
                
                                               
                $data[] = $row;
            }

            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->m_purchase_order->count_all(),
                            "recordsFiltered" => $this->m_purchase_order->count_filtered(),
                            "data" => $data,
                    );
            //output to json format
            echo json_encode($output);
        }

        public function process()
    {

        $this->form_validation->set_rules('jmlcell', 'Item_ID', 'trim|required');

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
                
                $index4="knv".$i;
                if(isset($_POST[$index4]))$konversi[$i]=$_POST[$index4];
                
                $index5="harga".$i;
                if(isset($_POST[$index5])){
                    $harga[$i]=$_POST[$index5];
                
                    $sum += $harga[$i];
                }
                
            }	
                                            
            $id = ($this->input->post('id'))?$this->regex->_genRegex($this->input->post('id'),'RGXINT'):0;
            
            $dataexc = array(
                'transaction_no' => $this->regex->_genRegex($this->input->post('ponumber'),'RGXQSL'),
                'transaction_code' => '001',
                'transaction_date' => date("Y-m-d"),
                'charge_amount' => $sum,
                'note' => $this->regex->_genRegex($this->input->post('insertNote'),'RGXQSL'),
            );
            
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL')));
            
                /*save transaksi item*/
                $this->m_purchase_order->save('item_transaction',$dataexc);
            

                for($i=0;$i<=$_POST['jmlcell'];$i++)
                {
                    
                    if(isset($itemIDs[$i])){    
                        $datadetail = array(
                            'transaction_no' => $dataexc['transaction_no'],
                            'item_id' => $itemIDs[$i],
                            'quantity' => $qty[$i],
                            'satuan' => $satuan[$i],
                            'konversi' => $konversi[$i],
                            'harga' => $harga[$i],
                        );
                        /*save transaksi item detail*/
                        $this->m_purchase_order->save('item_transaction_detail',$datadetail);
                    }    

                }      
                
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL')));
                
                /*update transaksi item*/
                $this->m_purchase_order->update(array('item_transaction_id' => $id), array('updated_date' =>  $dataexc['updated_date'],'updated_by' => $dataexc['updated_by'],'note' => $this->regex->_genRegex($this->input->post('insertNote'),'RGXQSL'),'charge_amount' => $sum));
                
                
                /*delete detail first*/
                //$del = $this->m_purchase_order->delete('item_transaction_detail', array('transaction_no' => $this->regex->_genRegex($this->input->post('ponumber'),'RGXQSL')));
                $this->db->delete('item_transaction_detail', array('transaction_no' => $this->regex->_genRegex($this->input->post('ponumber'),'RGXQSL')));
                //print_r($del);die;

                for($i=0;$i<=$_POST['jmlcell'];$i++)
                {
                    
                    if(isset($itemIDs[$i])){    
                        $datadetail = array(
                            'transaction_no' => $dataexc['transaction_no'],
                            'item_id' => $itemIDs[$i],
                            'quantity' => $qty[$i],
                            'satuan' => $satuan[$i],
                            'konversi' => $konversi[$i],
                            'harga' => $harga[$i],
                        );
                        /*save transaksi item detail*/
                        $this->m_purchase_order->save('item_transaction_detail',$datadetail);
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
        $item_id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        $toArray = explode(',',$item_id);
        //print_r($item_id);print_r($toArray);die;
        if($item_id!=null){
            if($this->m_purchase_order->delete_by_id($toArray)){
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