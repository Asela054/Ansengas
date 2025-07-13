<?php 
session_start();

$field_error_msg='';

if(!isset($_SESSION['userid'])){
	$field_error_msg='Session Expired';
}

if(!isset($_POST['debit_branch_colcode'], $_POST['debit_acc_colcode'], 
			$_POST['credit_branch_colcode'], $_POST['credit_acc_colcode'])){
	$field_error_msg='Select all fields marked as required';
}

$chequeNo=NULL;
$chequeDate=NULL;
$chequeBank=NULL;

$setCash=(int)$_POST['set_cash'];
$setCheque=1-$setCash;

if($setCheque==1){
	$chequeDate=$_POST['cheque_date'];
	$chequeBank=$_POST['cheque_bank'];
	
	$chequeNo=$_POST['cheque_no'];
	
	if(!preg_match('/^\d{6}$/', $chequeNo)){//'/^[1-9]\d{4}$/'
		$field_error_msg='Invalid cheque number';
	}
	
	if(($chequeDate=='')||($chequeBank=='')){
		$field_error_msg='Select cheque date and bank';
	}
	
	
}

if($field_error_msg!=''){
	//header ("Location:index.php");
	$actionObj=new stdClass();
    $actionObj->icon='fas fa-exclamation-triangle';
    $actionObj->title='';
    $actionObj->message=$field_error_msg;//'Session Expired';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='danger';

    echo json_encode(array('msgdesc'=>$actionObj));
	
	die();
}

require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$receiptCustomer=$_POST['rec_customer'];
$receiptCategory=$_POST['rec_category'];
$headNarration=$_POST['head_narration'];

$debitBranch=$_POST['debit_branch'];
$debitAccount=$_POST['debit_acc'];
$debitBranchColcode=$_POST['debit_branch_colcode'];
$debitAccountColcode=$_POST['debit_acc_colcode'];
$creditBranch=$_POST['credit_branch'];
$creditAccount=$_POST['credit_acc'];
$creditBranchColcode=$_POST['credit_branch_colcode'];
$creditAccountColcode=$_POST['credit_acc_colcode'];

$updatedatetime=date('Y-m-d h:i:s');


$head_k = ''; /*invoice-id*/
$sub_k = '';

if(isset($_POST['ref_id'])){
	$head_k = $_POST['ref_id'];
}

$receiptNarration=$_POST['rec_narration'];
$receivedAmount=$_POST['rec_amount'];

$conn->autocommit(FALSE);
$flag = true;

if($head_k==''){
	$updateSQL = "INSERT INTO tbl_gl_receipts (receipt_customer, receipt_category, receipt_head_narration, receipt_debit_branch, receipt_debit_account, receipt_debit_branch_code, receipt_debit_subaccount, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

	$stmt = $conn->prepare($updateSQL);
	$stmt->bind_param("ssssssss", $receiptCustomer, $receiptCategory, $headNarration, $debitBranch, $debitAccount, $debitBranchColcode, $debitAccountColcode, $userID);
	$ResultOut = $stmt->execute();
	
	$affectedRowCnt = $conn->affected_rows;
	
	if($affectedRowCnt==1){
		$head_k = $stmt->insert_id;
	}else{
		$flag = false;
	}
	
	$stmt->close();
	
}else{
	$updateSQL = "UPDATE tbl_gl_receipts SET receipt_customer=?, receipt_category=?, receipt_head_narration=?, receipt_debit_branch=?, receipt_debit_account=?, receipt_debit_branch_code=?, receipt_debit_subaccount=?, updated_by=?, updated_at=NOW() WHERE id=? AND receipt_complete=0";

	$stmt = $conn->prepare($updateSQL);
	$stmt->bind_param("sssssssss", $receiptCustomer, $receiptCategory, $headNarration, $debitBranch, $debitAccount, $debitBranchColcode, $debitAccountColcode, $userID, $head_k);
	$ResultOut = $stmt->execute();
	
	$affectedRowCnt = $conn->affected_rows;
	
	if(!($affectedRowCnt==1)){
		$flag = false;
	}
	
	$stmt->close();
}


$updateSQL = "INSERT INTO tbl_gl_receipt_details (tbl_gl_receipt_id, receipt_sub_narration, receipt_credit_branch, receipt_credit_account, receipt_credit_branch_code, receipt_credit_subaccount, settle_by_cash, settle_by_cheque, cheque_no, cheque_date, cheque_bank, received_amount, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($updateSQL);
$stmt->bind_param("sssssssssssss", $head_k, $receiptNarration, $creditBranch, $creditAccount, $creditBranchColcode, $creditAccountColcode, $setCash, $setCheque, $chequeNo, $chequeDate, $chequeBank, $receivedAmount, $userID);
$ResultOut = $stmt->execute();

$affectedRowCnt = $conn->affected_rows;

if($affectedRowCnt==1){
	$sub_k = $stmt->insert_id;
}else{
	$flag = false;
}

$actionObj=new stdClass();

if ($flag) {
	$conn->commit();
	/*
	echo "All queries were executed successfully";
	*/
	$actionObj->icon='fas fa-check-circle';
	$actionObj->title='';
	$actionObj->message='Add Successfully';
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