<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

final Class Graph_master {

    function get_graph($params) {
    	$data = array();
	
		$data = $this->setting_module($params);
				
		return $data;
		
    }

    function setting_module($params) {
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);

		/*total klaim berdasarkan nomor sep per tahun existing*/
		/*based query*/
		if($params['prefix']==1){
			$query = "select month(sales_date) as bulan,sum(charge_amount) as total from sales where year(sales_date)=".date('Y')." group by month(sales_date)";	
			$fields = array('Penjualan'=>'total');
			$title = '<span style="font-size:13.5px">Penjualan per tahun '.date('Y').'</span>';
			$subtitle = 'Source: AlBatsiq';
		}

		if($params['prefix']==2){
			$query = "SELECT item.item_name as name, sum(sales_detail.quantity) as total FROM sales_detail 
					LEFT JOIN item ON item.item_id=sales_detail.item_id 
					left join sales on sales.sales_no=sales_detail.sales_no
					WHERE YEAR(sales.sales_date)=".date('Y')." GROUP BY sales_detail.item_id ORDER BY sum(sales_detail.quantity) DESC LIMIT 10";	
			$fields = array('name' => 'total');
			$title = '<span style="font-size:13.5px">10 item yang paling banyak terjual</span>';
			$subtitle = 'Source: AlBatsiq';
		}

		if($params['prefix']==3){
			$query = "select month(transaction_date) as bulan,sum(charge_amount) as total from item_transaction where year(transaction_date)=2018 group by month(transaction_date) ORDER BY sum(charge_amount) DESC";	
			$fields = array('Bulan' => 'bulan', 'Total' => 'total');
			$title = '<span style="font-size:13.5px">Pembelian per tahun '.date('Y').'</span>';
			$subtitle = 'Source: AlBatsiq';
		}
		
		/*excecute query*/
		$data = $db->query($query)->result_array();
		/*find and set type chart*/
		$chart = $this->chartTypeData($params['TypeChart'], $fields, $params, $data);
		$chart_data = array(
			'title' 	=> $title,
			'subtitle' 	=> $subtitle,
			'xAxis' 	=> isset($chart['xAxis'])?$chart['xAxis']:'',
			'series' 	=> isset($chart['series'])?$chart['series']:'',
			);

		return $chart_data;
		
    }


    public function chartTypeData($style, $fields, $params, $data){

    	switch ($style) {
    		case 'column':
    			/*lanjutkan buat function jika ada style yang lain*/
    			if ($params['style']==1) {
    				return $this->ColumnStyleOneData($fields, $params, $data);
    			}
    			break;
    		case 'pie':
    			if ($params['style']==1) {
    				return $this->PieStyleOneData($fields, $params, $data);
    			}
    			break;
    		case 'line':
    			if ($params['style']==1) {
    				return $this->LineStyleOneData($fields, $params, $data);
    			}
    			break;
    		case 'table':
    			if ($params['style']==1) {
    				return $this->TableStyleOneData($fields, $params, $data);
    			}
    			break;
    		
    		default:
    			# code...
    			break;
    	}
    }
    public function ColumnStyleOneData($fields, $params, $data){
    	$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
    	
        $getData = array();
		foreach($data as $key=>$row){
			foreach ($fields as $kf => $vf) {
				$getData[$kf][$row['bulan']-1] = (int)$row[$vf];
			}
		}
		
		for ($i=0; $i < 12; $i++) { 
			foreach ($fields as $kf2 => $vf2) {
				if(!isset($getData[$kf2][$i])){
					$getData[$kf2][$i] = 0;
				}
				ksort($getData[$kf2]);
			}
			$categories[] = $CI->tanggal->getBulan($i+1);
			
		}

		foreach ($getData as $k => $r) {
			$series[] = array('name' => $k, 'data' => $r );
		}
		
		$chart_data = array(
			'xAxis' 	=> array('categories' => $categories),
			'series' 	=> $series,
		);
		return $chart_data;
    }

    public function PieStyleOneData($fields, $params, $data){
    	$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
    	
        $getData = array();
		foreach($data as $key=>$row){
			foreach ($fields as $kf => $vf) {
				$getData[$row[$kf]][] = (int)$row[$vf];
			}
		}

		foreach ($getData as $k => $r) {
			$series[] = array($k, array_sum($r));
		}
		$chart_data = array(
			'series' 	=> $series,
		);
		return $chart_data;
    }

    public function LineStyleOneData($fields, $params, $data){
    	$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
    	
        $getData = array();
		foreach($data as $key=>$row){
			foreach ($fields as $kf => $vf) {
				$getData[$kf][$row['bulan']-1] = (int)$row[$vf];
			}
		}
		
		for ($i=0; $i < 12; $i++) { 
			foreach ($fields as $kf2 => $vf2) {
				if(!isset($getData[$kf2][$i])){
					$getData[$kf2][$i] = 0;
				}
				ksort($getData[$kf2]);
			}
			$categories[] = $CI->tanggal->getBulan($i+1);
			
		}

		foreach ($getData as $k => $r) {
			$series[] = array('name' => $k, 'data' => $r );
		}
		
		$chart_data = array(
			'xAxis' 	=> array('categories' => $categories),
			'series' 	=> $series,
		);
		return $chart_data;
    }

    public function TableStyleOneData($fields, $params, $data){
    	$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
    	
        $html = '';
        $html .='<table class="table table-bordered table-hover"><thead>
			        <tr><th width="20px" class="center">No</th>';
		        foreach ($fields as $kf => $vf) {
		        	$html .= '<th>'.ucfirst($kf).'</th>';
		        }
      	$html .='</thead>';
      	$html .='<tbody>';
		$no=0;
		//print_r($data);die;
		foreach ($data as $key => $value) { $no++;
			$value['bulan'] = $CI->tanggal->getBulan($value['bulan']);
			$value['total'] = number_format($value['total'],2);
      		$html .='<tr>';
	      	$html .='<td align="center">'.$no.'</td>';
	      	foreach ($fields as $keyf => $valuef) {
	      		$html .='<td>'.ucfirst(strtolower($value[$valuef])).'</td>';
	      	}
	      	$html .='</tr>';
      	}
      	
      	$html .='</tbody>';
      	$html .='</table>';

        $chart_data = array(
			'xAxis' 	=> 0,
			'series' 	=> $html,
		);
		return $chart_data;
    }
	
}

?> 