<?php
require_once('../connection/db.php');

$receiptID=$_POST['refID'];

//detail
$query_rsDetails = "select drv_d.id AS detail_id, drv_h.trano, drv_h.tratype, drv_h.tradate, drv_h.totamount from (SELECT id, trano FROM tbl_gl_rev_audit_details WHERE tbl_gl_rev_audit_id=? AND log_cancel=0) AS drv_d INNER JOIN (SELECT DISTINCT trano, tratype, tradate, totamount FROM tbl_account_transaction WHERE totamount>0) AS drv_h ON drv_d.trano=drv_h.trano";

$stmtDetails = $conn->prepare($query_rsDetails);
$stmtDetails->bind_param('s', $receiptID);
$stmtDetails->execute();
$stmtDetails->store_result();
$totalRows_rsDetails = $stmtDetails->num_rows;
$stmtDetails->bind_result($detail_id, $trano, $tratype, $tradate, $totamount);

$row_rsDetails = $stmtDetails->fetch();


$book_data=array();


if($totalRows_rsDetails>=1){
	do{
		$book_data[] = array('detail_id'=>$detail_id, 'trn_num'=>$trano, 
						'trn_type'=>$tratype, 'trn_date'=>$tradate, 'tot_amount'=>$totamount);
		
	}while($stmtDetails->fetch());
}

$output = array('table_data'=>$book_data);

echo json_encode($output);
?>