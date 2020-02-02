<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class M_bank_account extends CI_Model {

        var $table = 'tmp_bank_account';
        var $column = array('tmp_bank_account.bank_name','tmp_bank_account.bank_number','tmp_bank_account.is_active');
        var $select = 'tmp_bank_account.*';

        var $order = array('tmp_bank_account.ba_id' => 'ASC');

        public function __construct()
        {
            parent::__construct();
            $this->load->database();
        }

        private function _main_query(){
            $this->db->select($this->select);
            $this->db->from($this->table);
            $this->db->where($this->table.".is_deleted != 'Y'");
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
                $this->db->where_in(''.$this->table.'.ba_id',$id);
                $query = $this->db->get();
                return $query->result();
            }else{
                $this->db->where(''.$this->table.'.ba_id',$id);
                $query = $this->db->get();
                return $query->row();
            }
            
        }

        public function save($data)
        {
            $this->db->insert($this->table, $data);
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
            $data = $this->db->where_in(''.$this->table.'.ba_id', $id);
            return $this->db->update($this->table, array('is_deleted' => 'Y', 'is_active' => 'N'));
        }

        public function get_all_satuan()
        {
            $this->_main_query();  
            $query = $this->db->get();
            return $query->result();          
        }
    
    }
    
    /* End of file ModelName.php */
    
?>