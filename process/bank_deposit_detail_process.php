<?php 
session_start();

if(!isset($_SESSION['userid'])){
	//header ("Location:index.php");
	$actionObj=new stdClass();
    $actionObj->icon='fas fa-exclamation-triangle';
    $actionObj->title='';
    $actionObj->message='Session Expired';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='danger';

    echo json_encode(array('msgdesc'=>$actionObj));
	
	die();
}

require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$depositCancel=$_POST['detail_cancel'];
$depositAmount=$_POST['detail_val'];

$updatedatetime=date('Y-m-d h:i:s');


$head_k = ''; /*invoice-id*/
$sub_k = '';

if(isset($_POST['ref_id'])){
	$head_k = $_POST['ref_id'];
}
/**/
if(isset($_POST['sub_id'])){
	$sub_k = $_POST['sub_id'];
}

$receiptRefNo=$_POST['receipt_refno'];
$receiptAccountId=$_POST['acc_ref'];
$receiptBranchId=$_POST['branch_ref'];
$setCash=$_POST['set_cash'];


$conn->autocommit(FALSE);
$flag = true;
$resmsg = '';

$pre_deposit_cnt=1;

$preDeposits = "SELECT COUNT(*) AS pre_deposit_cnt FROM tbl_gl_bank_deposit_receipt_details WHERE tbl_gl_receipt_detail_id=? AND tbl_gl_bank_deposit_id<>? AND deposit_cancel=0";
$stmtDeposit = $conn->prepare($preDeposits);
$stmtDeposit->bind_param('ss', $receiptRefNo, $head_k);
$stmtDeposit->execute();
$stmtDeposit->store_result();

if($stmtDeposit->num_rows==1){
	$stmtDeposit->bind_result($pre_deposit_cnt);
	$row_preDeposits = $stmtDeposit->fetch();
}else{
	$flag=false;
}

if($pre_deposit_cnt==1){
	$flag=false;
}

$presql = "SELECT id FROM tbl_gl_bank_deposits WHERE id=? AND deposit_complete=0";
$stmtFinalize = $conn->prepare($presql);
$stmtFinalize->bind_param('s', $head_k);
$stmtFinalize->execute();
$stmtFinalize->store_result();

if($stmtFinalize->num_rows==0){
	$flag=false;
}

if($flag){
	if($sub_k==''){
		$updateSQL = "INSERT INTO tbl_gl_bank_deposit_receipt_details (tbl_gl_bank_deposit_id, tbl_gl_receipt_detail_id, receipt_account_id, receipt_branch_id, transaction_refno, transaction_branch_code, transaction_subaccount, transaction_accamount, created_by, created_at) SELECT ? AS tbl_gl_bank_deposit_id , ? AS tbl_gl_receipt_detail_id, ? AS receipt_account_id, ? AS receipt_branch_id, drv.refno AS transaction_refno, drv.branchcode AS transaction_branch_code, drv.acccode AS transaction_subaccount, drv.accamount AS transaction_accamount, ? AS created_by, NOW() AS created_at FROM  (SELECT drv_rec.branchcode, drv_rec.acccode, drv_sub.refno, drv_sub.accamount FROM (SELECT tbl_gl_receipt_id, 0 AS refno, received_amount AS accamount FROM tbl_gl_receipt_details WHERE id=?) AS drv_sub INNER JOIN (SELECT id, receipt_debit_branch_code AS branchcode, receipt_debit_subaccount AS acccode FROM tbl_gl_receipts) AS drv_rec ON drv_sub.tbl_gl_receipt_id=drv_rec.id) AS drv";
	
		$stmt = $conn->prepare($updateSQL);
		$stmt->bind_param("ssssss", $head_k, $receiptRefNo, $receiptAccountId, $receiptBranchId, $userID, $receiptRefNo);
		$ResultOut = $stmt->execute();
		
		$affectedRowCnt = $conn->affected_rows;
		
		if($affectedRowCnt==1){
			$sub_k = $stmt->insert_id;
			$resmsg = '<h5>Receipt added successfully</h5>';
		}else{
			$flag = false;
		}
		
		$stmt->close();
		
	}else{
		$updateSQL = "UPDATE tbl_gl_bank_deposit_receipt_details SET deposit_cancel=?, updated_by=?, updated_at=NOW() WHERE id=?";
		
		$stmt = $conn->prepare($updateSQL);
		$stmt->bind_param("sss", $depositCancel, $userID, $sub_k);
		$ResultOut = $stmt->execute();
		
		$affectedRowCnt = $conn->affected_rows;
		
		$resmsg = ($depositCancel==0)?'<h5>Receipt added successfully</h5>':'<h5>Receipt removed successfully</h5>';
		
		if(!($affectedRowCnt==1)){
			$flag = false;
		}
		
		$stmt->close();
	}
	
	/*
	mark-or-unmark-receipt-details-as-bank-deposit
	*/
	$updateSQL = "UPDATE tbl_gl_receipt_details SET bank_deposit=(1-?), updated_by=?, updated_at=NOW() WHERE id=? AND receipt_cancel=0";
	
	$stmt = $conn->prepare($updateSQL);
	$stmt->bind_param("sss", $depositCancel, $userID, $receiptRefNo);
	$ResultOut = $stmt->execute();
	
	$affectedRowCnt = $conn->affected_rows;
	
	if(!($affectedRowCnt==1)){
		$flag = false;
	}
	
	$stmt->close();
	
	
	/*
	update-cheque-deposit-value-according-to-selected-cheque-receipts
	*/
	if($setCash==0){
		$updateSQL = "UPDATE tbl_gl_bank_deposits SET deposit_amount=(deposit_amount+?) WHERE id=? AND cheque_collection=1";
		
		$stmt = $conn->prepare($updateSQL);
		$stmt->bind_param("ss", $depositAmount, $head_k);
		$ResultOut = $stmt->execute();
		
		$affectedRowCnt = $conn->affected_rows;
		
		if(!($affectedRowCnt==1)){
			$flag = false;
		}
		
		$stmt->close();
		
		
	}
	
}

$actionObj=new stdClass();

if ($flag) {
	$conn->commit();
	/*
	echo "All queries were executed successfully";
	*/
	$actionObj->icon='fas fa-check-circle';
	$actionObj->title='';
	$actionObj->message=$resmsg;//'Add Successfully';
	$actionObj->url='';
	$actionObj->target='_blank';
	$actionObj->type='success';
} else {
	$conn->rollback();
	/*
	echo "All queries were rolled back";
	*/
	$actionObj->icon='fas fa-exclamation-triangle';
	$actionObj->title='';
	$actionObj->message='Record Error';
	$actionObj->url='';
	$actionObj->target='_blank';
	$actionObj->type='danger';
}

$res_arr = array('msgdesc'=>$actionObj, 'head_k'=>$head_k, 'sub_k'=>$sub_k);

echo json_encode($res_arr);
//---