<?php
require_once('../connection/db.php');

$receiptID=$_POST['refID'];
$receipt_complete=0;

//header
$query_rsReceipt = "select receipt_customer, receipt_category, receipt_head_narration, receipt_debit_branch, receipt_debit_account, receipt_complete from tbl_gl_receipts WHERE id=?";

$stmtReceipt = $conn->prepare($query_rsReceipt);
$stmtReceipt->bind_param('s', $receiptID);
$stmtReceipt->execute();
$stmtReceipt->store_result();
$totalRows_rsReceipt = $stmtReceipt->num_rows;
$stmtReceipt->bind_result($receipt_customer, $receipt_category, $receipt_head_narration, $receipt_debit_branch, $receipt_debit_account, $receipt_complete);

if($totalRows_rsReceipt==1){
	$row_rsReceipt = $stmtReceipt->fetch();
}

//detail
$query_rsDetails = "select tbl_gl_receipt_details.id as detail_id, tbl_gl_receipt_details.receipt_credit_branch_code AS receipt_credit_branch, CONCAT(tbl_gl_receipt_details.receipt_credit_subaccount, ' ', drv_acc.subaccountname) AS receipt_credit_account, tbl_gl_receipt_details.receipt_sub_narration, tbl_gl_receipt_details.settle_by_cash, tbl_gl_receipt_details.settle_by_cheque, tbl_gl_receipt_details.received_amount from tbl_gl_receipt_details INNER JOIN (SELECT subaccount, subaccountname FROM tbl_subaccount) AS drv_acc ON tbl_gl_receipt_details.receipt_credit_subaccount=drv_acc.subaccount WHERE tbl_gl_receipt_details.tbl_gl_receipt_id=? AND tbl_gl_receipt_details.receipt_cancel=0";

$stmtDetails = $conn->prepare($query_rsDetails);
$stmtDetails->bind_param('s', $receiptID);
$stmtDetails->execute();
$stmtDetails->store_result();
$totalRows_rsDetails = $stmtDetails->num_rows;
$stmtDetails->bind_result($detail_id, $receipt_credit_branch, $receipt_credit_account, $receipt_sub_narration, $settle_by_cash, $settle_by_cheque, $received_amount);

$row_rsDetails = $stmtDetails->fetch();

$setCash=1;
$setCheque=0;

$book_data=array();


if($totalRows_rsReceipt==1){
	$setCash=$settle_by_cash;
	$setCheque=$settle_by_cheque;

	do{
		$book_data[] = array('detail_id'=>$detail_id, 'receipt_credit_branch'=>$receipt_credit_branch, 
						'receipt_credit_account'=>$receipt_credit_account, 'receipt_sub_narration'=>$receipt_sub_narration, 
						'received_amount'=>$received_amount);
		
	}while($stmtDetails->fetch());
}

$output = array('rec_customer'=>$receipt_customer, 'rec_category'=>$receipt_category, 
				'receipt_head_narration'=>$receipt_head_narration, 
				'debit_branch_id'=>$receipt_debit_branch, 'debit_acc_id'=>$receipt_debit_account, 
				'rec_complete'=>$receipt_complete, 
				'set_cash'=>$setCash, 'set_cheque'=>$setCheque, 
				'table_data'=>$book_data);

echo json_encode($output);
?>