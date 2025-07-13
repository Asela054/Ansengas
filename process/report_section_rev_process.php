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

$updatedatetime=date('Y-m-d h:i:s');



$sub_k = $_POST['conf_refid'];

$confCancel=$_POST['detail_cancel'];


$flag = true;
$resmsg = '';
/*
$pre_deposit_cnt=1;

$preDeposits = "SELECT COUNT(*) AS pre_deposit_cnt FROM tbl_gl_bank_deposit_receipt_details WHERE tbl_gl_receipt_detail_id=? AND tbl_gl_bank_deposit_id<>? AND deposit_cancel=0";
$stmtDeposit = $conn->prepare($preDeposits);
$stmtDeposit->bind_param('ss', $receiptRefNo, $head_k);
$stmtDeposit->execute();
$stmtDeposit->store_result();

if($stmtDeposit->num_rows==1){
	$stmtDeposit->bind_result($pre_deposit_cnt);
	$row_preDeposits = $stmtDeposit->fetch();
}else{
	$flag=false;
}

if($pre_deposit_cnt==1){
	$flag=false;
}
*/

if($flag){
	if($sub_k!=''){
		$updateSQL = "UPDATE tbl_gl_report_sub_sections SET sect_cancel=?, updated_by=?, updated_at=NOW() WHERE id=?";
		
		$stmt = $conn->prepare($updateSQL);
		$stmt->bind_param("sss", $confCancel, $userID, $sub_k);
		$ResultOut = $stmt->execute();
		
		$affectedRowCnt = $conn->affected_rows;
		
		$resmsg = ($confCancel==0)?'<h5>Section added to selected section</h5>':'<h5>Section updated successfully</h5>';
		
		if(!($affectedRowCnt==1)){
			$flag = false;
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

$res_arr = array('msgdesc'=>$actionObj, 'sub_k'=>$sub_k);

echo json_encode($res_arr);
//---