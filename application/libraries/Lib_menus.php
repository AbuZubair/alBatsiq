<?php 

Final class Lib_menus
{
    public function get_modules_by_user_id($username)
    {
        $CI =&get_instance();
        $getData = [];
        $db = $CI->load->database('default', TRUE);
        $qry = "SELECT app_program.* FROM user_role
                LEFT JOIN app_program ON app_program.program_id=user_role.program_id 
                WHERE app_program.program_type = 'Modul' and user_role.level_id IN (SELECT level_id FROM user WHERE username='".$username."') 
                order by app_program.counter ASC";
        $cek = $db->query($qry)->num_rows();        
        $array = $db->query($qry)->result_array();
    if($cek!=0){
        foreach ($array as $key => $value) {
            $id = $value['program_id'];
            if(!isset($result[$id])) $result[$id] = array();
            $result[$id] = $value['program_name']; 
        }

        foreach ($result as $k => $v) {
            $menu = $this->search_menu_by_group($k, $username);
           
            $arr = array(
                'program_id' => $k,
                'program_name' => $v,
                'menu' => $menu
                );
            $getData[] = $arr;
            
        }
        //echo"<pre>";print_r($getData);die;
      
    } 
        return $getData;
    
    }

    public function search_menu_by_group($program_id, $username){
        $CI =&get_instance();
        $getData = [];
        $db = $CI->load->database('default', TRUE);
        $sess = $CI->load->library('session');
        $qry = "SELECT app_program.* FROM user_role
                LEFT JOIN app_program ON app_program.program_id=user_role.program_id  
                WHERE user_role.level_id IN (SELECT level_id FROM user WHERE username='".$username."') AND app_program.program_type='Menu' AND app_program.program_parent_id='".$program_id."' order by counter ASC";
        $res = $db->query($qry)->result_array();
        
        foreach ($res as $key => $value)
        {
            $result[] = array(
                'program_id' => $value['program_id'],
                'program_name' => $value['program_name'],
                'program_type' => $value['program_type'],
                'program_parent_id' => $value['program_parent_id'],
                'link' => $value['link'],
                'counter' => $value['counter'],
                'is_active' => $value['is_active']
            );
        }

        foreach ($result as $k => $v) {
            $submenu = $this->search_submenu_by_group($v['program_id'], $username);
            $arr = array(
                'program_id' => $v['program_id'],
                'program_name' => $v['program_name'],
                'program_type' => $v['program_type'],
                'program_parent_id' => $v['program_parent_id'],
                'link' => $v['link'],
                'counter' => $v['counter'],
                'is_active' => $v['is_active'],
                'submenu' => $submenu
            );
           // $arr['submenu'] = $submenu;
            $getData[] = $arr;
        }

        return $getData;
    }

    public function search_submenu_by_group($program_id, $username){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $sess = $CI->load->library('session');
        $qry = "SELECT app_program.* FROM user_role
                LEFT JOIN app_program ON app_program.program_id=user_role.program_id  
                WHERE user_role.level_id IN (SELECT level_id FROM user WHERE username='".$username."') AND app_program.program_parent_id='".$program_id."' order by counter ASC";
        return $db->query($qry)->result();
    }
         
    public function get_menu_by_link($link)
    {
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $qry = "SELECT p1.*,p2.program_name AS parent_name FROM app_program p1
                LEFT JOIN app_program p2 on p2.program_id = p1.program_parent_id
                WHERE p1.link = '".$link."'";
    /*    $db->select('p1.*,');
        $db->from('app_program p1');
        $db->join('app_program p2', 'p2.program_id = p1.program_parent_id', 'left');
        $db->where("p1.link = '$link'"); */
        return $db->query($qry)->row();
    }

    public function get_modules_by_program_name($mod, $level)
        {
            $CI =&get_instance();
            $getData = [];
            $db = $CI->load->database('default', TRUE);
            $qry = "SELECT app_program.* FROM user_role
                    LEFT JOIN app_program ON app_program.program_id=user_role.program_id 
                    WHERE app_program.program_name = '".$mod."' and user_role.level_id =  '".$level."'";      
            $array = $db->query($qry)->result_array();
            
            foreach ($array as $key => $value) {
                $arr = array(
                    'program_id' => $value['program_id'],
                    'program_name' => $value['program_name'],
                    'link' => $value['link'],
                    );
                $getData[] = $arr;
            }
           // echo"<pre>";print_r($getData);die;
            return $getData;
        
        }

    function get_master_menus($level_id) {
    
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        $getData = array();
        $sess_menu = $this->get_all_menus($level_id);

        if($sess_menu){
            foreach ($sess_menu as $key => $value) {
                # code...
                $getData[] = $value;
            }
        }        

        return $getData;
        
    }

    public function get_all_menus($level_id){

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $getData = array();

        // get menu role
        $menu = $db->select('program_id,program_name,program_type,program_parent_id,link')->order_by('program_id','asc')->get_where('app_program', array('is_active'=>'Y', 'program_type' => 'Modul'));

        foreach ($menu->result_array() as $key => $value) {

            if( ($value['program_type'] == 'Modul') && ($value['link'] == '#') ){

                $res = $db->group_by('program_id')->get_where('app_program',array('program_parent_id'=>$value['program_id']))->result_array();
                //echo"<pre>";print_r($res);die;
                foreach ($res as $keys => $values){
                    $id = $values['program_id'];
                    if(!isset($result[$id])) $result[$id] = array();
                    $result[$id] = $values['program_name']; 
                }

                foreach ($result as $k => $v) {
                    $value['menu'] = $this->get_all_submenu($value['program_id'],$level_id);
                }
                

            }else{
                $value['menu'] = array();
            }

            
            $getData[] = $value;
        }

        //echo"<pre>";print_r($getData);die;
        return $getData;
    }

    public function get_all_submenu($program_id, $level_id)
    {
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $getData = [];


        $qry = "SELECT app_program.* FROM app_program
                WHERE program_parent_id IN ('".$program_id."') group by program_id";
        $res = $db->query($qry)->result_array();
        $cek = $db->query($qry)->num_rows();     
        //$res = $db->group_by('program_id')->get_where('app_program',array('program_parent_id'=>$program_id))->result_array();
        //echo"<pre>";print_r($res);die;
        
        if($cek==0){
            $arr = array();
            $getData[] = $arr;
        } else {
            foreach ($res as $key => $value)
            {
                $result[] = array(
                    'program_id' => $value['program_id'],
                    'program_name' => $value['program_name'],
                    'program_type' => $value['program_type'],
                    'program_parent_id' => $value['program_parent_id'],
                    'link' => $value['link'],
                    'counter' => $value['counter'],
                    'is_active' => $value['is_active']
                );
            }

            foreach ($result as $k => $v) {
                
                if(($v['program_type'] == 'Menu') && ($v['link']=='#')){
                    $submenu = $this->search_all_submenu($v['program_id'],$level_id);
                    $arr = array(
                        'program_id' => $v['program_id'],
                        'program_name' => $v['program_name'],
                        'program_type' => $v['program_type'],
                        'program_parent_id' => $v['program_parent_id'],
                        'link' => $v['link'],
                        'counter' => $v['counter'],
                        'is_active' => $v['is_active'],
                        'submenu' => $submenu
                    );
                } else {
                    $arr = array(
                        'program_id' => $v['program_id'],
                        'program_name' => $v['program_name'],
                        'program_type' => $v['program_type'],
                        'program_parent_id' => $v['program_parent_id'],
                        'link' => $v['link'],
                        'counter' => $v['counter'],
                        'is_active' => $v['is_active']
                    );
                }
            // $arr['submenu'] = $submenu;
                $getData[] = $arr;
            }
        } 

        return $getData;
    }

    public function search_all_submenu($program_id,$level_id){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $sess = $CI->load->library('session');
        $qry = "SELECT app_program.* FROM  app_program
                WHERE program_parent_id='".$program_id."' order by counter ASC";
        return $db->query($qry)->result();
    }

}
?>