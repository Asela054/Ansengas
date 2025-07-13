<?php 
session_start();

$field_error_msg='';

if(!isset($_SESSION['userid'])){
	$field_error_msg='Session Expired';
}

if(!isset($_POST['open_acc'], $_POST['open_acc_colcode'])){
	$field_error_msg='Select all fields marked as required';
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

$openAccount=$_POST['open_acc'];
$openAccountColcode=$_POST['open_acc_colcode'];
$openAmount=$_POST['open_amount'];
$financialYear=$_POST['fin_year'];
$masterRefno=$_POST['fin_code'];
$acBalanceRegCode=$openAccount.'_'.$financialYear;
$updatedatetime=date('Y-m-d h:i:s');




$flag = true;


$pre_sql="SELECT idtbl_subaccount, tbl_account_category_idtbl_account_category FROM tbl_subaccount WHERE subaccount=?";
$stmtReg = $conn->prepare($pre_sql);
$stmtReg->bind_param('s', $openAccountColcode);
$stmtReg->execute();
$stmtReg->store_result();
$reg_cnt = $stmtReg->num_rows;
$stmtReg->bind_result($idtbl_subaccount, $idtbl_account_category);
$row_rsReg = $stmtReg->fetch();
/*
$updateSQL = "INSERT INTO tbl_gl_account_balance_details (ac_balance_reg_code, idtbl_subaccount, idtbl_account_allocation, idtbl_financial_year, tbl_master_idtbl_master, subaccount, ac_open_balance, created_by, created_at) SELECT md5(?) AS ac_balance_reg_code, idtbl_subaccount, ? AS idtbl_account_allocation, ? AS idtbl_financial_year, ? AS tbl_master_idtbl_master, subaccount, ? AS ac_open_balance, ? AS created_by, NOW() AS created_at FROM tbl_subaccount WHERE subaccount=?";
*/
$updateSQL = "INSERT INTO tbl_gl_account_balance_details (ac_balance_reg_code, idtbl_subaccount, idtbl_account_allocation, idtbl_financial_year, tbl_master_idtbl_master, subaccount, ac_open_balance, created_by, created_at) VALUES (md5(?), ?, ?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($updateSQL);
$stmt->bind_param("ssssssss", $acBalanceRegCode, $idtbl_subaccount, $openAccount, $financialYear, $masterRefno, $openAccountColcode, $openAmount, $userID);
$ResultOut = $stmt->execute();

$affectedRowCnt = $conn->affected_rows;

if($affectedRowCnt==1){
	$part_k = $stmt->insert_id;
}else{
	$flag = false;
}


/*
update-petty-cash
*/
	
if($idtbl_account_category==3){
	/*
	$pre_sql="SELECT idtbl_subaccount FROM tbl_subaccount WHERE subaccount=? AND tbl_account_category_idtbl_account_category=3";
	$stmtReg = $conn->prepare($pre_sql);
	$stmtReg->bind_param('s', $openAccountColcode);
	$stmtReg->execute();
	$stmtReg->store_result();
	$reg_cnt = $stmtReg->num_rows;
	$stmtReg->bind_result($idtbl_subaccount);
	$row_rsReg = $stmtReg->fetch();
	*/
	
	//if($reg_cnt==1){
	
	$insertSQL = "INSERT INTO tbl_pettycash_reimburse (`date`, openbal, reimursebal, closebal, accountno, chequeno, chequedate, printstatus, status, insertdatetime, tbl_user_idtbl_user, tbl_subaccount_idtbl_subaccount, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch) SELECT DATE(NOW()) AS `date`, 0 AS openbal, 0 AS reimursebal, ? AS closebal, subaccountno AS accountno, '' AS chequeno, DATE(NOW()) AS chequedate, 1 AS printstatus, 1 AS status, NOW() AS insertdatetime, ? AS tbl_user_idtbl_user, ? AS idtbl_subaccount, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch FROM tbl_account_allocation WHERE idtbl_account_allocation=?";
	$stmt = $conn->prepare($insertSQL);
	$stmt->bind_param("ssss", $openAmount, $userID, $idtbl_subaccount, $openAccount);
	$ResultOut = $stmt->execute();
	
	if(!($reg_cnt==$conn->affected_rows)){
		$flag = false;
	}
	
	//}
}


$actionObj=new stdClass();

if($flag){
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

$res_arr = array('msgdesc'=>$actionObj);

echo json_encode($res_arr);
//---