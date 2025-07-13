<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$bank=addslashes($_POST['bank']);
$bankbranch=addslashes($_POST['bankbranch']);
$accountno=addslashes($_POST['accountno']);
$startno=addslashes($_POST['startno']);
$endno=addslashes($_POST['endno']);
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_cheque_info`(`startno`, `endno`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_bank_idtbl_bank`, `tbl_bank_branch_idtbl_bank_branch`, `tbl_bank_account_idtbl_bank_account`) VALUES ('$startno','$endno','1','$updatedatetime','$userID','$bank','$bankbranch','$accountno')";
    if($conn->query($insert)==true){        
        header("Location:../chequeinfo.php?action=4");
    }
    else{header("Location:../chequeinfo.php?action=5");}
}
else{
    $update="UPDATE `tbl_cheque_info` SET `startno`='$startno',`endno`='$endno',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID',`tbl_bank_idtbl_bank`='$bank',`tbl_bank_branch_idtbl_bank_branch`='$bankbranch',`tbl_bank_account_idtbl_bank_account`='$accountno' WHERE `idtbl_cheque_info`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../chequeinfo.php?action=6");
    }
    else{header("Location:../chequeinfo.php?action=5");}
}
?>