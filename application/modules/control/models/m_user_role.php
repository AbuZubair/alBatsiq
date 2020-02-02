<?php

    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class M_user_role extends CI_Model {

        var $table = 'level_user';
        var $column = array('level_user.level_name','level_user.is_active');
        var $select = 'level_user.*';

        //var $order = array('level_user.level_name' => 'ASC');

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
                $this->db->where_in(''.$this->table.'.level_id',$id);
                $query = $this->db->get();
                return $query->result();
            }else{
                $this->db->where(''.$this->table.'.level_id',$id);
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
            $data = $this->db->where_in(''.$this->table.'.level_id', $id);
            return $this->db->update($this->table, array('is_deleted' => 'Y', 'is_active' => 'N'));
        }

        public function get_role_menu_by_level_id($id)
        {
            $this->db->select('user_role.role, app_program.program_name, app_program.program_id, app_program.program_type');
            $this->db->from('user_role');
            $this->db->join('app_program','app_program.program_id=user_role.program_id','left');
            $this->db->where('user_role.level_id', $id);
            $this->db->order_by('app_program.level_program', 'asc');
            $user_role = $this->db->get()->result();
            $html = '';
            foreach ($user_role as $key => $value) {
                # code...
                if($value->program_type !='Modul'){
                    $html .= '<li>'.$value->program_name.' ('.$value->role.') </li>';
                } else {
                    $html .= '<li>'.$value->program_name.'</li>';
                }
            }
            return $html;
        }

        public function get_checked_form($program_id, $level_id, $role=''){
            $this->db->from('user_role');
            $this->db->where(array('program_id' => $program_id, 'level_id'=> $level_id));
            $this->db->where('role LIKE "%'.$role.'%"');
            $exist = $this->db->get()->row();
            if($exist){
                return 'checked';
            }else{
                return false;
            }
        }
            
    
    }
    
    /* End of file ModelName.php */
    
?>