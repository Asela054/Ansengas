<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:../index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$record=$_GET['record'];
$type=$_GET['type'];

if($type==1){$value=1;}
else if($type==2){$value=1;}

if($type==1){
    $sql="UPDATE `tbl_vehicle_load` SET `approvestatus`='$value',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_vehicle_load`='$record'";
}
else if($type==2){
    $sql="UPDATE `tbl_vehicle_load` SET `veiwallcustomerstatus`='$value',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_vehicle_load`='$record'";
}

if($conn->query($sql)==true){header("Location:../loading.php?action=1");}
else{header("Location:../loading.php?action=5");}
?>