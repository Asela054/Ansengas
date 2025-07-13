<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$tableData=$_POST['tableData'];
$updatedatetime=date('Y-m-d h:i:s');

foreach($tableData as $rowaallocate){
    $accountno=$rowaallocate['col_1'];
    $companyID=$rowaallocate['col_3'];
    $branchID=$rowaallocate['col_4'];

    $insert="INSERT INTO `tbl_account_allocation`(`subaccountno`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_company_idtbl_company`, `tbl_company_branch_idtbl_company_branch`) VALUES ('$accountno','1','$updatedatetime','$userID','$companyID','$branchID')";
    if($conn->query($insert)==true){        
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
}