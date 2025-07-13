<?php
require_once('../connection/db.php');

$receiptID=$_POST['refID'];
$deposit_complete=0;

//header
$query_rsReceipt = "select cash_collection, cheque_collection, branch_id, deposit_date, deposit_narration, deposit_amount, transfer_branch_id, transfer_account_id, deposit_complete from tbl_gl_bank_deposits WHERE id=?";

$stmtReceipt = $conn->prepare($query_rsReceipt);
$stmtReceipt->bind_param('s', $receiptID);
$stmtReceipt->execute();
$stmtReceipt->store_result();
$totalRows_rsReceipt = $stmtReceipt->num_rows;
$stmtReceipt->bind_result($cash_collection, $cheque_collection, $branch_id, $deposit_date, $deposit_narration, $deposit_amount, $transfer_branch_id, $transfer_account_id, $deposit_complete);

if($totalRows_rsReceipt==1){
	$row_rsReceipt = $stmtReceipt->fetch();
}

$output = array('set_cash'=>$cash_collection, 'set_cheque'=>$cheque_collection, 'branch_id'=>$branch_id, 'deposit_date'=>$deposit_date, 'deposit_narration'=>$deposit_narration, 'rec_amount'=>$deposit_amount, 'transfer_branch_id'=>$transfer_branch_id, 'transfer_acc_id'=>$transfer_account_id, 'rec_complete'=>$deposit_complete);

echo json_encode($output);
?>