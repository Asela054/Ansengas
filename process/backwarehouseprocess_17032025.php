<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d h:i:s');

$date_warehouse=$_POST['date_warehouse'];
$hiddenID=$_POST['hiddenID'];

$sql="UPDATE `tbl_damage_return` SET `backstockstatus`='1',`backstockdate`='$date_warehouse',`updatedatetime`='$updatedatetime' WHERE `idtbl_damage_return`='$hiddenID'";
if($conn->query($sql)==true){header("Location:../damagereturn.php?action=6");}
else{header("Location:../damagereturn.php?action=5");}
?>