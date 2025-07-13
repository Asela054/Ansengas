<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$querystatus=0;

$company=$_POST['company'];
$companybranch=$_POST['companybranch'];
$tableData=$_POST['tableData'];

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

foreach($tableData as $rowtabledata){
    $pettyID=$rowtabledata['col_1'];

    $sqlpettyinfo="SELECT * FROM `tbl_pettycash` WHERE `idtbl_pettycash`='$pettyID' AND `status`=1";
    $resultpettyinfo=$conn->query($sqlpettyinfo);
    $rowpettyinfo=$resultpettyinfo->fetch_assoc();

    $creditaccount=$rowpettyinfo['pettyaccount'];
    $debitaccount=$rowpettyinfo['debitaccount'];
    $amount=$rowpettyinfo['amount'];

    $sqlaccountdebit="SELECT `idtbl_subaccount` FROM `tbl_subaccount` WHERE `subaccount`='$debitaccount' AND `status`=1";
    $resultaccountdebit=$conn->query($sqlaccountdebit);
    $rowaccountdebit=$resultaccountdebit->fetch_assoc();

    $accountdebitID=$rowaccountdebit['idtbl_subaccount'];

    $sqltrano="SELECT `idtbl_account_transaction` FROM `tbl_account_transaction` ORDER BY `idtbl_account_transaction` DESC LIMIT 1";
    $resulttrano=$conn->query($sqltrano);
    $rowtrano=$resulttrano->fetch_assoc();

    if(empty($rowtrano['idtbl_account_transaction'])){
        $trano='P0001';
    }
    else{
        $trano='P000'.($rowtrano['idtbl_account_transaction']+1);
    }

    $sqlrefno="SELECT `refid` FROM `tbl_account_transaction` ORDER BY `refid` DESC LIMIT 1";
    $resultrefno=$conn->query($sqlrefno);
    $rowrefno=$resultrefno->fetch_assoc();
    
    if(empty($rowrefno['refid'])){
        $refid='1';
        $refno='P0001';
    }
    else{
        $refid=($rowrefno['refid']+1);
        $refno='P000'.$refid;
    }

    $sqlcheckreimburs="SELECT `closebal`, `tbl_subaccount_idtbl_subaccount` FROM `tbl_pettycash_reimburse` WHERE `accountno`='$creditaccount' AND `tbl_company_idtbl_company`='$company' AND `tbl_company_branch_idtbl_company_branch`='$companybranch' ORDER BY `idtbl_pettycash_reimburse` DESC LIMIT 1";
    $resultcheckreimburs=$conn->query($sqlcheckreimburs);
    $rowcheckreimburs=$resultcheckreimburs->fetch_assoc();

    $closebalance=$rowcheckreimburs['closebal'];
    $newclosebalance=$closebalance-$amount;
    $creditaccountID=$rowcheckreimburs['tbl_subaccount_idtbl_subaccount'];

    $insertpettyreimburse="INSERT INTO `tbl_pettycash_reimburse`(`date`, `openbal`, `reimursebal`, `closebal`, `accountno`, `chequeno`, `chequedate`, `printstatus`, `status`, `insertdatetime`, `updatedatetime`, `updateuser`, `tbl_user_idtbl_user`, `tbl_subaccount_idtbl_subaccount`, `tbl_company_idtbl_company`, `tbl_company_branch_idtbl_company_branch`) VALUES ('$today','$closebalance','0','$newclosebalance','$creditaccount','','','0','1','$updatedatetime','','','$userID','$creditaccountID','$company','$companybranch')";
    $conn->query($insertpettyreimburse);

    $updatepettycash="UPDATE `tbl_pettycash` SET `transcode`='$trano', `refcode`='$refno', `poststatus`='1' WHERE `idtbl_pettycash`='$pettyID' AND `status`=1";
    $conn->query($updatepettycash);

    $sqlmaster="SELECT `idtbl_master` FROM `tbl_master` WHERE `tbl_company_branch_idtbl_company_branch`='$companybranch' AND `status`=1";
    $resultmaster=$conn->query($sqlmaster);
    $rowmaster=$resultmaster->fetch_assoc();

    $masterID=$rowmaster['idtbl_master'];

    $paytype="CA";
    $settlecash=1;

    $insertdebittransaction="INSERT INTO `tbl_account_transaction`(`trano`, `tratype`, `refid`, `refno`, `seqno`, `crdr`, `acccode`, `accamount`, `narration`, `totamount`, `tradate`, `paytype`, `receiptinvno`, `ismatched`, `companycode`, `branchcode`, `reversstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_master_idtbl_master`) VALUES ('$trano','P','$refid','$refno','1','D','$debitaccount','$amount','','$amount','$today','$paytype','','0','$companycode','$branchcode','0','1','$updatedatetime','$userID','$masterID')";
    $conn->query($insertdebittransaction);

    $insertcredittransaction="INSERT INTO `tbl_account_transaction`(`trano`, `tratype`, `refid`, `refno`, `seqno`, `crdr`, `acccode`, `accamount`, `narration`, `totamount`, `tradate`, `paytype`, `receiptinvno`, `ismatched`, `companycode`, `branchcode`, `reversstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_master_idtbl_master`) VALUES ('$trano','P','$refid','$refno','0','C','$creditaccount','$amount','','$amount','$today','$paytype','','0','$companycode','$branchcode','0','1','$updatedatetime','$userID','$masterID')";
    $conn->query($insertcredittransaction);

    if($insertcredittransaction && $insertdebittransaction){
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