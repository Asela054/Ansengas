<?php 
session_start();

$field_error_msg='';

if(!isset($_SESSION['userid'])){
	$field_error_msg='Session Expired';
}

if(!isset($_POST['deposit_branch_colcode'], 
			$_POST['transfer_branch_colcode'], $_POST['transfer_acc_colcode'])){
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

$depositDate=$_POST['deposit_date'];
$depositNarration=$_POST['deposit_narration'];
$depositBranch=$_POST['deposit_branch'];
$depositBranchColcode=$_POST['deposit_branch_colcode'];

$setCash=$_POST['set_cash'];
$setCheque=1-$setCash;

$transferBranch=$_POST['transfer_branch'];
$transferAccount=$_POST['transfer_acc'];
$transferBranchColcode=$_POST['transfer_branch_colcode'];
$transferAccountColcode=$_POST['transfer_acc_colcode'];

$updatedatetime=date('Y-m-d h:i:s');


$head_k = ''; /*invoice-id*/
$sub_k = '';

if(isset($_POST['ref_id'])){
	$head_k = $_POST['ref_id'];
}
/*
if(isset($_POST['sub_id'])){
	$sub_k = $_POST['sub_id'];
}
*/

$depositAmount=(($head_k!='') && ($setCheque==1))?1:$_POST['rec_amount'];


$conn->autocommit(FALSE);
$flag = true;
$resmsg = '';

if($head_k==''){
	$updateSQL = "INSERT INTO tbl_gl_bank_deposits (cash_collection, cheque_collection, branch_id, branch_code, deposit_date, deposit_narration, deposit_amount, transfer_branch_id, transfer_branch_code, transfer_subaccount, transfer_account_id, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

	$stmt = $conn->prepare($updateSQL);
	$stmt->bind_param("ssssssssssss", $setCash, $setCheque, $depositBranch, $depositBranchColcode, $depositDate, $depositNarration, $depositAmount, $transferBranch, $transferBranchColcode, $transferAccountColcode, $transferAccount, $userID);
	$ResultOut = $stmt->execute();
	
	$affectedRowCnt = $conn->affected_rows;
	
	if($affectedRowCnt==1){
		$head_k = $stmt->insert_id;
		$resmsg = '<h4>Record saved successfully.</h4><br /><h5>You can now select/deselect the receipts from the given list</h5>';
	}else{
		$flag = false;
	}
	
	$stmt->close();
	
}else{
	$cashDeposit = "UPDATE tbl_gl_bank_deposits SET branch_id=?, branch_code=?, deposit_date=?, deposit_narration=?, deposit_amount=?, transfer_branch_id=?, transfer_branch_code=?, transfer_subaccount=?, transfer_account_id=?, updated_by=?, updated_at=NOW() WHERE id=? AND cash_collection=1 AND cheque_collection=0";
	$chequeDeposit = "UPDATE tbl_gl_bank_deposits SET branch_id=?, branch_code=?, deposit_date=?, deposit_narration=?, deposit_amount=(deposit_amount*?), transfer_branch_id=?, transfer_branch_code=?, transfer_subaccount=?, transfer_account_id=?, updated_by=?, updated_at=NOW() WHERE id=? AND cash_collection=0 AND cheque_collection=1";
	
	$updateSQL = ($setCash==1)?$cashDeposit:$chequeDeposit;
	
	$stmt = $conn->prepare($updateSQL);
	$stmt->bind_param("sssssssssss", $depositBranch, $depositBranchColcode, $depositDate, $depositNarration, $depositAmount, $transferBranch, $transferBranchColcode, $transferAccountColcode, $transferAccount, $userID, $head_k);
	$ResultOut = $stmt->execute();
	
	$affectedRowCnt = $conn->affected_rows;
	
	$resmsg = '<h4>Record updated successfully.</h4>';
	
	if(!($affectedRowCnt==1)){
		$flag = false;
	}
	
	$stmt->close();
}
/*
if($setCash==1){
	if($sub_k==''){
		$updateSQL = "INSERT INTO tbl_gl_bank_deposit_details (tbl_gl_bank_deposit_id, deposit_amount) VALUES (?, ?)";
		$stmt = $conn->prepare($updateSQL);
		$stmt->bind_param("ss", $head_k, $depositAmount);
		$ResultOut = $stmt->execute();
		
		$affectedRowCnt = $conn->affected_rows;
		
		if($affectedRowCnt==1){
			$sub_k = $stmt->insert_id;
		}else{
			$flag = false;
		}
	}else{
		$updateSQL = "UPDATE tbl_gl_bank_deposit_details INNER JOIN (SELECT id, cash_collection, cheque_collection FROM tbl_gl_bank_deposits WHERE id=?) AS drv_doc ON tbl_gl_bank_deposit_details.tbl_gl_bank_deposit_id=drv_doc.id SET tbl_gl_bank_deposit_details.deposit_amount=? WHERE tbl_gl_bank_deposit_details.id=? AND drv_doc.cash_collection=1 AND drv_doc.cheque_collection=0";
		
		$stmt = $conn->prepare($updateSQL);
		$stmt->bind_param("sss", $head_k, $depositAmount, $sub_k);
		$ResultOut = $stmt->execute();
		
		$affectedRowCnt = $conn->affected_rows;
		
		if(!($affectedRowCnt==1)){
			$flag = false;
		}
		
		$stmt->close();
	}
	
}
*/
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