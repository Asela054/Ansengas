<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:../index.php");}
require_once('../connection/db.php');

$updatedatetime=date('Y-m-d h:i:s');

$userID=$_SESSION['userid'];
$record=$_GET['record'];
$type=$_GET['type'];

$today=date('Y-m-d');

if($type==1){$value=1;}
else if($type==2){$value=2;}
else if($type==3){$value=3;}

if($type<4){
    $sql="UPDATE `tbl_pettycash_voucher` SET `status`='$value',`updateuser`='$userID',`updatedatetime`='$updatedatetime' WHERE `idtbl_pettycash_voucher`='$record'";
    if($conn->query($sql)==true){header("Location:../pettycashreimbursement.php?action=$type");}
    else{header("Location:../pettycashreimbursement.php?action=5");}
}
else if($type==4){
    $updatevoucher="UPDATE `tbl_pettycash_voucher` SET `approvestatus`='1',`updateuser`='$userID',`updatedatetime`='$updatedatetime' WHERE `idtbl_pettycash_voucher`='$record'";
    if($conn->query($updatevoucher)==true){
        $sqlcheck="SELECT * FROM `tbl_pettycash_voucher` WHERE `idtbl_pettycash_voucher`='$record' AND `status`=1 AND `approvestatus`=1";
        $resultcheck=$conn->query($sqlcheck);
        $rowcheck=$resultcheck->fetch_assoc();

        $company=$rowcheck['tbl_company_idtbl_company'];
        $companybranch=$rowcheck['tbl_company_branch_idtbl_company_branch'];

        $sqlcompany="SELECT `code` FROM `tbl_company` WHERE `idtbl_company`='$company' AND `status`=1";
        $resultcompany=$conn->query($sqlcompany);
        $rowcompany=$resultcompany->fetch_assoc();

        $companycode=$rowcompany['code'];

        $sqlbranch="SELECT `code` FROM `tbl_company_branch` WHERE `idtbl_company_branch`='$companybranch' AND `status`=1";
        $resultbranch=$conn->query($sqlbranch);
        $rowbranch=$resultbranch->fetch_assoc();

        $branchcode=$rowbranch['code'];

        $creditaccount=$rowcheck['creditaccount'];
        $debitaccount=$rowcheck['debitaccount'];
        $amount=$rowcheck['amount'];
        $chequeno=$rowcheck['chequeno'];
        $narration=$rowcheck['desc'];

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
            $refno='P000'.$rowrefno['refid'];
        }

        $sqlmaster="SELECT `idtbl_master` FROM `tbl_master` WHERE `tbl_company_branch_idtbl_company_branch`='$companybranch' AND `status`=1";
        $resultmaster=$conn->query($sqlmaster);
        $rowmaster=$resultmaster->fetch_assoc();

        $masterID=$rowmaster['idtbl_master'];

        $paytype="CH";
        $settlecash=1;

        $sqlcheckreimburs="SELECT `closebal`, `tbl_subaccount_idtbl_subaccount` FROM `tbl_pettycash_reimburse` WHERE `accountno`='$debitaccount' AND `tbl_company_idtbl_company`='$company' AND `tbl_company_branch_idtbl_company_branch`='$companybranch' ORDER BY `idtbl_pettycash_reimburse` DESC LIMIT 1";
        $resultcheckreimburs=$conn->query($sqlcheckreimburs);
        $rowcheckreimburs=$resultcheckreimburs->fetch_assoc();

        $closebalance=$rowcheckreimburs['closebal'];
        $newclosebalance=$closebalance+$amount;
        $debitaccountID=$rowcheckreimburs['tbl_subaccount_idtbl_subaccount'];

        $insertpettyreimburse="INSERT INTO `tbl_pettycash_reimburse`(`date`, `openbal`, `reimursebal`, `closebal`, `accountno`, `chequeno`, `chequedate`, `printstatus`, `status`, `insertdatetime`, `updatedatetime`, `updateuser`, `tbl_user_idtbl_user`, `tbl_subaccount_idtbl_subaccount`, `tbl_company_idtbl_company`, `tbl_company_branch_idtbl_company_branch`) VALUES ('$today','$closebalance','0','$newclosebalance','$debitaccount','','','0','1','$updatedatetime','','','$userID','$debitaccountID','$company','$companybranch')";
        $conn->query($insertpettyreimburse);

        $insertdebittransaction="INSERT INTO `tbl_account_transaction`(`trano`, `tratype`, `refid`, `refno`, `seqno`, `crdr`, `acccode`, `accamount`, `narration`, `totamount`, `tradate`, `paytype`, `receiptinvno`, `ismatched`, `companycode`, `branchcode`, `reversstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_master_idtbl_master`) VALUES ('$trano','P','$refid','$refno','1','D','$debitaccount','$amount','$narration','$amount','$today','$paytype','','0','$companycode','$branchcode','0','1','$updatedatetime','$userID','$masterID')";
        $conn->query($insertdebittransaction);

        $accountrasID=$conn->insert_id;

        $insertcredittransaction="INSERT INTO `tbl_account_transaction`(`trano`, `tratype`, `refid`, `refno`, `seqno`, `crdr`, `acccode`, `accamount`, `narration`, `totamount`, `tradate`, `paytype`, `receiptinvno`, `ismatched`, `companycode`, `branchcode`, `reversstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_master_idtbl_master`) VALUES ('$trano','P','$refid','$refno','0','C','$creditaccount','$amount','$narration','$amount','$today','$paytype','','0','$companycode','$branchcode','0','1','$updatedatetime','$userID','$masterID')";
        $conn->query($insertcredittransaction);

        if($paytype=='CH'){
            $insertchequeinfo="INSERT INTO `tbl_gl_account_transaction_details`(`idtbl_account_transaction`, `tratype`, `paytype`, `crdr`, `cheque_no`, `doc_detail_id`) VALUES ('$accountrasID','R','$paytype','D','$chequeno','')";
            $conn->query($insertchequeinfo);
        }

        header("Location:../pettycashreimbursement.php?action=1");
    }
    else{header("Location:../pettycashreimbursement.php?action=5");}
}

?>