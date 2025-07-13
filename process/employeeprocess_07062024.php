<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$empname = $_POST['empname'];
$empepf = $_POST['empepf'];
$empnic = $_POST['empnic'];
$empmobile = $_POST['empmobile'];
$empaddress = $_POST['empaddress'];
$emptype = $_POST['emptype'];

$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $query = "INSERT INTO `tbl_employee`(`name`, `epfno`, `nic`, `phone`, `address`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_user_type_idtbl_user_type`) VALUES ('$empname','$empepf','$empnic','$empmobile','$empaddress','1','$updatedatetime','$userID','$emptype')";
    if($conn->query($query)==true){header("Location:../employee.php?action=4");}
    else{header("Location:../employee.php?action=5");}
}
else{
    $query = "UPDATE `tbl_employee` SET `name`='$empname',`epfno`='$empepf',`nic`='$empnic',`phone`='$empmobile',`address`='$empaddress',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID', `tbl_user_type_idtbl_user_type`='$emptype' WHERE `idtbl_employee`='$recordID'";
    if($conn->query($query)==true){header("Location:../employee.php?action=6");}
    else{header("Location:../employee.php?action=5");}
}
?>