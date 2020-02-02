<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

final Class Master {

    function get_tahun_pengaduan($nid='',$name, $id,$class='',$required='',$inline='') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);

		$data = $db->query('SELECT YEAR(pgd_tanggal) AS years
							FROM mc_pengaduan
							WHERE YEAR(pgd_tanggal) != 0
							GROUP BY YEAR(pgd_tanggal)')->result_array();

		$selected = $nid?'':'selected';
		$readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		$field.=$fieldset.'
		<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
			<option value="0" '.$selected.'> - Silahkan pilih - </option>';

				foreach($data as $row){
					$sel = $nid==$row['years']?'selected':'';
					$field.='<option value="'.$row['years'].'" '.$sel.' >'.strtoupper($row['years']).'</option>';
				}	
			
		$field.='
		</select>
		'.$fieldsetend;
		
		return $field;
		
    }

    function get_tahun($nid='',$name,$id,$class='',$required='',$inline='') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$year = array();
		$now = date('Y');
		for ($i=$now-2; $i < $now+2 ; $i++) { 
			$year[] = $i;
		}
		$data = $year;

		$selected = $nid?'':'selected';
		$readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		$field.=$fieldset.'
		<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
			<option value="0" '.$selected.'> - Silahkan pilih - </option>';

				foreach($data as $row){
					$sel = $nid==$row?'selected':'';
					$field.='<option value="'.$row.'" '.$sel.' >'.strtoupper($row).'</option>';
				}	
			
		$field.='
		</select>
		'.$fieldsetend;
		
		return $field;
		
    }

    function get_bulan($nid='',$name,$id,$class='',$required='',$inline='') {
		//print_r($nid);die;
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$year = array();
		for ($i=1; $i < 13 ; $i++) { 
			$list = array(
				'key' => $i,
				'value' => Tanggal::getBulan($i),
				);
			$year[] = $list;
		}
		$data = $year;

		$selected = $nid?'':'selected';
		$readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		$field.=$fieldset.'
		<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
			<option value="0" '.$selected.'> - Silahkan pilih - </option>';

				foreach($data as $row){
					$sel = $nid==$row['key']?'selected':'';
					$field.='<option value="'.$row['key'].'" '.$sel.' >'.strtoupper($row['value']).'</option>';
				}	
			
		$field.='
		</select>
		'.$fieldsetend;
		
		return $field;
		
    }

    function custom_selection($custom=array(), $nid='',$read='',$name,$id,$class='',$required='',$inline='') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		
        $data = $db->where($custom['where'])->get($custom['table'])->result_array();
		
		$selected = $nid?'':'selected';
		$readonly = $read?'readonly':'';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		$field.='
		<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
			<option value="" '.$selected.'> - Silahkan pilih - </option>';

				foreach($data as $row){
					$sel = $nid==$row[$custom['id']]?'selected':'';
					$field.='<option value="'.$row[$custom['id']].'" '.$sel.' >'.strtoupper($row[$custom['name']]).'</option>';
				}	
			
		$field.='
		</select>
		';
		
		return $field;
		
	}
	
	function custom_selection_with_join($custom=array(), $nid='',$name,$id,$class='',$required='',$inline='') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		
		foreach ($custom['join'] as $k => $v) {
			$db->join($v['relation_table'],$custom['table'].'.'.$v['relation_ref_id'].'='.$v['relation_table'].'.'.$v['relation_id'],$v['join_type']);
		}
        $db->where($custom['where']);
		$data = $db->get($custom['table'])->result_array();

		$selected = $nid?'':'selected';
		$readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		$field.='
		<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
			<option value="" '.$selected.'> - Silahkan pilih - </option>';

				foreach($data as $row){
					$sel = $nid==$row[$custom['id']]?'selected':'';
					$field.='<option value="'.$row[$custom['id']].'" '.$sel.' >'.strtoupper($row[$custom['name']]).'</option>';
				}	
			
		$field.='
		</select>
		';
		
		return $field;
		
    }

    function get_ttd($custom=array(), $nid='',$name,$id,$class='',$required='',$inline='') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		
        $data = $db->where('uk_level <=2')->where($custom['where'])->get($custom['table'])->result_array();
		
		$selected = $nid?'':'selected';
		$readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		$field.='
		<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
			<option value="" '.$selected.'> - Silahkan pilih - </option>';

				foreach($data as $row){
					$sel = $nid==$row[$custom['id']]?'selected':'';
					$field.='<option value="'.$row[$custom['id']].'" '.$sel.' >'.strtoupper($row[$custom['name']]).'</option>';
				}	
			
		$field.='
		</select>
		';
		
		return $field;
		
    }

    function on_change_custom_selection($custom=array(), $nid='',$name,$id,$class='',$required='',$inline='') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		
		if($nid != ''){
        	$data = $db->where($custom['id'], $nid)
        			   ->where($custom['where'])
        			   ->get($custom['table'])->result_array();
		}else{
			$data = array();
		}
		
		$selected = $nid?'':'selected';
		$readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		$field.='
		<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
			<option value="" '.$selected.'> - Silahkan pilih - </option>';

				foreach($data as $row){
					$sel = $nid==$row[$custom['id']]?'selected':'';
					$field.='<option value="'.$row[$custom['id']].'" '.$sel.' >'.strtoupper($row[$custom['name']]).'</option>';
				}	
			
		$field.='
		</select>
		';
		
		return $field;
		
    }

    function get_change($params=array(), $nid='',$name,$id,$class='',$required='',$inline='') {
        
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        if($nid!=''){
            $data = $db->where($params['id'], $nid)->get($params['table'])->result_array();
        }else{
            $data = array();
        }

        $selected = $nid?'':'selected';
        $readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
        
        $starsign = $required?'*':'';
        
        $fieldset = $inline?'':'<fieldset>';
        $fieldsetend = $inline?'':'</fieldset>';
        
        $field='';
        $field.=$fieldset.'
        <select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
            <option value="0" '.$selected.'> - Silahkan pilih - </option>';
                foreach($data as $row){
                    $sel = $nid==$row[$params['id']]?'selected':'';
                    $field.='<option value="'.$row[$params['id']].'" '.$sel.' >'.strtoupper($row[$params['name']]).'</option>';
                }
                
            
        $field.='
        </select>
        '.$fieldsetend;
        return $field;
        
    }
    
    function get_custom_data($params) {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$data = $db->where($params['where'])->get($params['table'])->result_array();
		
		return $data;
		
    }

    function get_history_notification() {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$history = $db->where(array('to'=>$CI->session->userdata('user')->pg_id))
				   ->join('v_pegawai', 'v_pegawai.pg_id=notification.from','left')
				   ->order_by('id', 'DESC')
				   ->get('notification');
		$total_unread = $db->where(array('to'=>$CI->session->userdata('user')->pg_id,'is_read'=>'N'))->join('v_pegawai', 'v_pegawai.pg_id=notification.from','left')->get('notification');
		$data = array(
			'history' => $history,
			'total_unread' => $total_unread->num_rows(),
			);
		return $data;
		
    }

    function get_content_dashboard() {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);

		/*total surat masuk*/
		if( in_array($CI->session->userdata('user')->role_id, array(1,2) ) ){
			$db->where('surat_flag', 1);
		}else{
			$db->where(array('surat_flag'=>1,'surat_tujuan_pg_id'=>$CI->session->userdata('user')->role_id));
		}
		$db->from('surat');
		$db->where('is_active', 'Y');
		$surat_masuk = $db->get(); 
		$data['total_surat_masuk'] = $surat_masuk->num_rows();

		/*total surat masuk*/
		if( in_array($CI->session->userdata('user')->role_id, array(1,2) ) ){
			$db->where('surat_flag', 2);
		}else{
			$db->where(array('surat_flag'=>2,'surat_tujuan_pg_id'=>$CI->session->userdata('user')->pg_id));
		}
		$db->from('surat');
		$db->where('is_active', 'Y');
		$surat_keluar = $db->get(); 
		$data['total_surat_keluar'] = $surat_keluar->num_rows();

		/*total disposisi masuk*/
		if( in_array($CI->session->userdata('user')->role_id, array(1) ) ){
			$db->where('dt_id is not null');
		}else{
			$db->where(array('dt_tujuan_pg_id'=>$CI->session->userdata('user')->pg_id));
		}
		$db->from('disposisi_tujuan');
		$disposisi = $db->get();
		$data['total_disposisi_masuk'] = $disposisi->num_rows();

		/*total disposisi keluar*/
		if( in_array($CI->session->userdata('user')->role_id, array(1) ) ){
			$db->where('disposisi_id is not null');
		}else{
			$db->where(array('created_by'=>$CI->session->userdata('user')->pg_id));
		}
		$db->from('disposisi');
		$db->where('is_active', 'Y');
		$disposisi_keluar = $db->get(); 
		$data['total_disposisi_keluar'] = $disposisi_keluar->num_rows();


		return $data;
		
    }

    function get_graph_data() {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$qry = 'SELECT web_modul.`wm_name`, COUNT(web_posting.wm_id)AS total
FROM web_posting
LEFT JOIN web_modul ON web_modul.`wm_id`=web_posting.`wm_id`
GROUP BY web_posting.`wm_id`';
		$sql = $db->query($qry)->result();
		
		return $sql;
		
    }

    function get_graph_polling() {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$qry = 'SELECT wpl_question, (SELECT GROUP_CONCAT(CONCAT(wpl_option),"=",counter)AS hasil
		FROM web_polling_answer
		WHERE wpl_id=web_polling.`wpl_id`) AS hasil
		FROM web_polling
		WHERE is_active="Y"
		ORDER BY wpl_tanggal DESC
		LIMIT 1';
		$sql = $db->query($qry)->row();
		
		return $sql;
		
    }
	
	public function getMaxTransNo($table,$where,$prefix)
	{
		# code...
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$qry = $db->get_where($table,$where)->num_rows();
		$max_num = $qry + 1;
		$num = sprintf("%04d", $max_num);
		$no = ''.$prefix.date('Y').date('m').$num.'';
		return $no;
	}
	
}

?> 