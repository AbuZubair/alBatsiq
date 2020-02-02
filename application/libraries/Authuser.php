<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

final Class Authuser {

    function filtering_data_by_level_user($table, $username){

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $CI->load->library('session');

        $query = "SELECT level FROM user WHERE USERNAME='".$username."'";
        $level_id = $db->query($query)->result();
        
        if (in_array($level_id, array(3))) {
            return $db->where($table.'.created_by', $CI->session->userdata('user')->username);
        }else{
            return false;
        }
    }

    function show_button($link, $code, $level='', $style=''){

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $CI->load->library('session');

        /*check existing*/
        $query = "SELECT role
                    FROM user_role
                    WHERE program_id = (SELECT program_id FROM app_program WHERE link='$link') AND level_id IN (SELECT level_id FROM user WHERE USERNAME='".$CI->session->userdata('username')."')"; 
        $result = $db->query($query);
        if($result->num_rows() > 0){
            $role = $result->row()->role;
            $str_to_array = explode(',', $role); 
            /*ubah link menjad*/
            $exp_code = explode('/', $style);
            $ori_code = (string)$exp_code[0];
            $flag = isset($exp_code[1])?$exp_code[1]:'';
            //print_r($flag);die;
            $repl_link = str_replace("?flag=$flag",'',$link);

            /*switch code to get button*/
            return $this->switch_to_get_btn($str_to_array, $repl_link, $code, $level, $style);
        }else{
            return false;
        }
    }

    function switch_to_get_btn($array, $link, $code, $id, $style=''){

        if(in_array($code, $array)){
            /*get button action*/
            switch ($code) {

                case 'C':
                    $btn = $this->create_button($link, $id, $code.$style);
                    break;
                case 'R':
                    $btn = $this->read_button($link, $id, $code.$style);
                    break;
                case 'U':
                    $btn = $this->update_button($link, $id, $code.$style);
                    break;
                case 'D':
                    $btn = $this->delete_button($link, $id, $code.$style);
                    break;
                
                default:
                    # code...
                    $btn = '';
                    break;
            }
            return $btn;
        }
    }

    function create_button($link, $id, $code_style){
        $exp_code = explode('/', $code_style);
        $code = (string)$exp_code[0];
        $flag = isset($exp_code[1])?$exp_code[1]:'';
        //print_r($code_style);die;
        switch ($code) {

            /*style for create*/
            case 'C':
                # code...
                $btn = '<button class="btn btn-white btn-m btn-info btn-bold" onclick="getMenu('."'".$link.'/form'."'".')"><i class="ace-icon glyphicon glyphicon-plus bigger-50 blue"></i>New</button>';
                break;
            case 'C1':
                # code...
                $btn = '<a href="#" class="btn btn-m btn-primary" onclick="getMenu('."'".$link.'/form'."'".')"><i class="ace-icon glyphicon glyphicon-plus bigger-50"></i>New</a>';
                break;

            case 'C11':
                # code...
                $btn = '<button class="btn btn-m btn-primary" onclick="getMenuTabs('."'".$link.'/form?pgd_id='.$id.''."'".')"><i class="ace-icon glyphicon glyphicon-plus bigger-50"></i>New</button>';
                break;

            case 'C2':
                # code...
                $btn = '<button class="btn btn-m btn-primary" onclick="getMenu('."'".$link.'/form'."'".')"><i class="ace-icon glyphicon glyphicon-plus bigger-50"></i></button>';
                break;
            case 'C3':
                # code...
                $btn = '<button class="btn btn-white btn-m btn-info btn-bold" onclick="getMenu('."'".$link.'/form'."'".')"><i class="ace-icon glyphicon glyphicon-plus bigger-50 blue"></i></button>';
                break;

            case 'C4':
                # code...
                $btn = '<a href="#" onclick="getMenu('."'".$link.'/form'."'".')" class="tooltip-success" data-rel="tooltip" title="Add">
                                        <span class="blue">
                                            <i class="ace-icon glyphicon glyphicon-plus bigger-120"></i>
                                        </span>
                                    </a>';
                break;

            case 'C6':
                # code...
                $btn = '<a href="#" onclick="getMenu('."'".$link.'/form'."'".')"title="Add">Add</a>';
                break;

            case 'CC1':
                # code...
                $btn = '<button class="btn btn-xs btn-primary" onclick="getMenu('."'".$link.''."'".')"><i class="ace-icon glyphicon glyphicon-plus bigger-50"></i></button>';
                break;

            default:
                # code...
                $btn = '';
                break;
        }
        return $btn;
    }

    function read_button($link, $id, $code_style){
        $exp_code = explode('/', $code_style);
        $code = (string)$exp_code[0];
        $flag = isset($exp_code[1])?$exp_code[1]:'';
        //print_r($code_style);die;
        switch ($code) {

            /*style button for read action*/
            case 'R':
                # code...
                $btn = '<button class="btn btn-white btn-xs btn-info btn-bold" onclick="getMenu('."'".$link.'/show/'.$id.''."'".')"><i class="ace-icon fa fa-eye bigger-50 blue"></i>View</button>';
                break;

            case 'R1':
                # code...
                $btn = '<button class="btn btn-xs btn-info" onclick="getMenu('."'".$link.'/show/'.$id.''."'".')"><i class="ace-icon fa fa-eye bigger-50"></i>View</button>';
                break;
            case 'R2':
                # code...
                $btn = '<button class="btn btn-xs btn-info" onclick="getMenu('."'".$link.'/show/'.$id.''."'".')"><i class="ace-icon fa fa-eye bigger-50"></i>View</button>';
                break;
            case 'R21':
                # code...
                $btn = '<button class="btn btn-xs btn-info" onclick="getMenuTabs('."'".$link.'/show/'.$id.''."'".')"><i class="ace-icon fa fa-eye bigger-50"></i></button>';
                break;
            case 'R3':
                # code...
                $btn = '<button class="btn btn-white btn-xs btn-info btn-bold" onclick="getMenu('."'".$link.'/show/'.$id.''."'".')"><i class="ace-icon fa fa-eye bigger-50 blue"></i></button>';
                break;

            case 'R4':
                # code...
                $btn = '<a href="#" onclick="getMenu('."'".$link.'/form/'.$id.''."'".')" class="tooltip-success" data-rel="tooltip" title="View">
                                        <span class="info">
                                            <i class="ace-icon fa fa-eye bigger-120"></i>
                                        </span>
                                    </a>';
                break;

            case 'R6':
                # code...
                $btn = '<a href="#" onclick="getMenu('."'".$link.'/show/'.$id.''."'".')">Read</a>';
                break;

            case 'RC1':
                # code...
                $btn = '<button class="btn btn-xs btn-info" onclick="getMenu('."'".$link.'/show?id='.$id.'&flag='.$flag.''."'".')"><i class="ace-icon fa fa-eye bigger-50"></i></button>';
                break;

            default:
                # code...
                $btn = '';
                break;
        }
        return $btn;
    }

    function update_button($link, $id, $code_style){
        $exp_code = explode('/', $code_style);
        $code = (string)$exp_code[0];
        $flag = isset($exp_code[1])?$exp_code[1]:'';
        //print_r($code_style);die;
        switch ($code) {

            /*style button for read action*/
            case 'U':
                # code...
                $btn = '<button class="btn btn-white btn-xs btn-success btn-bold" onclick="getMenu('."'".$link.'/form/'.$id.''."'".')"><i class="ace-icon fa fa-pencil bigger-50 blue"></i>Edit</button>';
                break;

            case 'U1':
                # code...
                if($link=='control/user'){
                    $btn = '<button class="btn btn-xs btn-success" onclick="getMenu('."'".$link.'/form/'.$id.''."'".')"><i class="ace-icon fa fa-pencil bigger-50"></i>Edit</button>
                        <button class="btn btn-white btn-xs btn-warning btn-bold" onclick="getMenu('."'".$link.'/reset_pwd/'.$id.''."'".')"><i class="ace-icon fa fa-pencil bigger-50 blue"></i>Reset Password</button>';
                }else{
                    $btn = '<button class="btn btn-xs btn-success" onclick="getMenu('."'".$link.'/form/'.$id.''."'".')"><i class="ace-icon fa fa-pencil bigger-50"></i>Edit</button>';
                }
                break;
            case 'U2':
                # code...
                $btn = '<button class="btn btn-xs btn-success" onclick="getMenu('."'".$link.'/form/'.$id.''."'".')"><i class="ace-icon fa fa-edit bigger-50"></i></button>';
                break;
            case 'U21':
                # code...
                $btn = '<button class="btn btn-xs btn-success" onclick="getMenuTabs('."'".$link.'/form/'.$id.''."'".')"><i class="ace-icon fa fa-pencil bigger-50"></i></button>';
                break;
            case 'U3':
                # code...
                $btn = '<button class="btn btn-xs btn-success" onclick="getMenu('."'".$link.'/form/'.$id.''."'".')"><i class="ace-icon fa fa-pencil bigger-50"></i>Stock Opname</button>';
                break;

             case 'U4':
                # code...
                $btn = '<a href="#" onclick="getMenu('."'".$link.'/form/'.$id.''."'".')" class="tooltip-success" data-rel="tooltip" title="Update">
                                        <span class="green">
                                            <i class="ace-icon fa fa-pencil bigger-120"></i>
                                        </span>
                                    </a>';
                break;

            case 'U6':
                # code...
                $btn = '<a href="#" onclick="getMenu('."'".$link.'/form/'.$id.''."'".')" title="Update">Update</a>';
                break;

            case 'UC1':
                # code...
                $btn = '<button class="btn btn-xs btn-success" onclick="getMenu('."'".$link.'/form?id='.$id.'&flag='.$flag."'".')"><i class="ace-icon fa fa-edit bigger-50"></i></button>';
                break;

            default:
                # code...
                $btn = '';
                break;
        }
        return $btn;
    }

    function delete_button($link, $id, $code_style){
        $exp_code = explode('/', $code_style);
        $code = (string)$exp_code[0];
        $flag = isset($exp_code[1])?$exp_code[1]:'';
        //print_r($code_style);die;
        switch ($code) {

            /*style button for delete action*/
            case 'D':
                # code...
                $btn = '<a href="#" class="btn btn-white btn-m btn-danger btn-bold" onclick="delete_data('.$id.')"><i class="ace-icon fa fa-trash-o bigger-50 blue"></i>Delete</a>';
                break;

            case 'D1':
                # code...
                $btn = '<a href="#" class="btn btn-m btn-danger" onclick="delete_data('.$id.')"><i class="ace-icon fa fa-trash-o bigger-50"></i>Delete</a>';
                break;
            case 'D2':
                # code...
                $btn = '<button class="btn btn-xs btn-danger" onclick="delete_data('.$id.')"><i class="ace-icon fa fa-times bigger-50"></i>Delete</button>';
                break;

            case 'D3':
                # code...
                $btn = '<a href="#" class="btn btn-white btn-m btn-danger btn-bold" onclick="delete_data('.$id.')"><i class="ace-icon fa fa-trash-o bigger-50 blue"></i></a>';
                break;
            
            case 'D4':
                # code...
                $btn = '<a href="#" onclick="delete_data('.$id.')" class="tooltip-success" data-rel="tooltip" title="Delete">
                                        <span class="red">
                                            <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                        </span>
                                    </a>';
                break;

            case 'D5':
                # code...
                $btn = '<a href="#" class="btn btn-m btn-danger" id="button_delete"><i class="ace-icon fa fa-trash-o bigger-50"></i>Delete selected items</a>';
                break;


            case 'D6':
                # code...
                $btn = '<a href="#" onclick="delete_data('.$id.')">Delete</a>';
                break;

            case 'DC1':
                # code...
                $btn = '<button class="btn btn-xs btn-danger" onclick="delete_data('.$id.')"><i class="ace-icon fa fa-times bigger-50"></i></button>';
                break;

            default:
                # code...
                $btn = '';
                break;
        }
        return $btn;
    }

    function get_user_description(){

        $this->db->from('m_user');
        $this->db->join('m_role', 'm_role.id_role=m_user.id_role','left');
        $this->db->where(array('id_user'=>$this->session->userdata('data_user')->id_user));
        $value = $this->db->get()->row();
        
        $field = 'Anda login sebagai, ';
        if( $this->session->userdata('data_user')->id_role == 5 ){
            $field .= '<strong><i> Puskesmas : '.ucwords($value->nama_puskesmas_kab).' || Kab/Kota : '.ucwords($value->nama_kabupaten).' || Provinsi : '.ucwords($value->nama_provinsi).'</strong></i>';
        }elseif( $this->session->userdata('data_user')->id_role == 4 ){
            $field .= '<strong><i> Kab/Kota : '.ucwords($value->nama_kabupaten).' || Provinsi : '.ucwords($value->nama_provinsi).'</strong></i>';
        }elseif( $this->session->userdata('data_user')->id_role == 3 ){
            $field .= '<strong><i> Provinsi : '.ucwords($value->nama_provinsi).'</strong></i>';
        }else{
            $field .= '<strong><i> '.ucwords($value->role_name).'</strong></i>';
        }

        return $field;
    }

	

}

?> 