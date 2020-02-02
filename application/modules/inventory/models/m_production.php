<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class M_production extends CI_Model {

        var $table = 'production';
        var $column = array('production.production_id','production.production_no','production.production_date','production.note','production.created_date','production.created_by');
        var $select = 'production.*, production_detail.*,item.item_id,item.item_name';

        var $order = array('production.production_id' => 'DESC');

        public function __construct()
        {
            parent::__construct();
            $this->load->database();
        }

        private function _main_query(){
            $this->db->select($this->column);
            $this->db->from($this->table);
            $this->db->join('production_detail', 'production_detail.production_no = '.$this->table.'.production_no', 'left');
            $this->db->join('item', 'item.item_id = production_detail.item_id_bahan', 'left');
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
            $query = "select * from production 
            where production_no = '".$id."'";
            $exc = $this->db->query($query);
            return $exc->row();
            
        }

        public function get_detail_by_id($id)
        {
            # code...
            $query = "select production_detail.*,a.item_name,b.item_name as bahan from production_detail 
                    join item a on a.item_id=production_detail.item_id_result
                    join item b on b.item_id=production_detail.item_id_bahan
					where production_detail.production_no = '".$id."'";
            $exc = $this->db->query($query);
            return $exc->result();
        }

        public function get_numrow_detail_by_id($id)
        {
            # code...
            $query = "select production_detail.*,item.item_name from production_detail 
                    join item on item.item_id=production_detail.item_id_result
					where production_detail.production_no = '".$id."'";
            $exc = $this->db->query($query);
            return $exc->num_rows();
        }

        public function get_balance($id)
        {
            # code...
            $query = "select production_detail.item_id_result,stock_item.item_id,stock_item.balance,item.konversi
                    from stock_item 
                    join production_detail on stock_item.item_id=production_detail.item_id_bahan 
                    JOIN production ON (production.production_no=production_detail.production_no AND production.loc_id=stock_item.loc_id)
                    join item on item.item_id=production_detail.item_id_bahan 
                    where production.production_no = '".$id."'";
            $exc = $this->db->query($query);
            return $exc->result();
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

        public function delete_by_id($id)
        {
            $this->db->delete('production_detail',array('production_no' => $id));
            return $this->db->delete($this->table,array('production_no' => $id));
        }

        public function get_harga_beli($item,$no)
        {
            # code...
            $this->db->select('production_detail.*,item.*,tmp_mst_satuan.satuan_name as satuan_beli');
            $this->db->from('production_detail');
            $this->db->join('item', 'item.item_id = production_detail.item_id', 'left');
            $this->db->join('tmp_mst_satuan', 'tmp_mst_satuan.satuan_id = item.satuan_beli_id', 'left');
            $this->db->where(array('production_detail.item_id' => $item,'production_detail.production_no' => $no));
            $query = $this->db->get();
            return $query->result();
            
        }

    
    }
    
    /* End of file ModelName.php */
    
?>