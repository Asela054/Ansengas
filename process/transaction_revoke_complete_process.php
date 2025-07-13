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
$receipt_complete=0;

$updatedatetime=date('Y-m-d h:i:s');


$head_k = ''; /*invoice-id*/

if(isset($_POST['ref_id'])){
	$head_k = $_POST['ref_id'];
}


$conn->autocommit(FALSE);
$flag = true;

if($head_k!=''){
	$pre_sql = "SELECT COUNT(*) AS rev_cnt FROM tbl_gl_rev_audit_details WHERE tbl_gl_rev_audit_id=? AND log_cancel=0";
	$stmtRevCnt = $conn->prepare($pre_sql);
	$stmtRevCnt->bind_param('s', $head_k);
	$stmtRevCnt->execute();
	$stmtRevCnt->store_result();
	$stmtRevCnt->bind_result($rev_cnt);
	$row_rsRevCnt = $stmtRevCnt->fetch();
	
	/*
	allow-cancellation-for-unmatched-records-by-filtering-ismatched-column
	*/
	$insertSQL = "INSERT INTO tbl_gl_transaction_revoke_regs (tbl_gl_rev_audit_id, trano, created_by, created_at) SELECT drv.tbl_gl_rev_audit_id, drv.trano, ? AS created_by, NOW() AS created_at FROM(SELECT tbl_gl_rev_audit_id, trano, idtbl_account_transaction FROM tbl_gl_rev_audit_details WHERE tbl_gl_rev_audit_id=? AND log_cancel=0) AS drv INNER JOIN (SELECT trano, SUM(ismatched) AS ismatched FROM tbl_account_transaction WHERE trano IN(SELECT trano FROM tbl_gl_rev_audit_details WHERE tbl_gl_rev_audit_id=? AND log_cancel=0) GROUP BY trano) AS tbl_account_transaction ON drv.trano=tbl_account_transaction.trano WHERE tbl_account_transaction.ismatched=0";

	$stmt = $conn->prepare($insertSQL);
	$stmt->bind_param("sss", $userID, $head_k, $head_k);
	$ResultOut = $stmt->execute();
	
	$affectedRowCnt = $conn->affected_rows;
	
	if($affectedRowCnt==$rev_cnt){
		//$receipt_complete=1;
	}else{
		$flag = false;
	}
	
	$stmt->close();


	
	if($flag){
		$updateSQL = "INSERT INTO tbl_account_transaction (tbl_master_idtbl_master, trano, tratype, seqno, acccode, accamount, crdr, narration, totamount, tradate, paytype, refid, reversstatus, status, refno, updatedatetime, ismatched, branchcode, companycode, tbl_user_idtbl_user) ";
		/*
		$updateSQL .= "SELECT drv_revs.tbl_master_idtbl_master, drv_revs.trano, drv_revs.tratype, drv_revs.seqno AS seqno, drv_revs.acccode, drv_revs.accamount AS accamount, drv_crdr.tog_crdr, drv_revs.narration, drv_revs.totamount AS totamount, drv_revs.tradate, drv_revs.paytype, drv_revs.refid, drv_revs.status, drv_revs.refno, NOW() AS updatedatetime, drv_revs.ismatched, drv_revs.branchcode, drv_revs.companycode, ? AS tbl_user_idtbl_user FROM (SELECT tbl_master_idtbl_master, trano, tratype, seqno, acccode, accamount, crdr,  narration, totamount, tradate, paytype, refid, status, refno, ismatched, branchcode, companycode FROM tbl_account_transaction WHERE trano IN (SELECT trano FROM tbl_gl_rev_audit_details WHERE tbl_gl_rev_audit_id=? AND log_cancel=0)) AS drv_revs INNER JOIN (SELECT 'C' AS pre_crdr, 'D' AS tog_crdr UNION ALL SELECT 'D' AS pre_crdr, 'C' AS tog_crdr) AS drv_crdr ON drv_revs.crdr=drv_crdr.pre_crdr";
		*/
		$updateSQL .= "SELECT drv_revs.tbl_master_idtbl_master, CONCAT('X', SUBSTRING(CONCAT('00000000', drv_regs.id), -9, 9)) AS trano, drv_revs.tratype, drv_revs.seqno AS seqno, drv_revs.acccode, drv_revs.accamount AS accamount, drv_crdr.tog_crdr, drv_revs.narration, drv_revs.totamount AS totamount, drv_revs.tradate, drv_revs.paytype, drv_revs.refid, 1 AS reversstatus, drv_revs.status, drv_revs.refno, NOW() AS updatedatetime, drv_revs.ismatched, drv_revs.branchcode, drv_revs.companycode, ? AS tbl_user_idtbl_user FROM (SELECT tbl_master_idtbl_master, trano, tratype, seqno, acccode, accamount, crdr,  narration, totamount, tradate, paytype, refid, status, refno, ismatched, branchcode, companycode FROM tbl_account_transaction WHERE trano IN (SELECT trano FROM tbl_gl_rev_audit_details WHERE tbl_gl_rev_audit_id=? AND log_cancel=0)) AS drv_revs ";
		$updateSQL .= "INNER JOIN (SELECT id, trano FROM tbl_gl_transaction_revoke_regs WHERE tbl_gl_rev_audit_id=?) AS drv_regs ON drv_revs.trano=drv_regs.trano ";
		$updateSQL .= "INNER JOIN (SELECT 'C' AS pre_crdr, 'D' AS tog_crdr UNION ALL SELECT 'D' AS pre_crdr, 'C' AS tog_crdr) AS drv_crdr ON drv_revs.crdr=drv_crdr.pre_crdr";
		
		$stmt = $conn->prepare($updateSQL);
		$stmt->bind_param("sss", $userID, $head_k, $head_k);
		$ResultOut = $stmt->execute();
		
		$affectedRowCnt = $conn->affected_rows;
		
		if($affectedRowCnt>=2){
			//$receipt_complete = 1;
		}else{
			$flag = false;
		}
		
		$updateSQL = "UPDATE tbl_account_transaction SET reversstatus=1 WHERE trano IN (SELECT trano FROM tbl_gl_rev_audit_details WHERE tbl_gl_rev_audit_id=? AND log_cancel=0)";
		$stmtFreeze = $conn->prepare($updateSQL);
		$stmtFreeze->bind_param("s", $head_k);
		$ResultOut = $stmtFreeze->execute();
		
		if(!($affectedRowCnt==$conn->affected_rows)){
			$flag = false;
		}
		
		
		//release-invoice-records-related-to-transactions
		/*
		$sql = "SELECT drv_rev.receiptinvno, drv_rev.paytype FROM (SELECT receiptinvno, IFNULL(NULLIF(paytype, paytype NOT IN ('CA', 'CH')), 'CX') AS paytype FROM tbl_account_transaction WHERE trano IN (SELECT trano FROM tbl_gl_rev_audit_details WHERE tbl_gl_rev_audit_id=? AND log_cancel=0) AND receiptinvno<>0 AND seqno>0) AS drv_rev ";
		*/
		$sql = "SELECT drv_rev.receiptinvno, drv_rev.paytype FROM (SELECT receiptinvno, IFNULL(NULLIF(paytype, paytype NOT IN ('CA', 'CH')), 'CX') AS paytype FROM tbl_account_transaction WHERE trano IN (SELECT trano FROM tbl_gl_rev_audit_details WHERE tbl_gl_rev_audit_id=? AND log_cancel=0) AND receiptinvno<>0 AND tratype='P' GROUP BY receiptinvno) AS drv_rev ";
		$sql .= "INNER JOIN (SELECT idtbl_invoice AS receiptinvno, 'CR' AS paytype FROM tbl_invoice WHERE addtoaccountstatus=1 UNION ALL SELECT idtbl_invoice_payment_detail AS receiptinvno, 'CX' AS paytype FROM tbl_invoice_payment_detail WHERE addaccountstatus=1) AS drv_inv ON (drv_rev.receiptinvno=drv_inv.receiptinvno AND drv_rev.paytype=drv_inv.paytype)";
		$stmtInvList = $conn->prepare($sql);
		$stmtInvList->bind_param('s', $head_k);
		$stmtInvList->execute();
		$stmtInvList->store_result();
		$totalRows_invList = $stmtInvList->num_rows;
		$stmtInvList->bind_result($receiptinvno, $paytype);
		
		if($totalRows_invList>0){
			$invNums = array('CR'=>array(), 'CX'=>array());
			$affectedInvCnt = 0;
			
			while($stmtInvList->fetch()){
				$invNums[$paytype][]=$receiptinvno;
			}
			
			if(count($invNums['CR'])>0){
				$updateSQL = "UPDATE tbl_invoice SET addtoaccountstatus=0 WHERE idtbl_invoice IN (".implode(',', $invNums['CR']).")";
				$stmt = $conn->prepare($updateSQL);
				$ResultOut = $stmt->execute();
				
				$affectedInvCnt+=$conn->affected_rows;
			}
			
			if(count($invNums['CX'])>0){
				$updateSQL = "UPDATE tbl_invoice_payment_detail SET addaccountstatus=0 WHERE idtbl_invoice_payment_detail IN (".implode(',', $invNums['CX']).")";
				$stmt = $conn->prepare($updateSQL);
				$ResultOut = $stmt->execute();
				
				$affectedInvCnt+=$conn->affected_rows;
			}
			
			if(!($affectedInvCnt==$totalRows_invList)){
				$flag=false;
			}
		}
		
		
		//release-payment-records-associated-with-porderpaymentid-related-to-transactions
		$sql = "SELECT porderpaymentid FROM tbl_gl_payment_details WHERE CONCAT('P', SUBSTRING(CONCAT('00000000', tbl_gl_payment_id), -9, 9)) IN (SELECT trano FROM tbl_gl_rev_audit_details WHERE tbl_gl_rev_audit_id=? AND log_cancel=0) AND payment_cancel=0 AND porderpaymentid<>0";
		$stmtPayList = $conn->prepare($sql);
		$stmtPayList->bind_param('s', $head_k);
		$stmtPayList->execute();
		$stmtPayList->store_result();
		$totalRows_payList = $stmtPayList->num_rows;
		$stmtPayList->bind_result($porderpaymentid);
		
		if($totalRows_payList>0){
			$payNums = array();
			
			while($stmtPayList->fetch()){
				$payNums[] = $porderpaymentid;
			}
			
			$updateSQL = "UPDATE tbl_porder_payment SET accountstatus=0 WHERE idtbl_porder_payment IN (".implode(',', $payNums).")";
			$stmt = $conn->prepare($updateSQL);
			$ResultOut = $stmt->execute();
			
			if(!($totalRows_payList==$conn->affected_rows)){
				$flag = false;
			}
		}
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
	$actionObj->message='Process Completed Successfully';
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

$res_arr = array('msgdesc'=>$actionObj, 'rec_complete'=>$receipt_complete);

echo json_encode($res_arr);
//---