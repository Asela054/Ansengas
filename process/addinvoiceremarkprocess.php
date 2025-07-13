<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d h:i:s');

$remark=$_POST['remark'];
$hiddenID=$_POST['hiddenID'];

$sql="UPDATE `tbl_invoice` SET `remarks`='$remark',`updatedatetime`='$updatedatetime' WHERE `idtbl_invoice`='$hiddenID'";
if($conn->query($sql)==true){header("Location:../invoiceview.php?action=$type");}
else{header("Location:../invoiceview.php?action=5");}
?>