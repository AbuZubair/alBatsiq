<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class M_purchase_order extends CI_Model {

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
            $this->db->where($this->table.".transaction_code = '001'");
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

        public function get_po($po)
        {
            # code...
            $query = "select * from item_transaction where status = 0 and transaction_code='001' and transaction_no='".$po."'";
            $exc = $this->db->query($query);
            return $exc->num_rows();
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
    
    }
    
    /* End of file ModelName.php */
    
?>