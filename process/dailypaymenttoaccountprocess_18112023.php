<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$querystatus=0;
$settlecash=0;
$settlecheque=0;

$creditaccount=$_POST['creditaccount'];
$debitaccount=$_POST['debitaccount'];
$receipttype=$_POST['receipttype'];
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

$sqlaccountcredit="SELECT `idtbl_subaccount` FROM `tbl_subaccount` WHERE `subaccount`='$creditaccount' AND `status`=1";
$resultaccountcredit=$conn->query($sqlaccountcredit);
$rowaccountcredit=$resultaccountcredit->fetch_assoc();

$accountcreditID=$rowaccountcredit['idtbl_subaccount'];

$sqlaccountdebit="SELECT `idtbl_subaccount` FROM `tbl_subaccount` WHERE `subaccount`='$debitaccount' AND `status`=1";
$resultaccountdebit=$conn->query($sqlaccountdebit);
$rowaccountdebit=$resultaccountdebit->fetch_assoc();

$accountdebitID=$rowaccountdebit['idtbl_subaccount'];

foreach($tableData as $rowtabledata){
    $receiptID=$rowtabledata['col_2'];
    $amount=$rowtabledata['col_5'];
    $chequeno=$rowtabledata['col_7'];
    $chequedate=$rowtabledata['col_8'];
    $bank=$rowtabledata['col_9'];
    $branch=$rowtabledata['col_10'];

    if($receipttype==3){
        $paytype="CR";

        $updatereceiptdetail="UPDATE `tbl_invoice` SET `addtoaccountstatus`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_invoice`='$receiptID'";
        $conn->query($updatereceiptdetail);
    }
    else{
        if($receipttype==1){$paytype="CA";$settlecash=1;}
        else if($receipttype==2){$paytype="CH";$settlecheque=1;}

        $updatereceiptdetail="UPDATE `tbl_invoice_payment_detail` SET `addaccountstatus`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_invoice_payment_detail`='$receiptID'";
        $conn->query($updatereceiptdetail);
    }

    $sqltrano="SELECT `idtbl_account_transaction` FROM `tbl_account_transaction` ORDER BY `idtbl_account_transaction` DESC LIMIT 1";
    $resulttrano=$conn->query($sqltrano);
    $rowtrano=$resulttrano->fetch_assoc();

    if(empty($rowtrano['idtbl_account_transaction'])){
        $trano='R0001';
    }
    else{
        $trano='R000'.($rowtrano['idtbl_account_transaction']+1);
    }

    $sqlrefno="SELECT `refid` FROM `tbl_account_transaction` ORDER BY `refid` DESC LIMIT 1";
    $resultrefno=$conn->query($sqlrefno);
    $rowrefno=$resultrefno->fetch_assoc();

    if(empty($rowrefno['refid'])){
        $refid='1';
        $refno='R0001';
    }
    else{
        $refid=($rowrefno['refid']+1);
        $refno='R000'.$rowrefno['refid'];
    }

    $sqlmaster="SELECT `idtbl_master` FROM `tbl_master` WHERE `tbl_company_branch_idtbl_company_branch`='$companybranch' AND `status`=1";
    $resultmaster=$conn->query($sqlmaster);
    $rowmaster=$resultmaster->fetch_assoc();

    $masterID=$rowmaster['idtbl_master'];

    $insertdebittransaction="INSERT INTO `tbl_account_transaction`(`trano`, `tratype`, `refid`, `refno`, `seqno`, `crdr`, `acccode`, `accamount`, `narration`, `totamount`, `tradate`, `paytype`, `receiptinvno`, `ismatched`, `companycode`, `branchcode`, `reversstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_master_idtbl_master`) VALUES ('$trano','R','$refid','$refno','1','D','$debitaccount','$amount','','$amount','$today','$paytype','$receiptID','0','$companycode','$branchcode','0','1','$updatedatetime','$userID','$masterID')";
    $conn->query($insertdebittransaction);

    $accountrasID=$conn->insert_id;

    $insertcredittransaction="INSERT INTO `tbl_account_transaction`(`trano`, `tratype`, `refid`, `refno`, `seqno`, `crdr`, `acccode`, `accamount`, `narration`, `totamount`, `tradate`, `paytype`, `receiptinvno`, `ismatched`, `companycode`, `branchcode`, `reversstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_master_idtbl_master`) VALUES ('$trano','R','$refid','$refno','0','C','$creditaccount','$amount','','$amount','$today','$paytype','$receiptID','0','$companycode','$branchcode','0','1','$updatedatetime','$userID','$masterID')";
    $conn->query($insertcredittransaction);

    if($paytype=='CH'){
        $insertchequeinfo="INSERT INTO `tbl_gl_account_transaction_details`(`idtbl_account_transaction`, `tratype`, `paytype`, `crdr`, `cheque_no`, `doc_detail_id`) VALUES ('$accountrasID','R','$paytype','D','$chequeno','')";
        $conn->query($insertchequeinfo);

    }

    $sqlcheck="SELECT COUNT(*) AS `count` FROM `tbl_gl_receipts` WHERE `refno`='$refno' AND `receipt_debit_subaccount`='$accountdebitID'";
    $resultcheck=$conn->query($sqlcheck);
    $rowcheck=$resultcheck->fetch_assoc();

    $receiptID=0;

    if($rowcheck['count']==0){
        $insertreceipt="INSERT INTO `tbl_gl_receipts`(`receipt_customer`, `receipt_category`, `receipt_head_narration`, `receipt_debit_branch`, `receipt_debit_account`, `receipt_debit_branch_code`, `receipt_debit_subaccount`, `receipt_complete`, `refno`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES ('','C','','$companybranch','$accountdebitID','$branchcode','$debitaccount','1','$refno','$userID','','$updatedatetime','')";
        $conn->query($insertreceipt);

        $receiptID=$conn->insert_id;
    }

    $insertreceiptinfo="INSERT INTO `tbl_gl_receipt_details`(`tbl_gl_receipt_id`, `receipt_sub_narration`, `receipt_credit_branch`, `receipt_credit_account`, `receipt_credit_branch_code`, `receipt_credit_subaccount`, `settle_by_cash`, `settle_by_cheque`, `cheque_no`, `cheque_date`, `cheque_bank`, `cheque_crossed`, `received_amount`, `receipt_cancel`, `bank_deposit`, `payreciptid`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES ('$receiptID','','$companybranch','$accountcreditID','$branchcode','$creditaccount','$settlecash','$settlecheque','$chequeno','$chequedate','$bank','','$amount','0','0','$receiptID','$userID','','$updatedatetime','')";
    $conn->query($insertreceiptinfo);

    if($updatereceiptdetail && $insertcredittransaction && $insertdebittransaction){
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