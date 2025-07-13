<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d h:i:s');

$date_company=$_POST['date_company'];
$hiddenID=$_POST['hiddenID'];

$sql="UPDATE `tbl_damage_return` SET `comsendstatus`='1',`comsenddate`='$date_company',`updatedatetime`='$updatedatetime' WHERE `idtbl_damage_return`='$hiddenID'";
if($conn->query($sql)==true){header("Location:../damagereturn.php?action=6");}
else{header("Location:../damagereturn.php?action=5");}
?>