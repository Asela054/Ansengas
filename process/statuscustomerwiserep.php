<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:../index.php");}
require_once('../connection/db.php'); 

$userID=$_SESSION['userid'];
$record=$_GET['record'];
$type=$_GET['type'];

if($type==1){$value=1;}
else if($type==2){$value=2;}
else if($type==3){$value=3;}

$sql="UPDATE `tbl_customerwise_salesrep` SET `status`='$value',`updateuser`='$userID' WHERE `idtbl_customerwise_salesrep`='$record'";
if($conn->query($sql)==true){header("Location:../customerwiserep.php?action=$type");}
else{header("Location:../customerwiserep.php?action=5");}
?>