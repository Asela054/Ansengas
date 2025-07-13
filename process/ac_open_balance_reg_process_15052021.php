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

$updateSQL = "INSERT INTO tbl_gl_account_balance_details (ac_balance_reg_code, idtbl_subaccount, idtbl_account_allocation, idtbl_financial_year, tbl_master_idtbl_master, subaccount, ac_open_balance, created_by, created_at) SELECT md5(?) AS ac_balance_reg_code, idtbl_subaccount, ? AS idtbl_account_allocation, ? AS idtbl_financial_year, ? AS tbl_master_idtbl_master, subaccount, ? AS ac_open_balance, ? AS created_by, NOW() AS created_at FROM tbl_subaccount WHERE subaccount=?";
$stmt = $conn->prepare($updateSQL);
$stmt->bind_param("sssssss", $acBalanceRegCode, $openAccount, $financialYear, $masterRefno, $openAmount, $userID, $openAccountColcode);
$ResultOut = $stmt->execute();

$affectedRowCnt = $conn->affected_rows;

if($affectedRowCnt==1){
	$part_k = $stmt->insert_id;
}else{
	$flag = false;
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