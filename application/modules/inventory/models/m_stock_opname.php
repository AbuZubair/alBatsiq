<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class M_stock_opname extends CI_Model {

        var $table = 'stock_opname';
        var $column = array('stock_opname.so_id','stock_opname.so_no','stock_opname.so_loc','stock_opname.so_date','stock_opname.note','stock_opname.so_status','stock_opname.so_total_selisih','stock_opname.created_date','stock_opname.created_by','unit.unit_name');
        var $select = 'stock_opname.*, stock_opname_detail.*,item.item_id,item.item_name,unit.loc_id,unit.unit_name';

        var $order = array('stock_opname.so_id' => 'DESC');

        public function __construct()
        {
            parent::__construct();
            $this->load->database();
        }

        private function _main_query(){
            $this->db->select($this->column);
            $this->db->from($this->table);
            $this->db->join('stock_opname_detail', 'stock_opname_detail.so_no = '.$this->table.'.so_no', 'left');
            $this->db->join('item', 'item.item_id = stock_opname_detail.so_item_id', 'left');
            $this->db->join('unit', 'unit.loc_id = '.$this->table.'.so_loc', 'left');
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
            $query = "select stock_opname.*,unit.unit_name,unit.loc_id from stock_opname 
            left join unit on unit.loc_id=stock_opname.so_loc
            where so_no = '".$id."'";
            $exc = $this->db->query($query);
            return $exc->row();
            
        }

        public function get_detail_by_id($id)
        {
            # code...
            $query = "select stock_opname_detail.*,item.item_name from stock_opname_detail 
                    join item on item.item_id=stock_opname_detail.item_id
					where stock_opname_detail.so_no = '".$id."'";
            $exc = $this->db->query($query);
            return $exc->result();
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

        public function get_harga_beli($item,$no)
        {
            # code...
            $this->db->select('stock_opname_detail.*,item.*,tmp_mst_satuan.satuan_name as satuan_beli');
            $this->db->from('stock_opname_detail');
            $this->db->join('item', 'item.item_id = stock_opname_detail.item_id', 'left');
            $this->db->join('tmp_mst_satuan', 'tmp_mst_satuan.satuan_id = item.satuan_beli_id', 'left');
            $this->db->where(array('stock_opname_detail.item_id' => $item,'stock_opname_detail.so_no' => $no));
            $query = $this->db->get();
            return $query->result();
            
        }

        public function get_po_complete($ref)
        {
            # code...
            $query = "SELECT DISTINCT item_id FROM stock_opname_detail WHERE so_no = '".$ref."' AND item_id NOT IN ( SELECT DISTINCT item_id FROM stock_opname_detail where reference_no = '".$ref."' )";
            $exc = $this->db->query($query);
            return $exc->num_rows();
        }
    
    }
    
    /* End of file ModelName.php */
    
?>