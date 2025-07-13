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
$deposit_complete=0;

$updatedatetime=date('Y-m-d h:i:s');


$head_k = ''; /*invoice-id*/

if(isset($_POST['ref_id'])){
	$head_k = $_POST['ref_id'];
}

//echo '>>'.$head_k.'--'.$userID;

$conn->autocommit(FALSE);
$flag = true;

if($head_k!=''){
	$updateSQL = "UPDATE tbl_gl_bank_deposits SET deposit_complete=1, updated_by=?, updated_at=NOW() WHERE id=? AND deposit_complete=0";

	$stmt = $conn->prepare($updateSQL);
	$stmt->bind_param("ss", $userID, $head_k);
	$ResultOut = $stmt->execute();
	
	$affectedRowCnt = $conn->affected_rows;
	
	if($affectedRowCnt==1){
		//$deposit_complete=1;
	}else{
		$flag = false;
	}
	
	$stmt->close();


	/*
	master-id, trn-no, trn-id[r/./.], seq-no[0,1,2,.], acc-code, acc-amount, dr-cr[c/d], narration, total-amount, trn-date, pay-type[ca/.], cancel[0], ref-no, user-id, sys-date, is-matched[0], company-code
	
	
	select COALESCE(drv_c.branch_id, drv_main.credit_branch_id) AS branch_id, drv_main.deposit_amount, COALESCE(drv_c.acccode, drv_main.credit_account_id, 'ASCA00010001') AS acccode, drv_c.crdr from (
	
	select branch_id as credit_branch_id, NULL as credit_account_id, (deposit_amount*cash_collection) AS deposit_amount FROM tbl_gl_bank_deposits where id=40 and deposit_complete=0
	union all 
	select drv_k.credit_branch_id, drv_k.credit_account_id, (drv_h.deposit_amount*(1-drv_h.settle_by_cash)) AS deposit_amount from (
	select id FROM tbl_gl_bank_deposits where id=40 and deposit_complete=0
	) AS drv_d INNER JOIN (
	select tbl_gl_bank_deposit_id, tbl_gl_receipt_detail_id, receipt_account_id AS credit_account_id, receipt_branch_id AS credit_branch_id FROM tbl_gl_bank_deposit_receipt_details WHERE tbl_gl_bank_deposit_id=40 AND deposit_cancel=0
	) AS drv_k ON drv_d.id=drv_k.tbl_gl_bank_deposit_id INNER JOIN (
	select id, received_amount AS deposit_amount, settle_by_cash from tbl_gl_receipt_details
	) AS drv_h ON drv_k.tbl_gl_receipt_detail_id=drv_h.id 
	
	) AS drv_main 
	CROSS JOIN (
	select NULL AS acccode, NULL AS branch_id, 'C' AS crdr 
	UNION ALL 
	select transfer_account_id AS acccode, transfer_branch_id AS branch_id, 'D' AS crdr from tbl_gl_bank_deposits wHERE id=40
	) AS drv_c
	#where drv_main.deposit_amount>0
	




	*/
	/*
	$updateSQL = "INSERT INTO tbl_account_transaction (tbl_master_idtbl_master, trano, tratype, seqno, acccode, accamount, crdr,  narration, totamount, tradate, paytype, refid, status, refno, updatedatetime, ismatched, branchcode, companycode, tbl_user_idtbl_user) ";
	*/
	$updateSQL = "select drv_year.tbl_master_idtbl_master, drv_main.trano_refno AS trano, 'D' AS traid, (@row_number:=@row_number+1)-1 AS seqno, COALESCE(drv_c.acccode, drv_main.credit_account_id, 'ASCA00010001') AS acccode, drv_main.deposit_amount AS accamount, drv_c.crdr, drv_main.narration, drv_main.totamount, drv_main.tradate, IFNULL(drv_paytype.cols_text, 'CH') AS paytype, 0 AS refid, 0 as status, IFNULL(drv_main.par_refno, drv_main.trano_refno) AS refno, drv_main.cheque_no, drv_main.doc_detail_id, drv_main.systemdate as updatedatetime, 0 as ismatched, tbl_company_branch.code as branchcode, tbl_company.code AS companycode, ? AS tbl_user_idtbl_user from (";
	
	$updateSQL .= "select CONCAT('D', SUBSTRING(CONCAT('00000000', id), -9, 9)) AS trano_refno, NULL AS par_refno, NULL AS cheque_no, NULL AS doc_detail_id, branch_id as credit_branch_id, NULL as credit_account_id, cash_collection AS cash_type, deposit_narration AS narration, DATE(created_at) AS tradate, DATE(NOW()) AS systemdate, (deposit_amount*cash_collection) AS deposit_amount, deposit_amount AS totamount FROM tbl_gl_bank_deposits where id=? UNION ALL ";
	
	$updateSQL .= "select CONCAT('D', SUBSTRING(CONCAT('00000000', drv_d.id), -9, 9)) AS trano_refno, BINARY(drv_refs.refno) AS par_refno, drv_h.cheque_no, drv_h.id AS doc_detail_id, drv_k.credit_branch_id, drv_k.credit_account_id, drv_d.cash_collection AS cash_type, drv_d.deposit_narration AS narration, DATE(drv_d.created_at) AS tradate, DATE(NOW()) AS systemdate, (drv_h.deposit_amount*(1-drv_h.settle_by_cash)) AS deposit_amount, drv_d.deposit_amount AS totamount from (";
	
	$updateSQL .= "select id, deposit_narration, deposit_amount, cash_collection, created_at FROM tbl_gl_bank_deposits where id=?) AS drv_d INNER JOIN (";
	
	$updateSQL .= "select tbl_gl_bank_deposit_id, tbl_gl_receipt_detail_id, transaction_subaccount AS credit_account_id, receipt_branch_id AS credit_branch_id FROM tbl_gl_bank_deposit_receipt_details WHERE tbl_gl_bank_deposit_id=? AND deposit_cancel=0) AS drv_k ON drv_d.id=drv_k.tbl_gl_bank_deposit_id INNER JOIN (";
	
	
	$updateSQL .= "select id, tbl_gl_receipt_id, received_amount AS deposit_amount, settle_by_cash, cheque_no from tbl_gl_receipt_details) AS drv_h ON drv_k.tbl_gl_receipt_detail_id=drv_h.id INNER JOIN (select id AS recs_id, refno from tbl_gl_receipts where receipt_complete=1) AS drv_refs ON drv_h.tbl_gl_receipt_id=drv_refs.recs_id) AS drv_main CROSS JOIN (";
	/*
	//select * from (select id, tbl_gl_receipt_id, received_amount AS deposit_amount, settle_by_cash from tbl_gl_receipt_details) AS drv_amts inner join (select id, refno from tbl_gl_receipts where receipt_complete=1) as drv_refs on drv_amts.tbl_gl_receipt_id=drv_refs.id
	*/
	
	
	$updateSQL .= "select NULL AS acccode, NULL AS branch_id, 'C' AS crdr UNION ALL ";
	
	$updateSQL .= "select transfer_subaccount AS acccode, transfer_branch_id AS branch_id, 'D' AS crdr from tbl_gl_bank_deposits WHERE id=?) AS drv_c INNER JOIN (";
	
	$updateSQL .= "select idtbl_master AS tbl_master_idtbl_master, tbl_company_branch_idtbl_company_branch AS branch_id from tbl_master where status=1) AS drv_year ON COALESCE(drv_c.branch_id, drv_main.credit_branch_id)=drv_year.branch_id INNER JOIN ";
	
	$updateSQL .= "tbl_company_branch ON drv_year.branch_id=tbl_company_branch.idtbl_company_branch INNER JOIN ";
	
	$updateSQL .= "tbl_company ON tbl_company_branch.tbl_company_idtbl_company=tbl_company.idtbl_company LEFT OUTER JOIN (";
	
	$updateSQL .= "select 1 AS rows_type, 'CA' AS cols_text) AS drv_paytype ON drv_main.cash_type=drv_paytype.rows_type CROSS JOIN (";
	
	$updateSQL .= "select @row_number:=0) AS t where drv_main.deposit_amount>0";
	
	$stmt = $conn->prepare($updateSQL);
	$stmt->bind_param("sssss", $userID, $head_k, $head_k, $head_k, $head_k);
	
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($tbl_master_idtbl_master, $trano, $traid, $seqno, $acccode, $accamount, $crdr, $narration, $totamount, $tradate, $paytype, $refid, $status, $refno, $cheque_no, $doc_detail_id, $updatedatetime, $ismatched, $branchcode, $companycode, $tbl_user_idtbl_user);
	
	if($stmt->num_rows>=2){
		$stmt->fetch();
		
		do{
			$insertSQL = "INSERT INTO tbl_account_transaction (tbl_master_idtbl_master, trano, tratype, seqno, acccode, accamount, crdr, narration, totamount, tradate, paytype, refid, status, refno, updatedatetime, ismatched, branchcode, companycode, tbl_user_idtbl_user) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmtInsert = $conn->prepare($insertSQL);
			$stmtInsert->bind_param('sssssssssssssssssss', $tbl_master_idtbl_master, $trano, $traid, $seqno, $acccode, $accamount, $crdr, $narration, $totamount, $tradate, $paytype, $refid, $status, $refno, $updatedatetime, $ismatched, $branchcode, $companycode, $tbl_user_idtbl_user);
			
			$ResultOut = $stmtInsert->execute();
			$affectedRowCnt = $conn->affected_rows;
			$idtbl_account_transaction = $stmtInsert->insert_id;
			
			if($paytype=='CH'){
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
	$deposit_complete=1;
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

$res_arr = array('msgdesc'=>$actionObj, 'rec_complete'=>$deposit_complete);

echo json_encode($res_arr);
//---