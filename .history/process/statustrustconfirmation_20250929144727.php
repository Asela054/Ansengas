<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d h:i:s');

$record=$_GET['record'];
$type=$_GET['type'];

if($type==3){$value=3;}

$sql="UPDATE `tbl_local_purchase` SET `status`='$value',`updatedatetime`='$updatedatetime' WHERE `idtbl_local_purchase`='$record'";
if($conn->query($sql)==true){header("Location:../localpurchase.php?action=$type");}
else{header("Location:../localpurchase.php?action=5");}
?>