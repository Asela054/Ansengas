<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d h:i:s');

$record=$_GET['record'];
$type=$_GET['type'];

if($type==3){$value=3;}

$sql="UPDATE `tbl_invoice_reimbursement` SET `status`='$value',`updatedatetime`='$updatedatetime' WHERE `idtbl_invoice_reimbursement`='$record'";
if($conn->query($sql)==true){
    $sql="SELECT `tbl_invoice_idtbl_invoice` FROM `tbl_invoice_reimbursement` WHERE `idtbl_invoice_reimbursement`='$record'";
    $result =$conn-> query($sql);
    $row = $result-> fetch_assoc();

    $invoiceID=$row['tbl_invoice_idtbl_invoice'];

    $updateinvoice="UPDATE `tbl_invoice` SET `paymentcomplete`='0',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_invoice`='$invoiceID'";
    $conn->query($updateinvoice);

    header("Location:../invoicereimbursement.php?action=$type");
}
else{header("Location:../invoicereimbursement.php?action=5");}
?>