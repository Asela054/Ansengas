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


$head_k = ''; /*section-id*/

$rptSectGroup=$_POST['grp_id'];
$rptSectName=$_POST['sect_name'];

$flag = true;
$resmsg = '';

/*
if($flag){
	
}
*/

$updateSQL = "INSERT INTO tbl_gl_report_sub_sections (tbl_gl_report_head_section_id, sub_section_name, created_by, created_at) VALUES (?, ?, ?, NOW())";

$stmt = $conn->prepare($updateSQL);
$stmt->bind_param("sss", $rptSectGroup, $rptSectName, $userID);
$ResultOut = $stmt->execute();

$affectedRowCnt = $conn->affected_rows;


if($affectedRowCnt==1){
	$head_k = $stmt->insert_id;
	$resmsg = '<h5>Section added successfully</h5>';
}else{
	$flag = false;
}


$stmt->close();



$actionObj=new stdClass();

if ($flag) {
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

$res_arr = array('msgdesc'=>$actionObj, 'head_k'=>$head_k);

echo json_encode($res_arr);
//---