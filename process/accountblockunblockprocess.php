<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:../index.php");}
require_once('../connection/db.php'); 

$userID=$_SESSION['userid'];
$tableData=$_POST['tableData'];
$type=$_POST['type'];

if($type==1){$value=1;}
else if($type==2){$value=2;}
else if($type==3){$value=3;}

$updatestatus=0;

foreach($tableData as $rowaccountlist){
    $accountallocationID=$rowaccountlist['col_1'];

    $sql="UPDATE `tbl_account_allocation` SET `status`='$value',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_account_allocation`='$accountallocationID'";
    if($conn->query($sql)==true){
        $updatestatus=1;
    }
    else{
        $updatestatus=0;
    }
}


if($updatestatus==1){
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-check-circle';
    $actionObj->title='';
    $actionObj->message='Add Successfully';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='success';

    echo $actionJSON=json_encode($actionObj);
}
else{
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-exclamation-triangle';
    $actionObj->title='';
    $actionObj->message='Record Error';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='danger';

    echo $actionJSON=json_encode($actionObj);
}
?>