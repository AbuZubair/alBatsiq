<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class M_stock_opname_detail extends CI_Model {

        var $table = 'stock_opname_detail';
        var $column = array('stock_opname_detail.so_no','stock_opname_detail.so_item_id','stock_opname_detail.so_quantity','stock_opname_detail.so_quantity_result','stock_opname_detail.satuan','stock_opname_detail.selisih','stock_opname.so_status','item.item_name','item.harga_beli');
        var $select = 'stock_opname_detail.*, stock_opname.so_status, item.item_id,item.item_name,item.harga_beli,unit.loc_id,unit.unit_name';

        var $order = array('stock_opname_detail.so_item_id' => 'ASC');

        public function __construct()
        {
            parent::__construct();
            $this->load->database();
        }

        private function _main_query(){
            $this->db->select($this->column);
            $this->db->from($this->table);
            $this->db->join('stock_opname', 'stock_opname.so_no = '.$this->table.'.so_no', 'left');
            $this->db->join('item', 'item.item_id = '.$this->table.'.so_item_id', 'left');
            $this->db->join('unit', 'unit.loc_id = stock_opname.so_loc', 'left');
            $this->db->group_by($this->column);
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

        
        function get_datatables($id)
        {
            $this->_get_datatables_query();
            if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
            $this->db->where('stock_opname_detail.so_no',$id);
            $query = $this->db->get();
            return $query->result();
        }

        public function get_by_id($id)
        {
            $this->_main_query();
            $this->db->where('stock_opname_detail.so_no',$id);
            $query = $this->db->get();
            return $query->result();
        }

        function count_filtered($id)
        {
            $this->_get_datatables_query();
            $this->db->where('stock_opname_detail.so_no',$id);
            $query = $this->db->get();
            return $query->num_rows();
        }

        public function count_all($id)
        {
            $this->_main_query();
            $this->db->where('stock_opname_detail.so_no',$id);
            return $this->db->count_all_results();
        }
    
        
        public function get_numrow_detail_by_id($id)
        {
            # code...
            $query = "select stock_opname_detail.*,item.item_name from stock_opname_detail 
                    join item on item.item_id=stock_opname_detail.item_id
					where stock_opname_detail.so_no = '".$id."'";
            $exc = $this->db->query($query);
            return $exc->num_rows();
        }

        public function save($table,$data)
        {
            $this->db->insert($table, $data);
            return $this->db->insert_id();
        }

        public function update($table, $data, $where)
        {
            $this->db->update($table, $data, $where);
            return $this->db->affected_rows();
        }

        public function delete($table,$where)
        {
            # code...
            $this->db->delete($table,$where);
            return $this->db->affected_rows();
        }

       public function get_detailed_by_loc($loc)
       {
           # code...
           $this->_main_query();
           $this->db->where('so_loc',$loc);
           $query = $this->db->get();
           return $query->result();
       }
    }
    
    /* End of file ModelName.php */
    
?>