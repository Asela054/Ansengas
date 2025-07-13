<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:../index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$record=$_GET['record'];
$type=$_GET['type'];

$today=date('Y-m-d');

if($type==1){$value=1;}
else if($type==2){$value=2;}
else if($type==3){$value=3;}

if($type==4){
    $sql="UPDATE `tbl_cutomer_target` SET `tbl_user_idtbl_user`='$userID' WHERE `idtbl_cutomer_target`='$record'";
}
else{
    $sql="UPDATE `tbl_cutomer_target` SET `status`='$value',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_cutomer_target`='$record'";
}

if($conn->query($sql)==true){header("Location:../customertarget.php?action=$type");}
else{header("Location:../customertarget.php?action=5");}