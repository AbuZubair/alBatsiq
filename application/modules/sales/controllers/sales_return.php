<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Sales_return extends MX_Controller {

        public function __construct()
        {
            parent::__construct();
            //Load Dependencies
            $this->load->model('m_sales_return','m_sales_return'); 
            $this->load->model('control/m_satuan','m_satuan'); 
            $this->load->model('control/m_group_item','m_group_item');
        
            $this->load->library('bcrypt');

            $this->title = ($this->lib_menus->get_menu_by_link('sales/sales_return'))?$this->lib_menus->get_menu_by_link('sales/sales_return')->program_name : 'Title';
            $this->level = ($this->lib_menus->get_menu_by_link('sales/sales_return'))?$this->lib_menus->get_menu_by_link('sales/sales_return')->level_program : 'Level';
            $this->parent = ($this->lib_menus->get_menu_by_link('sales/sales_return'))?$this->lib_menus->get_menu_by_link('sales/sales_return')->parent_name : 'Parent';

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

            $this->load->view('sales/sales_return/index',$data);
            
        }
        
        public function form($id='')
        {
            
            /*if id is not null then will show form edit*/
            if( $id != '' ){
                
                /*get value by id*/
                $data['value'] = $this->m_sales_return->get_by_id($id);
                $data['value_detail'] = $this->m_sales_return->get_detail_by_id($id);
                $data['value_numrow'] = $this->m_sales_return->get_numrow_detail_by_id($id);
                /*initialize flag for form*/
                $data['flag'] = "update";
            }else{
                /*initialize flag for form add*/
                $data['flag'] = "create";
                $data['value_numrow'] = 0;
                $data['trans_no'] = $this->master->getMaxTransNo('sales',array('sales_code' => '710', 'YEAR(sales_date)' => date('Y'), 'MONTH(sales_date)' => date('m')),'SALESRET');
              
            }
            /*title header*/  
            
            $this->load->view('sales/sales_return/form', $data);
        }

        public function show($id)
        {
            /*breadcrumbs for view*/
           // $this->breadcrumbs->push('View '.$this->title.'', 'reference/mst_global_param/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$gp_id);
            /*define data variabel*/
            $data['value'] = $this->m_sales_return->get_by_id($id);
            $data['value_detail'] = $this->m_sales_return->get_detail_by_id($id);
          //  $data['title'] = $this->title;
            $data['flag'] = "read";
           
            //print_r($data);die; 
            $this->load->view('sales/sales_return/form', $data);
        }
    
       
        public function get_data()
        {
            /*get data from model*/
            $list = $this->m_sales_return->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $row[] = '<div class="center"><label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->sales_no.'"/>
                            <span class="lbl"></span>
                        </label></div>';
                $row[] = $row_list->sales_no; 
                $row[] = $row_list->sales_date; 
                $row[] = $row_list->note; 
                $row[] = '<div class="center">
                                '.date("d-m-Y", strtotime($row_list->created_date)).'
                            </div>';
                $row[] = '<div class="center">
                            '.$row_list->created_by.'
                            </div>';
                $row[] = '<div class="center">
                                '.$this->authuser->show_button('sales/sales_return','R',$row_list->sales_no,2).'
                                
                            </div>';
                                               
                $data[] = $row;
            }

            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->m_sales_return->count_all(),
                            "recordsFiltered" => $this->m_sales_return->count_filtered(),
                            "data" => $data,
                    );
            //output to json format
            echo json_encode($output);
        }

        public function process()
    {
        $this->form_validation->set_rules('insertlocunit', 'Unit', 'trim|required');
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
                
                $index4="harga".$i;
                if(isset($_POST[$index4]))$harga[$i]=str_replace( ',', '', $_POST[$index4] );
                
                $index5="dsc".$i;
                if(isset($_POST[$index5]))$dsc[$i]=$_POST[$index5];

                $index6="total".$i;
                if(isset($_POST[$index6]))$total[$i]=str_replace( ',', '', $_POST[$index6] );
                
            }	
                                        
            $id = ($this->input->post('id'))?$this->regex->_genRegex($this->input->post('id'),'RGXINT'):0;

            //print_r($_POST['jmlcell']);die;
            $gt = $this->input->post('grand_total');
            $grand_total = str_replace( ',', '', $gt );
            
            $dataexc = array(
                'sales_no' => $this->regex->_genRegex($this->input->post('salesret_number'),'RGXQSL'),
                'sales_code' => '710',
                'sales_date' => date("Y-m-d"),
                'charge_amount' => -$grand_total,
                'loc_id' => $this->input->post('insertlocunit'),
                'reference_no' => $this->input->post('insertReferenceNo'),
                'note' => $this->regex->_genRegex($this->input->post('insertNote'),'RGXQSL'),
            );

            //print_r($dataexc);die;
            
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL')));                     

                for($i=0;$i<=$_POST['jmlcell'];$i++)
                {
                    
                    if(isset($itemIDs[$i])){    
                        $datadetail = array(
                            'sales_no' => $dataexc['sales_no'],
                            'item_id' => $itemIDs[$i],
                            'quantity' => -$qty[$i],
                            'satuan' => $satuan[$i],
                            'harga_jual' => $harga[$i],
                            'discount_amount' => $dsc[$i],
                            'total_amount' =>-$total[$i] ,
                            'reference_no' => $this->input->post('insertReferenceNo'),
                        );

                        //print_r($datadetail);die;
                        /*save transaksi item detail*/
                        $this->m_sales_return->save('sales_detail',$datadetail);

                        /* update balance item to unit*/
                        if (isset($_POST['insertlocunit'])){

                            $cekbalance = $this->db->get_where('stock_item', array('loc_id' => $this->input->post('insertlocunit'), 'item_id' => $itemIDs[$i]));
                            $num = $cekbalance->num_rows();
                            $fetchbalance = $cekbalance->row();
                            //print_r($this->db->last_query());print_r($num);die;
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
                                $this->stock->save_stock($data_balance,'710',$dataexc['sales_no']);

                            }else{
                                
                                $this->stock->update_stock($data_balance, $fetchbalance,'710',$dataexc['sales_no'],'add');
                               
                            }	
                        }

                        
                $ceksalescomplete = $this->m_sales_return->get_sales_complete($this->input->post('insertReferenceNo'));
                                    
                if($ceksalescomplete == 0){
                    
                    $data_status_sales = 3;
                    
                } else{

                    $data_status_sales = 1;

                }

                /*update status po */
                $this->m_sales_return->update('sales',array('status' => $data_status_sales),array('sales_no' => $dataexc['reference_no']));

                        
                    }    

                }      
               
                /*save transaksi item*/
                $this->m_sales_return->save('sales',$dataexc);               
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
            if($this->m_sales_return->delete_by_id($toArray)){
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