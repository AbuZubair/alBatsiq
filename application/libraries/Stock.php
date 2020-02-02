<?php

final Class Stock {

    public function save_stock($data_insert,$trans_code,$trans_no='',$ref='')
    {
        # code...
        $CI =& get_instance();
        $CI->load->library('session');                   
        $CI->load->database('default', TRUE);  

        //print_r($data_insert);

        /*save balance item*/
        $CI->db->insert('stock_item', $data_insert);

        /*save stock card */
        $data_sc = array(
            'loc_id' =>$data_insert['loc_id'],
            'item_id' => $data_insert['item_id'],
            'transaction_date' => $data_insert['date'],
            'transaction_code' => $trans_code,
            'transaction_no' => $trans_no,
            'stock_in' => $data_insert['balance'],
            'stock_out' => 0,
            'stock_before' => 0,
            'stock_balance' => $data_insert['balance'],
            'satuan' => $data_insert['satuan'],
            'created_date' => date('Y-m-d H:i:s'),
            'created_by' => $data['created_by'] = json_encode(array('username' => $CI->session->userdata('username'), 'nama' => $CI->session->userdata('nama'))),
        );

        if($ref!=''){
            $data_sc['loc_ref'] = $ref;
        }

       //print_r($data_sc);die;

        $CI->db->insert('stock_card', $data_sc);
           
      
    }

    public function update_stock($data_insert,$data_stock,$trans_code,$trans_no='',$flag='',$ref='')
    {
        # code...

         # code...
         $CI =& get_instance();
         $CI->load->library('session');                
         $CI->load->database('default', TRUE);  

         //print_r($data_insert);print_r($data_stock);die;

         $data_sc = array(
            'loc_id' =>$data_insert['loc_id'],
            'item_id' => $data_insert['item_id'],
            'transaction_date' => $data_insert['date'],
            'transaction_code' => $trans_code,
            'transaction_no' => $trans_no,
            'satuan' => $data_insert['satuan'],
            'created_date' => date('Y-m-d H:i:s'),
            'created_by' => $data['created_by'] = json_encode(array('username' => $CI->session->userdata('username'), 'nama' => $CI->session->userdata('nama'))),
        );

         if($flag=='add'){
            $balance = $data_insert['balance'] + $data_stock->balance;
            $data_sc['stock_in'] = $data_insert['balance'];
            $data_sc['stock_out'] = 0;
            $data_sc['stock_before'] = $data_stock->balance;
            $data_sc['stock_balance'] = $balance;
            if($ref!=''){
                $data_sc['loc_ref'] = $ref;
            }
         }else{
            $balance = $data_stock->balance - $data_insert['balance'];
            $data_sc['stock_in'] = 0;
            $data_sc['stock_out'] = $data_insert['balance'];
            $data_sc['stock_before'] = $data_stock->balance;
            $data_sc['stock_balance'] = $balance;
            if($ref!=''){
                $data_sc['loc_ref'] = $ref;
            }
         }
         

         /*save balance item*/
         $CI->db->update('stock_item', array('balance' => $balance, 'date' => $data_insert['date']),array('loc_id' => $data_insert['loc_id'], 'item_id' => $data_insert['item_id']));

        /*save stock card */
        $CI->db->insert('stock_card', $data_sc);
 
    }


}

?>