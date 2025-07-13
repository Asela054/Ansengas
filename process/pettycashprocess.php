<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$pettycashaccount=$_POST['pettycashaccount'];
$creditaccount=$pettycashaccount;
$pettydate=$_POST['pettydate'];
$openbal=$_POST['openbal'];
$company=$_POST['company'];
$companybranch=$_POST['companybranch'];
$tableData=$_POST['tableData'];

$querystatus=0;

$today=date('Y-m-d');
$updatedatetime=date('Y-m-d h:i:s');

$sqlcompany="SELECT `code` FROM `tbl_company` WHERE `idtbl_company`='$company' AND `status`=1";
$resultcompany=$conn->query($sqlcompany);
$rowcompany=$resultcompany->fetch_assoc();

$companycode=$rowcompany['code'];

$sqlbranch="SELECT `code` FROM `tbl_company_branch` WHERE `idtbl_company_branch`='$companybranch' AND `status`=1";
$resultbranch=$conn->query($sqlbranch);
$rowbranch=$resultbranch->fetch_assoc();

$branchcode=$rowbranch['code'];

$sqlaccountcredit="SELECT `idtbl_subaccount` FROM `tbl_subaccount` WHERE `subaccount`='$pettycashaccount' AND `status`=1";
$resultaccountcredit=$conn->query($sqlaccountcredit);
$rowaccountcredit=$resultaccountcredit->fetch_assoc();

$accountcreditID=$rowaccountcredit['idtbl_subaccount'];

foreach($tableData as $rowtabledata){
    $debitaccount=$rowtabledata['col_1'];
    $amount=$rowtabledata['col_3'];
    $narration=$rowtabledata['col_5'];

    $sqlaccountdebit="SELECT `idtbl_subaccount` FROM `tbl_subaccount` WHERE `subaccount`='$debitaccount' AND `status`=1";
    $resultaccountdebit=$conn->query($sqlaccountdebit);
    $rowaccountdebit=$resultaccountdebit->fetch_assoc();

    $accountdebitID=$rowaccountdebit['idtbl_subaccount'];

    $closebal=$openbal-$amount;

    $insertpettycash="INSERT INTO `tbl_pettycash`(`date`, `transcode`, `refcode`, `pettyaccount`, `openbal`, `amount`, `closebal`, `debitaccount`, `desc`, `poststatus`, `reimbursestatus`, `status`, `insertdatetime`, `updatedatetime`, `updateuser`, `tbl_user_idtbl_user`, `tbl_subaccount_idtbl_subaccount`, `tbl_company_idtbl_company`, `tbl_company_branch_idtbl_company_branch`) VALUES ('$pettydate','','','$pettycashaccount','$openbal','$amount','$closebal','$debitaccount','$narration','0','0','1','$updatedatetime','','','$userID','$accountdebitID','$company','$companybranch')";
    $conn->query($insertpettycash);

    $openbal=$closebal;

    if($insertpettycash){
        $querystatus=1;
    }
    else{
        $querystatus=0;
    }
}

if($querystatus==1){
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