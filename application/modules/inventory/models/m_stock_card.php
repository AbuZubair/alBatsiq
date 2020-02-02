<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class M_stock_card extends CI_Model {

        var $table = 'stock_card';
        var $column = array('stock_card.loc_id','stock_card.item_id','stock_card.transaction_code','stock_card.stock_in','stock_card.stock_out','stock_card.stock_before','stock_card.stock_balance','stock_card.loc_ref','item.item_name','a.unit_name as unit','b.unit_name as unit_ref','stock_card.created_date','stock_card.satuan');
        var $select = 'stock_card.*, item.item_name , a.unit_name, b.unit_name';

        var $order = array('stock_card.loc_id' => 'asc');

        public function __construct()
        {
            parent::__construct();
            $this->load->database();
        }

        private function _main_query(){
            $this->db->select($this->column);
            $this->db->from($this->table);
            $this->db->join('item', 'item.item_id = '.$this->table.'.item_id', 'left');
            $this->db->join('unit a', 'a.loc_id = '.$this->table.'.loc_id', 'left');
            $this->db->join('unit b', 'b.loc_id = '.$this->table.'.loc_ref', 'left');
        }

        private function _get_datatables_query()
        {
            
            $this->_main_query();

            $i = 0;
        
            foreach ($this->column as $item) 
            {
                if($_POST['search']['value'])
                    ($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
                $column[$i] = $item;
                $i++;
            }
            
            if(isset($_POST['order']))
            {
                $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            } 
            else if(isset($this->order))
            {
                $order = $this->order;
                $this->db->order_by(key($order), $order[key($order)]);
            }
        }

        
        function get_datatables()
        {
            $this->_get_datatables_query();
            if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
            $query = $this->db->get();
            return $query->result();
        }

        function count_filtered()
        {
            $this->_get_datatables_query();
            $query = $this->db->get();
            return $query->num_rows();
        }

        public function count_all()
        {
            $this->_main_query();
            return $this->db->count_all_results();
        }
    
        public function get_by_id($id)
        {
            $this->_main_query();
            if(is_array($id)){
                $this->db->where_in(''.$this->table.'.transaction_no',$id);
                $query = $this->db->get();
                return $query->result();
            }else{
                $this->db->where(''.$this->table.'.transaction_no',$id);
                $query = $this->db->get();
                return $query->row();
            }
            
        }

        public function save($table,$data)
        {
            $this->db->insert($table, $data);
            return $this->db->insert_id();
        }

        public function update($where, $data)
        {
            $this->db->update($this->table, $data, $where);
            return $this->db->affected_rows();
        }

 
        public function delete_by_id($id)
        {
            $get_data = $this->get_by_id($id);
            $this->db->where_in(''.$this->table.'.transaction_no', $id);
            return $this->db->delete($this->table,array());
        }

        public function get_data($item,$loc)
        {
            $this->_main_query();

            if (isset($item) AND $item != '') {
	            $this->db->where(array('stock_card.item_id' => $item));	
	        }

	        if (isset($loc) AND $loc != '') {
	            $this->db->where(array('stock_card.loc_id' => $loc));	
	        }
            $this->db->order_by('sc_id','desc');
            $query = $this->db->get();
            return $query->result();
        }

        function get_exported_data()
        {
            $this->_main_query();
            $query = $this->db->get();
            //print_r($this->db->last_query());
            return $query->result();
        }
    
    }
    
    /* End of file ModelName.php */
    
?>