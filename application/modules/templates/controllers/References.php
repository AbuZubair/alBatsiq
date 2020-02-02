<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class References extends MX_Controller {

    public function __construct()
	{
		parent::__construct();
	}

    public function getDataItem($item_id = '')
    {
        # code...
        $this->db->select('item.item_name,a.satuan_id as satuan_beli_id, a.satuan_name as satuan_beli,item.harga_jual,b.satuan_id as satuan_jual_id, b.satuan_name as satuan_jual,item.konversi');
        $this->db->from('item');
        $this->db->join('tmp_mst_satuan a','a.satuan_id = item.satuan_beli_id', 'left');
        $this->db->join('tmp_mst_satuan b','b.satuan_id = item.satuan_jual_id', 'left');
        $this->db->where("item.item_id = '$item_id'");
        $query = $this->db->get();
        echo json_encode($query->result());
    }

    public function getItem()
	{
		$result = $this->getItemResult($_POST['keyword']);
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->item_id.' : '.$value->item_name;
		}
		echo json_encode($arrResult);
	}
	public function getItemResult($keyword)
	{
		# code...
		
		$query = "select item_id,item_name
					from item 
					where is_active='Y' and is_deleted='N' and item_name like '%".$keyword."%' or item_id like '%".$keyword."%'";
        $exc = $this->db->query($query);
        return $exc->result();
	}

	public function getItemSalesAvailable()
	{
		
		$result = $this->getItemSalesAvailableResult($_POST['data']);
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->item_id.' : '.$value->item_name.' ( balance: '.$value->balance.' ) ' ;
		}
		echo json_encode($arrResult);
	}
	public function getItemSalesAvailableResult($data)
	{
		# code...
		
		$query = "select a.item_id,a.balance,b.item_name
					from stock_item a 
					left join item b on b.item_id=a.item_id
					where b.is_active='Y' and b.is_deleted='N' and b.sales_available='Y' and a.loc_id = '".$data[1]."' and b.item_name like '%".$data[0]."%' or a.item_id like '%".$data[0]."%'
					group by item_id";
        $exc = $this->db->query($query);
        return $exc->result();
	}

	public function getItemSales()
	{
		$result = $this->getItemSalesResult($_POST['data']);
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->item_id.' : '.$value->item_name;
		}
		echo json_encode($arrResult);
	}
	public function getItemSalesResult($data)
	{
		# code...
		//$item = join(",",json_decode($data[1]));
		$item = implode("','", json_decode($data[1]));
		$query = "select item_id,item_name
				from item 
				where is_active='Y' and is_deleted='N' and item_id not in ('".$item."') and (item_name like '%".$data[0]."%' or item_id like '%".$data[0]."%') ";
        $exc = $this->db->query($query);
        return $exc->result();
	}

	public function getItemByLoc()
	{
		//print_r($_POST['data']);die;
		$result = $this->getItemByLocResult($_POST['data']);
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->item_id.' : '.$value->item_name.' ( balance: '.$value->balance.' ) ' ;
		}
		echo json_encode($arrResult);
	}
	public function getItemByLocResult($data)
	{
		# code...
		$query = "select a.item_id,a.balance,b.item_name
					from stock_item a 
					left join item b on b.item_id=a.item_id
					where a.loc_id = '".$data[1]."' and b. item_name like '%".$data[0]."%' or b.item_id like '%".$data[0]."%' group by item_id";
        $exc = $this->db->query($query);
        return $exc->result();
	}

	
	public function getPO()
	{
		$query = "select distinct * from item_transaction
					where transaction_code='001' and YEAR(transaction_date)=".date('Y')." and MONTH(transaction_date)=".date('m')." and status <> 3 order by item_transaction_id desc";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getPOR($loc)
	{
		$query = "select distinct * from item_transaction
					where transaction_code='002' and YEAR(transaction_date)=".date('Y')." and MONTH(transaction_date)=".date('m')." and status <> 3 and to_loc_id = '".$loc."' order by item_transaction_id desc";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getSales($loc)
	{
		$query = "select distinct * from sales
					where sales_code='700' and YEAR(sales_date)=".date('Y')." and MONTH(sales_date)=".date('m')." and status <> 3 and loc_id = '".$loc."' order by sales_id desc";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getPObyTgl($from,$to)
	{
		$query = "select * from item_transaction
					where transaction_code='001' and transaction_date between '".$from."' and '".$to."' ";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getPORbyTgl($from,$to)
	{
		$query = "select * from item_transaction
					where transaction_code='002' and transaction_date between '".$from."' and '".$to."' ";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getSalesbyTgl($from,$to)
	{
		$query = "select * from sales
					where sales_code='700' and sales_date between '".$from."' and '".$to."' ";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getPODetail($po)
	{
		$query = "Select item_transaction_detail.*, item.item_name
				from item_transaction_detail 
				left join item on item_transaction_detail.item_id = item.item_id 
				where item_transaction_detail.transaction_no='".$po."'
				AND item_transaction_detail.item_id NOT IN ( select item_id from item_transaction_detail where reference_no = '".$po."' )";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getPORDetail($por)
	{
		$query = "Select item_transaction_detail.*, item.item_name, stock_item.balance
				from item_transaction_detail 
				left join item on item_transaction_detail.item_id = item.item_id 
				left join stock_item on item_transaction_detail.item_id = stock_item.item_id 
				where item_transaction_detail.transaction_no='".$por."'
				AND item_transaction_detail.item_id NOT IN ( select item_id from item_transaction_detail where reference_no = '".$por."' )
				group by item_id";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getSalesDetail($sales)
	{
		$query = "Select sales_detail.*, item.item_name, stock_item.balance
				from sales_detail 
				left join item on sales_detail.item_id = item.item_id 
				left join stock_item on sales_detail.item_id = stock_item.item_id 
				where sales_detail.sales_no='".$sales."'
				AND sales_detail.item_id NOT IN ( select item_id from sales_detail where reference_no = '".$sales."' )
				group by item_id";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getLocUnit($loc_id)
	{
		# code...
		
		$query = "select loc_id,unit_name
					from unit 
					where is_active='Y' and is_deleted='N' and loc_id <> '".$loc_id."' ";
		$exc = $this->db->query($query);
		//print_r($exc);
        echo json_encode($exc->result());
	}

	public function getItemProduction($loc_id)
	{
		# code...
		
		$query = "select a.item_id,b.item_name
					from stock_item a
					left join item b on b.item_id=a.item_id
					left join tmp_mst_item_group c on c.group_item_id = b.group_item_id
					where a.loc_id = '".$loc_id."' and c.is_production='Y' group by a.item_id ";
		$exc = $this->db->query($query);
		//print_r($this->db->last_query());
        echo json_encode($exc->result());
	}

	public function getItemProductionDetail($item,$loc_id)
	{
		# code...
		
		$query = "select a.item_id,a.balance,c.satuan_name,b.konversi
					from stock_item a
					left join item b on b.item_id=a.item_id
					left join tmp_mst_satuan c on c.satuan_id=b.satuan_jual_id
					where a.loc_id = '".$loc_id."' and a.item_id = '".$item."' group by a.item_id ";
		$exc = $this->db->query($query);
		//print_r($this->db->last_query());
        echo json_encode($exc->result());
	}

	
}
?>