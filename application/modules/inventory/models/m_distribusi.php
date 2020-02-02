<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class M_distribusi extends CI_Model {

        var $table = 'warehouse_transaction';
        var $column = array('warehouse_transaction.warehouse_transaction_id','warehouse_transaction.transaction_no','warehouse_transaction.transaction_date','warehouse_transaction.note','warehouse_transaction.created_date','warehouse_transaction.created_by');
        var $select = 'warehouse_transaction.*, warehouse_transaction_detail.*,item.item_id,item.item_name';

        var $order = array('warehouse_transaction.warehouse_transaction_id' => 'DESC');

        public function __construct()
        {
            parent::__construct();
            $this->load->database();
        }

        private function _main_query(){
            $this->db->select($this->column);
            $this->db->from($this->table);
            $this->db->join('warehouse_transaction_detail', 'warehouse_transaction_detail.transaction_no = '.$this->table.'.transaction_no', 'left');
            $this->db->join('item', 'item.item_id = warehouse_transaction_detail.item_id', 'left');
            $this->db->where($this->table.".transaction_code = '100'");
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
            $query = "select * from warehouse_transaction 
            where transaction_no = '".$id."'";
            $exc = $this->db->query($query);
            return $exc->row();
            
        }

        public function get_detail_by_id($id)
        {
            # code...
            $query = "select warehouse_transaction_detail.*,item.item_name from warehouse_transaction_detail 
                    join item on item.item_id=warehouse_transaction_detail.item_id
					where warehouse_transaction_detail.transaction_no = '".$id."'";
            $exc = $this->db->query($query);
            return $exc->result();
        }

        public function get_numrow_detail_by_id($id)
        {
            # code...
            $query = "select warehouse_transaction_detail.*,item.item_name from warehouse_transaction_detail 
                    join item on item.item_id=warehouse_transaction_detail.item_id
					where warehouse_transaction_detail.transaction_no = '".$id."'";
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

    
    
    }
    
    /* End of file ModelName.php */
    
?>