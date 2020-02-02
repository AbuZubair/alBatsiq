<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Production extends MX_Controller {

        public function __construct()
        {
            parent::__construct();
            //Load Dependencies
            $this->load->model('m_production','m_production'); 
            $this->load->model('control/m_satuan','m_satuan'); 
            $this->load->model('control/m_group_item','m_group_item');
        
            $this->load->library('bcrypt');

            $this->title = ($this->lib_menus->get_menu_by_link('inventory/production'))?$this->lib_menus->get_menu_by_link('inventory/production')->program_name : 'Title';
            $this->level = ($this->lib_menus->get_menu_by_link('inventory/production'))?$this->lib_menus->get_menu_by_link('inventory/production')->level_program : 'Level';
            $this->parent = ($this->lib_menus->get_menu_by_link('inventory/production'))?$this->lib_menus->get_menu_by_link('inventory/production')->parent_name : 'Parent';

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

            $this->load->view('inventory/production/index',$data);
            
        }
        
        public function form($id='')
        {
            /*if id is not null then will show form edit*/
            if( $id != '' ){
                
                /*get value by id*/
                $data['value'] = $this->m_production->get_by_id($id);
                $data['value_detail'] = $this->m_production->get_detail_by_id($id);
                $data['value_numrow'] = $this->m_production->get_numrow_detail_by_id($id);
                $bal_ = $this->m_production->get_balance($id);
                //print_r($bal_);die;
                $bal = $bal_->balance*$bal_->konversi;
                $data['balance_bahan'] = $bal;
                /*initialize flag for form*/
                $data['flag'] = "update";
            }else{
                /*initialize flag for form add*/
                $data['flag'] = "create";
                $data['value_numrow'] = 0;
                $data['trans_no'] = $this->master->getMaxTransNo('production',array('YEAR(production_date)' => date('Y'), 'MONTH(production_date)' => date('m')),'PROD');
              
            }
            /*title header*/  

            $this->load->view('inventory/production/form', $data);
        }

        public function show($id)
        {
            /*breadcrumbs for view*/
           // $this->breadcrumbs->push('View '.$this->title.'', 'reference/mst_global_param/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$gp_id);
            /*define data variabel*/
            $data['value'] = $this->m_production->get_by_id($id);
            $data['value_detail'] = $this->m_production->get_detail_by_id($id);
            $data['value_numrow'] = $this->m_production->get_numrow_detail_by_id($id);
            $bal_ = $this->m_production->get_balance($id);
            //print_r($bal_);die;
            //$bal = $bal_->balance*$bal_->konversi;
            $data['balance_bahan'] = json_encode($bal_);
          //  $data['title'] = $this->title;
            $data['flag'] = "read";
           
            // print_r($data);die; 
            $this->load->view('inventory/production/form', $data);
        }
    
       
        public function get_data()
        {
            /*get data from model*/
            $list = $this->m_production->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $row[] = '<div class="center"><label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->production_no.'"/>
                            <span class="lbl"></span>
                        </label></div>';
                $row[] = $row_list->production_no; 
                $row[] = $row_list->production_date; 
                $row[] = $row_list->note; 
                $row[] = '<div class="center">
                                '.date("d-m-Y", strtotime($row_list->created_date)).'
                            </div>';
                $row[] = '<div class="center">
                            '.$row_list->created_by.'
                            </div>';
                $row[] = '<div class="center">
                                '.$this->authuser->show_button('inventory/production','R',$row_list->production_no,2).'
                                <button class="btn btn-xs btn-danger" onclick="delete_data('."'".$row_list->production_no."'".')"><i class="ace-icon fa fa-times bigger-50"></i> Delete</button>
                            </div>';
                                               
                $data[] = $row;
            }

            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->m_production->count_all(),
                            "recordsFiltered" => $this->m_production->count_filtered(),
                            "data" => $data,
                    );
            //output to json format
            echo json_encode($output);
        }

        public function process()
    {
        $this->form_validation->set_rules('insertlocunit', 'From Unit', 'trim|required');
        //$this->form_validation->set_rules('insertBahan', 'Bahan Dasar', 'trim|required');
        //$this->form_validation->set_rules('qty_bahan', '', 'trim|required');
        $this->form_validation->set_rules('prodtgl', 'Date', 'trim|required');
        
        // print_r($_POST['insertBahan']);
        // print_r($_POST['qty_bahan']);die;

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
        
            $sum =  array();
            $sum_amount = 0;

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
                
                $index4="bahan_dipakai".$i;
                if(isset($_POST[$index4])){
                    foreach($_POST[$index4] as $k=>$v){
                        $sum[$k] = 0;
                        $bahan_dipakai[$i][$k]=$v;
                
                        $sum[$k] += $bahan_dipakai[$i][$k];
                    }
                }
                
                $index5="harga".$i;
                if(isset($_POST[$index5])){
                    $harga[$i]=$_POST[$index5];
                
                    $sum_amount += $harga[$i];
                }
            }	
                                        
            $id = ($this->input->post('id'))?$this->regex->_genRegex($this->input->post('id'),'RGXINT'):0;

            //print_r($_POST['jmlcell']);die;
            
            $dataexc = array(
                'production_no' => $this->regex->_genRegex($this->input->post('prodnumber'),'RGXQSL'),
                'transaction_code' => '500',
                'loc_id' => $this->input->post('insertlocunit'),
                'production_date' => $this->input->post('prodtgl'),
                //'item_id_bahan' => $this->input->post('insertBahan'),
                'charge_amount' => $sum_amount,
                'note' => $this->regex->_genRegex($this->input->post('insertNote'),'RGXQSL'),
            );
            
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL')));           
                
                foreach($_POST['insertBahan'] as $k=>$v){
                     
                    /*update stok bahan */
    
                    $cekbahan = $this->db->join('tmp_mst_satuan', 'tmp_mst_satuan.satuan_id = item.satuan_beli_id')->get_where('item', array('item_id' => $v));
                    $fetchbalanceBahanFrom = $this->db->get_where('stock_item', array('loc_id' => $this->input->post('insertlocunit'), 'item_id' => $v))->row();
                    $num = $cekbahan->num_rows();
                    $fetchbalance = $cekbahan->row();
                    $stockskg = $_POST['qty_bahan'][$k]/$fetchbalance->konversi*1;
                    $stock_out = $sum[$k]/$fetchbalance->konversi*1;
                    //print_r($stock_out);die;
                    $data_balance_bahan = array(
                        'loc_id' => $this->input->post('insertlocunit'),
                        'item_id' =>$v,
                        'balance' => $stockskg,
                        'satuan' => $fetchbalance->satuan_name,
                        'date' => date("Y-m-d"),
                        'updated_date' => date('Y-m-d H:i:s'),
                        'updated_by' => json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL'))),
                    );    
                    //print_r($data_balance_bahan);die;
                    /*update stock item*/
                    $this->db->update('stock_item', $data_balance_bahan ,array('loc_id' => $this->input->post('insertlocunit'), 'item_id' => $v));
                    $this->db->trans_commit();
    
                    /*update stock card*/
                    $data_sc = array(
                        'loc_id' =>$this->input->post('insertlocunit'),
                        'item_id' => $v,
                        'transaction_date' => $this->input->post('prodtgl'),
                        'transaction_code' => '500',
                        'transaction_no' => $dataexc['production_no'],
                        'created_date' => date('Y-m-d H:i:s'),
                        'created_by' => $data['created_by'] = json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL'))),                     
                        'stock_in' => 0,
                        'stock_out' => $stock_out,
                        'stock_before' => $fetchbalanceBahanFrom->balance,
                        'stock_balance' => $stockskg,
                        'satuan' => $data_balance_bahan['satuan'],
                    );
    
                    $this->db->insert('stock_card', $data_sc);
    
                    /*-------------------------------------------------*/
                    
                }
                //print_r($_POST['jmlcell']);
                for($i=0;$i<=$_POST['jmlcell'];$i++)
                {   //print_r($bahan_dipakai[$i]);die();
                    foreach($bahan_dipakai[$i] as $k=>$v){
                        if(isset($itemIDs[$i])){    
                            $datadetail = array(
                                'production_no' => $dataexc['production_no'],
                                'item_id_bahan' => $_POST['insertBahan'][$k],
                                'bahan_quantity' => $v,
                                'satuan_bahan' => $_POST['satuan_bahan'][$k],
                                'item_id_result' => $itemIDs[$i],
                                'result_quantity' => $qty[$i],
                                'satuan_result' => $satuan[$i],
                                'harga' => $harga[$i],
                            );
                            /*save transaksi item detail*/
                            $this->m_production->save('production_detail',$datadetail);
    
                            /* update balance item to unit*/
                            if (isset($_POST['insertlocunit'])){
    
                                $cekbalance = $this->db->get_where('stock_item', array('loc_id' => $this->input->post('insertlocunit'), 'item_id' => $itemIDs[$i]));
                                $num = $cekbalance->num_rows();
                                $fetchbalance = $cekbalance->row();
                                
                                $data_balance = array(
                                    'loc_id' => $this->input->post('insertlocunit'),
                                    'item_id' => $itemIDs[$i],
                                    'balance' => $qty[$i],
                                    'satuan' => $satuan[$i],
                                    'date' => date("Y-m-d"),
                                    'updated_date' => date('Y-m-d H:i:s'),
                                    'updated_by' => json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL'))),
                                );
    
                                if($num == 0){
                                   
                                    /*save balance item*/
                                    $this->stock->save_stock($data_balance,'500',$dataexc['production_no'],$this->input->post('insertlocunit'));
                                    
                                }else{
                                    
                                    $this->stock->update_stock($data_balance, $fetchbalance,'500',$dataexc['production_no'],'add',$this->input->post('insertlocunit'));
    
                                }	
                            }
    
                        }    
                    }
                    
                }      

                
                /*save transaksi item*/
                $this->m_production->save('production',$dataexc);
               
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
        if($item_id!=null){
            if($this->m_production->delete_by_id($item_id)){
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