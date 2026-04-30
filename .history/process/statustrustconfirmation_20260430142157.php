<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d h:i:s');

$record=$_GET['record'];
$type=$_GET['type'];

if($type==3){$value=3;}

$sql="UPDATE `tbl_trust_confirmation` SET `status`='$value',`updatedatetime`='$updatedatetime' WHERE `idtbl_trust_confirmation`='$record'";
if($conn->query($sql)==true){header("Location:../trustconfirmation.php?action=$type");}
else{header("Location:../trustconfirmation.php?action=5");}
?> 