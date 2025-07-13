<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$pettycashaccount=$_POST['pettycashaccount'];
$bankaccount=$_POST['bankaccount'];
$company=$_POST['company'];
$companybranch=$_POST['companybranch'];
$totalamount=$_POST['totalamount'];
$chequeno=$_POST['chequeno'];
$descvoucher=$_POST['descvoucher'];
$tableData=$_POST['tableData'];

$today=date('Y-m-d');
$updatedatetime=date('Y-m-d h:i:s');

$sqlaccountdebit="SELECT `idtbl_subaccount` FROM `tbl_subaccount` WHERE `subaccount`='$pettycashaccount' AND `status`=1";
$resultaccountdebit=$conn->query($sqlaccountdebit);
$rowaccountdebit=$resultaccountdebit->fetch_assoc();

$accountdebitID=$rowaccountdebit['idtbl_subaccount'];

$sqlaccountcredit="SELECT `idtbl_subaccount` FROM `tbl_subaccount` WHERE `subaccount`='$bankaccount' AND `status`=1";
$resultaccountcredit=$conn->query($sqlaccountcredit);
$rowaccountcredit=$resultaccountcredit->fetch_assoc();

$accountcreditID=$rowaccountcredit['idtbl_subaccount'];

$insertpettyvoucher="INSERT INTO `tbl_pettycash_voucher`(`date`, `debitaccount`, `creditaccount`, `amount`, `chequeno`, `desc`, `approvestatus`, `status`, `insertdatetime`, `updatedatetime`, `updateuser`, `tbl_user_idtbl_user`, `debitaccountid`, `creditaccountid`, `tbl_company_idtbl_company`, `tbl_company_branch_idtbl_company_branch`) VALUES ('$today','$pettycashaccount','$bankaccount','$totalamount','$chequeno','$descvoucher','0','1','$updatedatetime','','','$userID','$accountdebitID','$accountcreditID','$company','$companybranch')";

if($conn->query($insertpettyvoucher)==true){
    $pettyvoucherID=$conn->insert_id;

    foreach($tableData as $rowtabledata){
        $pettycashID=$rowtabledata['col_1'];

        $insertpettycashaccovoucher="INSERT INTO `tbl_pettycash_voucher_has_tbl_pettycash`(`tbl_pettycash_voucher_idtbl_pettycash_voucher`, `tbl_pettycash_idtbl_pettycash`) VALUES ('$pettyvoucherID','$pettycashID')";
        $conn->query($insertpettycashaccovoucher);

        $updatepettycash="UPDATE `tbl_pettycash` SET `reimbursestatus`='1',`updatedatetime`='$updatedatetime',`updateuser`='$userID' WHERE `idtbl_pettycash`='$pettycashID'";
        $conn->query($updatepettycash);
    }

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