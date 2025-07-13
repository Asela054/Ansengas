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
	$updateSQL = "UPDATE tbl_gl_receipts SET receipt_complete=1, updated_by=?, updated_at=NOW() WHERE id=? AND receipt_complete=0";

	$stmt = $conn->prepare($updateSQL);
	$stmt->bind_param("ss", $userID, $head_k);
	$ResultOut = $stmt->execute();
	
	$affectedRowCnt = $conn->affected_rows;
	
	if($affectedRowCnt==1){
		//$receipt_complete=1;
	}else{
		$flag = false;
	}
	
	$stmt->close();


	/*
	master-id, trn-no, trn-id[r/./.], seq-no[0,1,2,.], acc-code, acc-amount, dr-cr[c/d], narration, total-amount, trn-date, pay-type[ca/.], cancel[0], ref-no, user-id, sys-date, is-matched[0], company-code
	
	
	select drv_year.tbl_master_idtbl_master, drv_main.trano_refno AS trano, drv_main.traid AS traid, (@row_number:=@row_number+1)-1 AS seqno, drv_main.acccode, IFNULL(accamount, (drv_sums.cash_amount*drv_main.cash_type)+(drv_sums.cheque_amount*(1-drv_main.cash_type))) AS accamount, drv_main.crdr, drv_main.narration, drv_sums.totamount, drv_main.tradate, IFNULL(drv_paytype.cols_text, 'CH') AS paytype, drv_main.cancel as status, drv_main.trano_refno AS refno, drv_main.systemdate as updatedatetime, drv_main.IsMatched as ismatched, tbl_company_branch.code as branchcode, tbl_company.code AS companycode from (
	
	select drv_h.trano_refno, drv_h.traid,  drv_h.branch_id, drv_h.acccode, NULL AS accamount, drv_h.crdr, drv_h.narration, drv_h.tradate, drv_r.cash_type, drv_h.cancel, drv_h.systemdate, drv_h.IsMatched FROM (
	select SUBSTRING(CONCAT('R00000000', id), -10, 10) AS trano_refno, 'R' AS traid, receipt_debit_branch AS branch_id, receipt_debit_account AS acccode, 'D' AS crdr, receipt_head_narration AS narration, DATE(created_at) AS tradate, 0 AS cancel, DATE(NOW()) AS systemdate, 0 AS IsMatched from tbl_gl_receipts where id=6 AND receipt_complete=0
	) AS drv_h CROSS JOIN (
	select 1 AS cash_type UNION ALL select 0 As cash_type
	) AS drv_r
	
	union all 
	
	select SUBSTRING(CONCAT('R00000000', tbl_gl_receipt_id), -10, 10) AS trano_refno, 'R' AS traid, receipt_credit_branch AS branch_id, receipt_credit_account AS acccode, received_amount AS accamount, 'C' AS crdr, receipt_sub_narration AS narration, DATE(created_at) AS tradate, settle_by_cash AS cash_type, 0 AS cancel, DATE(NOW()) AS systemdate, 0 AS IsMatched from tbl_gl_receipt_details WHERE tbl_gl_receipt_id=6 and receipt_cancel=0
	
	) AS drv_main 
	
	INNER JOIN (
	
	select tbl_gl_receipt_id AS trano, SUM(settle_by_cash * received_amount) AS cash_amount, SUM(settle_by_cheque * received_amount) AS cheque_amount, SUM(received_amount) AS totamount from tbl_gl_receipt_details WHERE tbl_gl_receipt_id=6 and receipt_cancel=0 GROUP BY trano
	
	) AS drv_sums ON drv_main.trano_refno=drv_sums.trano
	
	INNER JOIN (
	select idtbl_master AS tbl_master_idtbl_master, tbl_company_branch_idtbl_company_branch AS branch_id from tbl_master where status=1
	) AS drv_year ON drv_main.branch_id=drv_year.branch_id
	
	INNER JOIN tbl_company_branch ON drv_year.branch_id=tbl_company_branch.idtbl_company_branch
	
	INNER JOIN tbl_company ON tbl_company_branch.tbl_company_idtbl_company=tbl_company.idtbl_company 
	
	LEFT OUTER JOIN (select 1 AS rows_type, 'CA' AS cols_text) AS drv_paytype ON drv_main.cash_type=drv_paytype.rows_type
	
	CROSS JOIN (select @row_number:=0) AS t
	
	HAVING accamount>0
	


	*/
	/*
	$updateSQL = "INSERT INTO tbl_account_transaction (tbl_master_idtbl_master, trano, tratype, seqno, acccode, accamount, crdr,  narration, totamount, tradate, paytype, refid, status, refno, updatedatetime, ismatched, branchcode, companycode, tbl_user_idtbl_user) ";
	*/
	$updateSQL = "select drv_year.tbl_master_idtbl_master, drv_main.trano_refno AS trano, 'R' AS traid, (@row_number:=@row_number+1)-1 AS seqno, drv_main.acccode, IFNULL(accamount, (drv_sums.cash_amount*drv_main.cash_type)+(drv_sums.cheque_amount*(1-drv_main.cash_type))) AS accamount, drv_main.crdr, drv_main.narration, drv_sums.totamount, drv_main.tradate, IFNULL(drv_paytype.cols_text, 'CH') AS paytype, 0 AS refid, 0 as status, drv_main.trano_refno AS refno, IFNULL(drv_main.cheque_no, '-1') AS cheque_no, IFNULL(drv_main.doc_detail_id, -1) AS doc_detail_id, drv_main.systemdate as updatedatetime, 0 as ismatched, tbl_company_branch.code as branchcode, tbl_company.code AS companycode, ? AS tbl_user_idtbl_user from (";
	
	$updateSQL .= "select drv_h.trano, drv_h.trano_refno, NULL AS cheque_no, NULL AS doc_detail_id, drv_h.traid,  drv_h.branch_id, drv_h.acccode, NULL AS accamount, drv_h.crdr, drv_h.narration, drv_h.tradate, drv_r.cash_type, drv_h.systemdate FROM (";
	
	$updateSQL .= "select id as trano, CONCAT('R', SUBSTRING(CONCAT('00000000', id), -9, 9)) AS trano_refno, 'R' AS traid, receipt_debit_branch AS branch_id, receipt_debit_subaccount AS acccode, 'D' AS crdr, receipt_head_narration AS narration, DATE(created_at) AS tradate, DATE(NOW()) AS systemdate from tbl_gl_receipts where id=?) AS drv_h CROSS JOIN (select 1 AS cash_type UNION ALL select 0 As cash_type) AS drv_r UNION ALL ";
	
	$updateSQL .= "select tbl_gl_receipt_id as trano, CONCAT('R', SUBSTRING(CONCAT('00000000', tbl_gl_receipt_id), -9, 9)) AS trano_refno, cheque_no, id AS doc_detail_id, 'R' AS traid, receipt_credit_branch AS branch_id, receipt_credit_subaccount AS acccode, received_amount AS accamount, 'C' AS crdr, receipt_sub_narration AS narration, DATE(created_at) AS tradate, settle_by_cash AS cash_type, DATE(NOW()) AS systemdate from tbl_gl_receipt_details WHERE tbl_gl_receipt_id=? and receipt_cancel=0) AS drv_main INNER JOIN (";
	
	$updateSQL .= "select tbl_gl_receipt_id AS trano, SUM(settle_by_cash * received_amount) AS cash_amount, SUM(settle_by_cheque * received_amount) AS cheque_amount, SUM(received_amount) AS totamount from tbl_gl_receipt_details WHERE tbl_gl_receipt_id=? and receipt_cancel=0 GROUP BY trano) AS drv_sums ON drv_main.trano=drv_sums.trano INNER JOIN (";
	
	$updateSQL .= "select idtbl_master AS tbl_master_idtbl_master, tbl_company_branch_idtbl_company_branch AS branch_id from tbl_master where status=1) AS drv_year ON drv_main.branch_id=drv_year.branch_id INNER JOIN ";
	
	$updateSQL .= "tbl_company_branch ON drv_year.branch_id=tbl_company_branch.idtbl_company_branch INNER JOIN ";
	
	$updateSQL .= "tbl_company ON tbl_company_branch.tbl_company_idtbl_company=tbl_company.idtbl_company LEFT OUTER JOIN (";
	
	$updateSQL .= "select 1 AS rows_type, 'CA' AS cols_text) AS drv_paytype ON drv_main.cash_type=drv_paytype.rows_type CROSS JOIN (";
	
	$updateSQL .= "select @row_number:=0) AS t HAVING accamount>0";
	
	$stmt = $conn->prepare($updateSQL);
	$stmt->bind_param("ssss", $userID, $head_k, $head_k, $head_k);
	
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($tbl_master_idtbl_master, $trano, $traid, $seqno, $acccode, $accamount, $crdr, $narration, $totamount, $tradate, $paytype, $refid, $status, $refno, $cheque_no, $doc_detail_id, $updatedatetime, $ismatched, $branchcode, $companycode, $tbl_user_idtbl_user);
	/*
	$ResultOut = $stmt->execute();
	
	$affectedRowCnt = $conn->affected_rows;
	
	if($affectedRowCnt>=2){
		$receipt_complete = 1;
	}else{
		$flag = false;
	}
	*/
	if($stmt->num_rows>=2){
		$stmt->fetch();
		
		do{
			$insertSQL = "INSERT INTO tbl_account_transaction (tbl_master_idtbl_master, trano, tratype, seqno, acccode, accamount, crdr, narration, totamount, tradate, paytype, refid, status, refno, updatedatetime, ismatched, branchcode, companycode, tbl_user_idtbl_user) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmtInsert = $conn->prepare($insertSQL);
			$stmtInsert->bind_param('sssssssssssssssssss', $tbl_master_idtbl_master, $trano, $traid, $seqno, $acccode, $accamount, $crdr, $narration, $totamount, $tradate, $paytype, $refid, $status, $refno, $updatedatetime, $ismatched, $branchcode, $companycode, $tbl_user_idtbl_user);
			
			$ResultOut = $stmtInsert->execute();
			$affectedRowCnt = $conn->affected_rows;
			$idtbl_account_transaction = $stmtInsert->insert_id;
			
			if(($paytype=='CH') && ($doc_detail_id!='-1')){
				$insertSQL = "INSERT INTO tbl_gl_account_transaction_details (idtbl_account_transaction, tratype, paytype, crdr, cheque_no, doc_detail_id) VALUES (?, ?, ?, ?, ?, ?)";
				
				$affectedRowCnt = 0;
				
				$stmtDoc = $conn->prepare($insertSQL);
				$stmtDoc->bind_param('ssssss', $idtbl_account_transaction, $traid, $paytype, $crdr, $cheque_no, $doc_detail_id);
				$ResultOut = $stmtDoc->execute();
				$affectedRowCnt = $conn->affected_rows;
			}
			
			/*
			if($affectedRowCnt>=2){
			*/
			if($affectedRowCnt==1){
				//$receipt_complete = 1;
			}else{
				$flag = false;
			}
			
		}while($stmt->fetch());
	}else{
		$flag = false;
	}
	
	
}

$actionObj=new stdClass();

if ($flag) {
	$conn->commit();
	$receipt_complete = 1;
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