<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class M_purchase_order_receive extends CI_Model {

        var $table = 'item_transaction';
        var $column = array('item_transaction.item_transaction_id','item_transaction.transaction_no','item_transaction.transaction_date','item_transaction.note','item_transaction.created_date','item_transaction.created_by');
        var $select = 'item_transaction.*, item_transaction_detail.*,item.item_id,item.item_name';

        var $order = array('item_transaction.item_transaction_id' => 'DESC');

        public function __construct()
        {
            parent::__construct();
            $this->load->database();
        }

        private function _main_query(){
            $this->db->select($this->column);
            $this->db->from($this->table);
            $this->db->join('item_transaction_detail', 'item_transaction_detail.transaction_no = '.$this->table.'.transaction_no', 'left');
            $this->db->join('item', 'item.item_id = item_transaction_detail.item_id', 'left');
            $this->db->where($this->table.".transaction_code = '002'");
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
            $query = "select * from item_transaction 
            where transaction_no = '".$id."'";
            $exc = $this->db->query($query);
            return $exc->row();
            
        }

        public function get_detail_by_id($id)
        {
            # code...
            $query = "select item_transaction_detail.*,item.item_name from item_transaction_detail 
                    join item on item.item_id=item_transaction_detail.item_id
					where item_transaction_detail.transaction_no = '".$id."'";
            $exc = $this->db->query($query);
            return $exc->result();
        }

        public function get_numrow_detail_by_id($id)
        {
            # code...
            $query = "select item_transaction_detail.*,item.item_name from item_transaction_detail 
                    join item on item.item_id=item_transaction_detail.item_id
					where item_transaction_detail.transaction_no = '".$id."'";
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

        public function get_harga_beli($item,$no)
        {
            # code...
            $this->db->select('item_transaction_detail.*,item.*,tmp_mst_satuan.satuan_name as satuan_beli');
            $this->db->from('item_transaction_detail');
            $this->db->join('item', 'item.item_id = item_transaction_detail.item_id', 'left');
            $this->db->join('tmp_mst_satuan', 'tmp_mst_satuan.satuan_id = item.satuan_beli_id', 'left');
            $this->db->where(array('item_transaction_detail.item_id' => $item,'item_transaction_detail.transaction_no' => $no));
            $query = $this->db->get();
            return $query->result();
            
        }

        public function get_po_complete($ref)
        {
            # code...
            $query = "SELECT DISTINCT item_id FROM item_transaction_detail WHERE transaction_no = '".$ref."' AND item_id NOT IN ( SELECT DISTINCT item_id FROM item_transaction_detail where reference_no = '".$ref."' )";
            $exc = $this->db->query($query);
            return $exc->num_rows();
        }
    
    }
    
    /* End of file ModelName.php */
    
?>