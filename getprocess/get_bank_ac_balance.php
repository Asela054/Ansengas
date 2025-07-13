<?php
require_once('../connection/db.php');

$statement_id='-1';
$subaccountno='';
$infomsg='';
$bank_reconcile_balance=0;

$gc_rectot=0;
$gc_uptot=0;
$bd_rectot=0;
$bd_uptot=0;

$gd_rectot=0;
$gd_uptot=0;
$bc_rectot=0;
$bc_uptot=0;

$accountId=$_POST['ac_num'];
$statementDate=$_POST['statement_date'];

//header
$query_rsReceipt = "select tbl_gl_bank_statements.id AS statement_id, tbl_account_allocation.subaccountno from tbl_gl_bank_statements INNER JOIN tbl_account_allocation ON tbl_gl_bank_statements.statement_account_id=tbl_account_allocation.idtbl_account_allocation WHERE tbl_gl_bank_statements.statement_account_id=? AND tbl_gl_bank_statements.statement_date_to=?";

$stmtReceipt = $conn->prepare($query_rsReceipt);
$stmtReceipt->bind_param('ss', $accountId, $statementDate);
$stmtReceipt->execute();
$stmtReceipt->store_result();
$totalRows_rsReceipt = $stmtReceipt->num_rows;
$stmtReceipt->bind_result($statement_id, $subaccountno);

