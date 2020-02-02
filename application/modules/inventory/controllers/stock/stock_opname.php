<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Stock_opname extends MX_Controller {

        public function __construct()
        {
            parent::__construct();
            //Load Dependencies
            $this->load->model('m_stock_opname','m_stock_opname'); 
            $this->load->model('m_stock_opname_detail','m_stock_opname_detail'); 
            $this->load->model('control/m_satuan','m_satuan'); 
            $this->load->model('control/m_group_item','m_group_item');
        
            $this->load->library('bcrypt');

            $this->title = ($this->lib_menus->get_menu_by_link('inventory/stock/stock_opname'))?$this->lib_menus->get_menu_by_link('inventory/stock/stock_opname')->program_name : 'Title';
            $this->level = ($this->lib_menus->get_menu_by_link('inventory/stock/stock_opname'))?$this->lib_menus->get_menu_by_link('inventory/stock/stock_opname')->level_program : 'Level';
            $this->parent = ($this->lib_menus->get_menu_by_link('inventory/stock/stock_opname'))?$this->lib_menus->get_menu_by_link('inventory/stock/stock_opname')->parent_name : 'Parent';

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

            $this->load->view('inventory/stock/stock_opname/index',$data);
            
        }
        
        public function form($id='')
        {
            /*if id is not null then will show form edit*/
            if( $id != '' ){
                
                /*get value by id*/
                $data['value'] = $this->m_stock_opname->get_by_id($id);
                /*initialize flag for form*/
                $data['flag'] = "update";
            }else{
                /*initialize flag for form add*/
                $data['flag'] = "create";
                $data['value_numrow'] = 0;
              
            }
            /*title header*/  
            
            $this->load->view('inventory/stock/stock_opname/form', $data);
        }

        public function show($id)
        {
            /*breadcrumbs for view*/
           // $this->breadcrumbs->push('View '.$this->title.'', 'reference/mst_global_param/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$gp_id);
            /*define data variabel*/
            $data['value'] = $this->m_stock_opname->get_by_id($id);
          //  $data['title'] = $this->title;
            $data['flag'] = "read";
           
            //print_r($data);die; 
            $this->load->view('inventory/stock/stock_opname/form', $data);
        }
    
       
        public function get_data()
        {
            /*get data from model*/
            $list = $this->m_stock_opname->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $row[] = '<div class="center"><label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->so_no.'"/>
                            <span class="lbl"></span>
                        </label></div>';
                $row[] = $row_list->so_no; 
                $row[] = $row_list->unit_name; 
                $row[] = $row_list->so_date; 
                $row[] = $row_list->note; 
                $row[] = '<div class="center">
                                '.date("d-m-Y", strtotime($row_list->created_date)).'
                            </div>';
                $row[] = '<div class="center">
                            '.$row_list->created_by.'
                            </div>';
                if( $row_list->so_status == 0){
                    $row[] = '<div class="center">
                                '.$this->authuser->show_button('inventory/stock/stock_opname','U',$row_list->so_no,3).'
                            </div>';
                } else{
                    $row[] = '<div class="center">
                                '.$this->authuser->show_button('inventory/stock/stock_opname','R',$row_list->so_no,2).'
                            </div>';
                }
                
                                               
                $data[] = $row;
            }

            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->m_stock_opname->count_all(),
                            "recordsFiltered" => $this->m_stock_opname->count_filtered(),
                            "data" => $data,
                    );
            //output to json format
            echo json_encode($output);
        }

        public function get_data_detail($id)
        {
            //$id = $_POST['id'];
            /*get data from model*/
            $list = $this->m_stock_opname_detail->get_datatables($id);
            $data = array();
            $no = $_POST['start'];
            $i = 0;
            foreach ($list as $row_list) {
                $no++;
                $row = array();

                $row[] = $no; 
                $row[] = $row_list->so_item_id; 
                $row[] = $row_list->item_name; 
                $row[] = '<div class="center">
                            <input id="qty" name="qty'.$i.'" value="'.$row_list->so_quantity.'" placeholder="0" class="form-control" type="number" step="0.01">
                            <input id="hrg" name="hrg'.$i.'" value="'.$row_list->harga_beli.'" placeholder="0" class="form-control" type="hidden" step="0.01">
                            <input id="item" name="item'.$i.'" value="'.$row_list->so_item_id.'" class="form-control" type="hidden">
                        </div>';  
                if($row_list->so_status == 0){
                    $row[] = '<div class="center">
                                <input id="qty_result" name="qty_result'.$i.'" value="" placeholder="0" class="form-control" type="number" step="0.01">
                            </div>'; 
                }else{
                    $row[] = '<div class="center">
                                <input id="qty_result" name="qty_result'.$i.'" value="'.$row_list->so_quantity_result.'" placeholder="0" class="form-control" type="number" step="0.01">
                            </div>'; 
                }
                        
                $row[] = $row_list->satuan; 

                $i++;
                                               
                $data[] = $row;
            }

            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->m_stock_opname_detail->count_all($id),
                            "recordsFiltered" => $this->m_stock_opname_detail->count_filtered($id),
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
        
            $so_no = $this->master->getMaxTransNo('stock_opname',array('YEAR(so_date)' => date('Y'), 'MONTH(so_date)' => date('m')),'SO');

            $dataexc = array(
                'so_no' => $so_no,
                'so_loc' => $this->regex->_genRegex($this->input->post('insertlocunit'),'RGXQSL'),
                'so_date' => date("Y-m-d"),
                'note' => $this->regex->_genRegex($this->input->post('insertNote'),'RGXQSL'),
                'created_date' => date('Y-m-d H:i:s'),
                'created_by' => json_encode(array('username' =>$this->regex->_genRegex($this->session->userdata('username'),'RGXQSL'), 'nama' => $this->regex->_genRegex($this->session->userdata('nama'),'RGXQSL'))),
            );

            $item_so = $this->db->join('item','item.item_id=stock_item.item_id')->get_where('stock_item', array('stock_item.loc_id' => $this->input->post('insertlocunit'),'item.is_active' => 'Y'))->result();
            
            $data_det=array();

            foreach ($item_so as $value) {
                # code...
                
                $data_detail = array(
                    'so_no' => $so_no,
                    'so_item_id' => $value->item_id,
                    'so_quantity' => $value->balance,
                    'satuan' => $value->satuan,
                );

                $data_det[]= $data_detail;
            }

            //print_r($dataexc);print_r($data_det);die;
           
            /* save so*/
            $this->m_stock_opname->save('stock_opname',$dataexc);

             /* save so detail*/
             foreach ($data_det as $val) {
                 # code...
                 $this->m_stock_opname->save('stock_opname_detail',$val);
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

    public function process_so()
    {
        $id = $this->input->post('so_number');
        $list = $this->m_stock_opname_detail->get_by_id($id);
        $num = count($list);$x=1;
        //print_r($num);die;

        for ($i=0; $i < $num; $i++) { 
            # code...
            $this->form_validation->set_rules('qty_result'.$i.'', 'No '.$x.'', 'trim|required');
            $x++;
        }
        

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
            
            $sum = $sum_amount = 0;

            //$data_so = array();

            for($i=0;$i < $num;$i++){
                
                $index1="qty".$i;
                if(isset($_POST[$index1])){
                    $qty[$i]=(int)$_POST[$index1];
                }

                $index2="qty_result".$i;
                if(isset($_POST[$index2])){
                    $qty_result[$i]=(int)$_POST[$index2];
                }

                $index3="hrg".$i;
                if(isset($_POST[$index3])){
                    $hrg[$i]=$_POST[$index3];
                }

                $index4="item".$i;
                if(isset($_POST[$index4])){
                    $item[$i]=$_POST[$index4];
                }

                //print_r($qty[$i]);print_r($qty_result[$i]);

                $selisih[$i] = $qty_result[$i]-$qty[$i];
            
                //print_r($selisih[$i]);die;

                $selisih_amount[$i] = $hrg[$i] * $selisih[$i];

                $sum +=  $selisih[$i];

                $sum_amount += $selisih_amount[$i];

                $dataexc = array(
                    'so_quantity_result' => $qty_result[$i],
                    'selisih' => $selisih[$i],
                    'selisih_amount' => $selisih_amount[$i],
                );

                //print_r($dataexc);die;

                $this->m_stock_opname_detail->update('stock_opname_detail',$dataexc, array('so_no' =>  $id,'so_item_id' => $item[$i]));

                //$data_so[]=$dataexc;
                                            
            }	
                                 
            //print_r($data_so);die;
            $this->m_stock_opname_detail->update('stock_opname',array('so_status' =>  1,'so_total_selisih' => $sum, 'so_total_selisih_amount' => $sum_amount ), array('so_no' => $id));

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
            if($this->m_stock_opname->delete_by_id($toArray)){
                //$this->logs->save('tmp_mst_global_parameter', $item_id, 'delete record', '', 'item_id');
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function exportPdf() { 
        
        $this->load->library('pdf');
        // $reg_data = $data->data->reg_data;
        // /*default*/
        // $action = ($act_code=='')?'I':$act_code;
        // /*filename and title*/
        // $filename = $flag.'-'.$reg_data->csm_rp_no_mr.$reg_data->no_registrasi.$pm;

        $loc = $_GET['a'];
        $unit_name = $_GET['b'];
        $list = $this->m_stock_opname_detail->get_detailed_by_loc($loc);

        $html = '';

        $html .= '<b><h3>Stock Opname '.$unit_name.'</h3></b>';
        $html .= '<table border="1" cellspacing="0" cellpadding="5">';
        $html .= '<tr>';
            $html .= '<th width="20%">Item ID</th>';
            $html .= '<th width="40%">Item Name</th>';
            $html .= '<th width="10%">Quantity</th>';
            $html .= '<th width="10%" style="text-align:center">Result</th>';
            $html .= '<th width="20%" style="text-align:center">Satuan</th>';
        $html .= '</tr>'; 
        foreach ($list as $value) {
            # code...
        $html .= '<tr>';
            $html .= '<td>'.$value->so_item_id.'</td>';    
            $html .= '<td>'.$value->item_name.'</td>';
            $html .= '<td style="text-align:center">'.$value->so_quantity.'</td>';
            $html .= '<td></td>';
            $html .= '<td style="text-align:center">'.$value->satuan.'</td>';
        $html .= '</tr>'; 
        }
        $html .= '</table>';
       

        //$tanggal = new Tanggal();
        $this->pdf->print_pdf($html,$unit_name);
    }

    public function exportPdf_result() { 
        
        $this->load->library('pdf');
        // $reg_data = $data->data->reg_data;
        // /*default*/
        // $action = ($act_code=='')?'I':$act_code;
        // /*filename and title*/
        // $filename = $flag.'-'.$reg_data->csm_rp_no_mr.$reg_data->no_registrasi.$pm;

        $loc = $_GET['a'];
        $unit_name = $_GET['b'];
        $list = $this->m_stock_opname_detail->get_detailed_by_loc($loc);

        $html = '';

        $html .= '<b><h3>Stock Opname '.$unit_name.'</h3></b>';
        $html .= '<table border="1" cellspacing="0" cellpadding="5">';
        $html .= '<tr>';
            $html .= '<th width="20%">Item ID</th>';
            $html .= '<th width="40%">Item Name</th>';
            $html .= '<th width="10%">Quantity</th>';
            $html .= '<th width="10%" style="text-align:center">Result</th>';
            $html .= '<th width="20%" style="text-align:center">Satuan</th>';
        $html .= '</tr>'; 
        foreach ($list as $value) {
            # code...
        $html .= '<tr>';
            $html .= '<td>'.$value->so_item_id.'</td>';    
            $html .= '<td>'.$value->item_name.'</td>';
            $html .= '<td style="text-align:center">'.$value->so_quantity.'</td>';
            $html .= '<td style="text-align:center">'.$value->so_quantity_result.'</td>';
            $html .= '<td style="text-align:center">'.$value->satuan.'</td>';
        $html .= '</tr>'; 
        }
        $html .= '</table>';
       

        //$tanggal = new Tanggal();
        $this->pdf->print_pdf($html,$unit_name);
    }
}

    
    
    /* End of file User.php */
    

?>