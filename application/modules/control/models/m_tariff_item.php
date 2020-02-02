<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class M_tariff_item extends CI_Model {

        var $table = 'item_tariff';
        var $column = array('item_tariff.tariff_id','item_tariff.tariff_id','item.item_name','item_tariff.harga_jual','item_tariff.created_date','item_tariff.created_by');
        var $select = 'item_tariff.*, item.item_name';

        var $order = array('item_tariff.tariff_id' => 'DESC');

        public function __construct()
        {
            parent::__construct();
            $this->load->database();
        }

        private function _main_query(){
            $this->db->select($this->select);
            $this->db->from($this->table);
            $this->db->join('item', 'item.item_id = '.$this->table.'.item_id', 'left');
            
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
                $this->db->where_in(''.$this->table.'.tariff_id',$id);
                $query = $this->db->get();
                return $query->result();
            }else{
                $this->db->where(''.$this->table.'.tariff_id',$id);
                $query = $this->db->get();
                return $query->row();
            }
            
        }

        public function save($data)
        {
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }

        public function get_item()
        {
            $this->db->select('item_id,item_name');
            $this->db->from('item');
            $array = array('sales_available' => 'Y', 'is_active' => 'Y');
            $this->db->where($array);
            $query = $this->db->get();
            return $query->result();
            
        }
    
    }
    
    /* End of file ModelName.php */
    
?>