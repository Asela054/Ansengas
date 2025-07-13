<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:../index.php");}
require_once('../connection/db.php');

$updatedatetime=date('Y-m-d h:i:s');

$userID=$_SESSION['userid'];
$record=$_GET['record'];
$type=$_GET['type'];

if($type==1){$value=1;}
else if($type==2){$value=2;}
else if($type==3){$value=3;}

$sql="UPDATE `tbl_pettycash` SET `status`='$value',`updateuser`='$userID',`updatedatetime`='$updatedatetime' WHERE `idtbl_pettycash`='$record'";
if($conn->query($sql)==true){header("Location:../pettycash.php?action=$type");}
else{header("Location:../pettycash.php?action=5");}
?>