if($totalRows_rsReceipt==1){
	$row_rsReceipt = $stmtReceipt->fetch();
	
	$query_rsCheckPoint = "select bank_reconcile_balance from tbl_gl_bank_statements WHERE statement_account_id=? AND reconcile_complete=1 AND reconcile_checkpoint=1";
	
	$stmtCheckPoint = $conn->prepare($query_rsCheckPoint);
	$stmtCheckPoint->bind_param('s', $accountId);
	$stmtCheckPoint->execute();
	$stmtCheckPoint->store_result();
	$totalRows_rsCheckPoint = $stmtCheckPoint->num_rows;
	$stmtCheckPoint->bind_result($bank_reconcile_balance);
	
	if($totalRows_rsCheckPoint==1){
		$row_rsCheckPoint = $stmtCheckPoint->fetch();
	}
	
	$infomsg=($bank_reconcile_balance==$_POST['statement_bal'])?'Account balance match':'Account closing balance is '.$bank_reconcile_balance;
	
	/*
	//get-present-and-unpresent-tots
	//gl
	//drv_reg.crdr, drv_reg.accamount, drv_reg.ismatched
	*/
	/*
	prev-query = ""SELECT SUM((drv_reg.crdr='C')*(drv_reg.ismatched=0)*drv_reg.accamount) AS gc_uptot, SUM((drv_reg.crdr='C')*(drv_reg.ismatched=1)*drv_reg.accamount) AS gc_rectot, 0 AS gd_uptot, 0 AS gd_rectot FROM (SELECT idtbl_account_transaction, accamount, crdr, ismatched FROM tbl_account_transaction WHERE acccode=? AND tradate<=? AND tratype='D' ..."
	*/
	$query_rsglReconcileProg = "SELECT SUM((drv_reg.crdr='C')*(drv_reg.ismatched=0)*drv_reg.accamount) AS gc_uptot, SUM((drv_reg.crdr='C')*(drv_reg.ismatched=1)*drv_reg.accamount) AS gc_rectot, SUM((drv_reg.crdr='D')*(drv_reg.ismatched=0)*drv_reg.accamount) AS gd_uptot, SUM((drv_reg.crdr='D')*(drv_reg.ismatched=1)*drv_reg.accamount) AS gd_rectot FROM (SELECT idtbl_account_transaction, accamount, crdr, ismatched FROM tbl_account_transaction WHERE acccode=? AND tradate<=? AND trano NOT IN (SELECT trano FROM tbl_gl_transaction_revoke_regs UNION ALL SELECT CONCAT('X', SUBSTRING(CONCAT('00000000', id), -9, 9)) AS trano FROM tbl_gl_transaction_revoke_regs)) AS drv_reg LEFT OUTER JOIN (SELECT tbl_gl_ac_audits.id, tbl_gl_ac_audit_details.particulars_id FROM tbl_gl_ac_audits INNER JOIN tbl_gl_ac_audit_details ON tbl_gl_ac_audits.id=tbl_gl_ac_audit_details.tbl_gl_ac_audit_id WHERE tbl_gl_bank_statement_id=? AND tbl_gl_ac_audit_details.log_copy=1 AND tbl_gl_ac_audit_details.reg_group_code='GENL' AND tbl_gl_ac_audit_details.log_cancel=0) AS drv_log ON drv_reg.idtbl_account_transaction=drv_log.particulars_id WHERE ((drv_reg.idtbl_account_transaction=IFNULL(drv_log.particulars_id, '')) + (drv_reg.ismatched=0))=1"; // GROUP BY drv_reg.crdr, drv_reg.ismatched
	
	$stmtglReconcileProg = $conn->prepare($query_rsglReconcileProg);
	$stmtglReconcileProg->bind_param('sss', $subaccountno, $statementDate, $statement_id);
	$stmtglReconcileProg->execute();
	$stmtglReconcileProg->store_result();
	$totalRows_rsglReconcileProg = $stmtglReconcileProg->num_rows;
	$stmtglReconcileProg->bind_result($gc_uptot, $gc_rectot, $gd_uptot, $gd_rectot);
	
	if($totalRows_rsglReconcileProg==1){
		$stmtglReconcileProg->fetch();
	}
	
	/*
	//bank-statement
	*/
	$query_rsbsReconcileProg = "SELECT SUM((drv_reg.crdr='D')*(drv_reg.ismatched=0)*drv_reg.accamount) AS bd_uptot, SUM((drv_reg.crdr='D')*(drv_reg.ismatched=1)*drv_reg.accamount) AS bd_rectot, SUM((drv_reg.crdr='C')*(drv_reg.ismatched=0)*drv_reg.accamount) AS bc_uptot, SUM((drv_reg.crdr='C')*(drv_reg.ismatched=1)*drv_reg.accamount) AS bc_rectot FROM (SELECT id AS idtbl_account_transaction, ABS(transaction_amount) AS accamount, crdr, ismatched FROM tbl_gl_bank_statement_details WHERE tbl_gl_bank_statement_id=?) AS drv_reg LEFT OUTER JOIN (SELECT tbl_gl_ac_audits.id, tbl_gl_ac_audit_details.particulars_id FROM tbl_gl_ac_audits INNER JOIN tbl_gl_ac_audit_details ON tbl_gl_ac_audits.id=tbl_gl_ac_audit_details.tbl_gl_ac_audit_id WHERE tbl_gl_bank_statement_id=? AND tbl_gl_ac_audit_details.log_copy=1 AND tbl_gl_ac_audit_details.reg_group_code='BANK' AND tbl_gl_ac_audit_details.log_cancel=0) AS drv_log ON drv_reg.idtbl_account_transaction=drv_log.particulars_id WHERE ((drv_reg.idtbl_account_transaction=IFNULL(drv_log.particulars_id, '')) + (drv_reg.ismatched=0))=1"; // GROUP BY drv_reg.crdr, drv_reg.ismatched
	
	$stmtbsReconcileProg = $conn->prepare($query_rsbsReconcileProg);
	$stmtbsReconcileProg->bind_param('ss', $statement_id, $statement_id);
	$stmtbsReconcileProg->execute();
	$stmtbsReconcileProg->store_result();
	$totalRows_rsbsReconcileProg = $stmtbsReconcileProg->num_rows;
	$stmtbsReconcileProg->bind_result($bd_uptot, $bd_rectot, $bc_uptot, $bc_rectot);
	
	if($totalRows_rsbsReconcileProg==1){
		$stmtbsReconcileProg->fetch();
	}
	
}else{
	$infomsg='Bank statement not found for account and date';
}

$output = array('statement_id'=>$statement_id, 'infomsg'=>$infomsg, 'gc_rectot'=>$gc_rectot, 'gc_uptot'=>$gc_uptot, 
				'bd_rectot'=>$bd_rectot, 'bd_uptot'=>$bd_uptot, 
				'gd_rectot'=>$gd_rectot, 'gd_uptot'=>$gd_uptot, 
				'bc_rectot'=>$bc_rectot, 'bc_uptot'=>$bc_uptot);

echo json_encode($output);
?>