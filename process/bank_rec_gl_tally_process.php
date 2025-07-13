<?php 
session_start();

$field_error_msg='';

if(!isset($_SESSION['userid'])){
	$field_error_msg='Session Expired';
}

$reconcileType=$_POST['reconcile_type'];
$groupCode=$_POST['group_code'];

if($field_error_msg==''){
	if($reconcileType=='G_REC'){
		$field_error_msg=($groupCode=='BANK')?'You should select GL Records only':'';
	}else if($reconcileType=='B_REC'){
		$field_error_msg=($groupCode=='GENL')?'You should select Bank Statement Records only':'';
	}
}

$docNum=$_POST['doc_num'];

if($docNum==-1){
	$field_error_msg='Bank statement not available';
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

$logCancel=$_POST['detail_cancel'];

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



$conn->autocommit(FALSE);
$flag = true;
$resmsg = '';

/*
check-if-reconciliation-complete
*/
$pre_sql="SELECT id AS bank_ac_statement_id, statement_account_id FROM tbl_gl_bank_statements WHERE id=? AND reconcile_complete=0";
$pre_stmt=$conn->prepare($pre_sql);
$pre_stmt->bind_param('s', $docNum);
$pre_stmt->execute();
$pre_stmt->store_result();
$pre_stmt->bind_result($bank_ac_statement_id, $statement_account_id);

$flag=($pre_stmt->num_rows==1)?$flag:false;

if($flag){
	if($head_k==''){
		$updateSQL = "INSERT INTO tbl_gl_ac_audits (tbl_gl_bank_statement_id, ac_compare_method, created_by, created_at) VALUES (?, ?, ?, NOW())";
	
		$stmt = $conn->prepare($updateSQL);
		$stmt->bind_param("sss", $docNum, $reconcileType, $userID);
		$ResultOut = $stmt->execute();
		
		$affectedRowCnt = $conn->affected_rows;
		
		if($affectedRowCnt==1){
			$head_k = $stmt->insert_id;
			
		}else{
			$flag = false;
		}
		
		$stmt->close();
	}
	
	if($sub_k==''){
		$updateSQL = "INSERT INTO tbl_gl_ac_audit_details (tbl_gl_ac_audit_id, particulars_id, reg_group_code, created_by, created_at) VALUES (?, ?, ?, ?, NOW())";
	
		$stmt = $conn->prepare($updateSQL);
		$stmt->bind_param("ssss", $head_k, $receiptRefNo, $groupCode, $userID);
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
		$updateSQL = "UPDATE tbl_gl_ac_audit_details SET log_cancel=?, updated_by=?, updated_at=NOW() WHERE id=?";
		
		$stmt = $conn->prepare($updateSQL);
		$stmt->bind_param("sss", $logCancel, $userID, $sub_k);
		$ResultOut = $stmt->execute();
		
		$affectedRowCnt = $conn->affected_rows;
		
		$resmsg = ($logCancel==0)?'<h5>Receipt added successfully</h5>':'<h5>Receipt removed successfully</h5>';
		
		if(!($affectedRowCnt==1)){
			$flag = false;
		}
		
		$stmt->close();
	}
	
	/*
	mark-or-unmark-receipt-details-as-bank-deposit
	
	$updateSQL = "UPDATE tbl_account_transaction SET ismatched=(1-?), updatedatetime=NOW() WHERE idtbl_account_transaction=?";
	
	$stmt = $conn->prepare($updateSQL);
	$stmt->bind_param("ss", $logCancel, $receiptRefNo);
	$ResultOut = $stmt->execute();
	
	$affectedRowCnt = $conn->affected_rows;
	
	if(!($affectedRowCnt==1)){
		$flag = false;
	}
	
	$stmt->close();
	*/
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