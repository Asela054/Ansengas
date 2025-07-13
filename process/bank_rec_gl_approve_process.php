<?php 
session_start();

$field_error_msg='';

if(!isset($_SESSION['userid'])){
	$field_error_msg='Session Expired';
}

$head_k = ''; /**/

if(isset($_POST['ref_id'])){
	$head_k = $_POST['ref_id'];
}


$logCopy=0;
$logSave=0;
$optSave=0;

$lockType=$_POST['lock_type'];

if($lockType=='copy'){
	$logCopy=1;
}else if($lockType=='save'){
	$logCopy=1;
	$logSave=1;
}


if($head_k==''){
	if($logSave==1){
		$optSave=1;
	}else{
		$field_error_msg='You must select the transactions first';
	}
}

$docNum = '';

if(isset($_POST['doc_num'])){
	$docNum = $_POST['doc_num'];
}

if($docNum==''){
	$field_error_msg='You must start reconciliation for selected account';//1st-check-up-keep-closer-to-error-info-json-output
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

$updatedatetime=date('Y-m-d h:i:s');


$conn->autocommit(FALSE);
$flag = true;
$resmsg = 'Selected records inserted';
//
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
	$pre_stmt->fetch();
	
	if($optSave==0){
		/*
		mark-or-unmark-receipt-details-as-bank-deposit
		*/
		if($logCopy==1){
			$copy_cnt = "select SUM(rec_cnt*(reg_group_code='BANK')) AS bank_cnt, SUM(rec_cnt*(reg_group_code='GENL')) AS genl_cnt FROM (SELECT COUNT(*) AS rec_cnt, reg_group_code FROM tbl_gl_ac_audit_details WHERE tbl_gl_ac_audit_id=? AND log_cancel=0 AND log_copy=0 GROUP BY reg_group_code) AS drv";
			$stmt_cnt = $conn->prepare($copy_cnt);
			$stmt_cnt->bind_param("s", $head_k);
			$stmt_cnt->execute();
			$stmt_cnt->store_result();
			$stmt_cnt->bind_result($bank_cnt, $genl_cnt);
			$stmt_cnt->fetch();
			
			$affectedRowCnt = 0;
			
			if($genl_cnt>0){
				/*
				allow-match-confirmation-for-active-records-by-filtering-trano-column
				*/
				$updateSQL = "UPDATE tbl_account_transaction SET ismatched=1, updatedatetime=NOW() WHERE idtbl_account_transaction IN (SELECT particulars_id FROM tbl_gl_ac_audit_details WHERE tbl_gl_ac_audit_id=? AND log_cancel=0 AND log_copy=0 AND reg_group_code='GENL') AND trano NOT IN (SELECT trano FROM tbl_gl_transaction_revoke_regs)";
				
				$stmt = $conn->prepare($updateSQL);
				$stmt->bind_param("s", $head_k);
				$ResultOut = $stmt->execute();
				
				$affectedRowCnt += $conn->affected_rows;
			}
			
			if($bank_cnt>0){
				$updateSQL = "UPDATE tbl_gl_bank_statement_details SET ismatched=1, updated_by=?, updated_at=NOW() WHERE id IN (SELECT particulars_id FROM tbl_gl_ac_audit_details WHERE tbl_gl_ac_audit_id=? AND log_cancel=0 AND log_copy=0 AND reg_group_code='BANK')";
				
				$stmt = $conn->prepare($updateSQL);
				$stmt->bind_param("ss", $userID, $head_k);
				$ResultOut = $stmt->execute();
				
				$affectedRowCnt += $conn->affected_rows;
			}
			
			$tot_cnt=$genl_cnt+$bank_cnt;
			
			if($affectedRowCnt==$tot_cnt){
				$resmsg = 'Selected records copied';
			}else{
				$flag = false;
			}
			
			$stmt_cnt->close();
		}
	
	
	
	
		$updateSQL = "UPDATE tbl_gl_ac_audit_details SET log_insert=1, log_copy=?, updated_by=?, updated_at=NOW() WHERE tbl_gl_ac_audit_id=? AND log_cancel=0 AND log_copy=0";
	
		$stmt = $conn->prepare($updateSQL);
		$stmt->bind_param("sss", $logCopy, $userID, $head_k);
		$ResultOut = $stmt->execute();
		
		$affectedRowCnt = $conn->affected_rows;
		
		if($affectedRowCnt==0){
			$flag = false;
		}
		
		$stmt->close();
	}
	
	
	if($logSave==1){
		$updateSQL = "UPDATE tbl_gl_bank_statements SET reconcile_checkpoint=(1-reconcile_complete), reconcile_complete=1, updated_by=?, updated_at=NOW() WHERE statement_account_id=? AND (reconcile_complete-reconcile_checkpoint)=0";
		
		$stmt = $conn->prepare($updateSQL);
		$stmt->bind_param("ss", $userID, $statement_account_id);
		$ResultOut = $stmt->execute();
		
		$affectedRowCnt = $conn->affected_rows;
		
		if($affectedRowCnt==0){
			$flag = false;
		}else{
			$resmsg='Reconciliation completed';
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

$res_arr = array('msgdesc'=>$actionObj);

echo json_encode($res_arr);
//---