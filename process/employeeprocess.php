<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$empname = $_POST['empname'];
$dob = $_POST['dob'];
$empepf = $_POST['empepf'];
$empnic = $_POST['empnic'];
$empmobile = $_POST['empmobile'];
$empaddress = $_POST['empaddress'];
$emptype = $_POST['emptype'];
$useraccount = $_POST['user_account'];


$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $query = "INSERT INTO `tbl_employee`(`name`, `dob`, `epfno`, `nic`, `phone`, `address`, `useraccount_id`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_user_type_idtbl_user_type`) VALUES ('$empname','$dob','$empepf','$empnic','$empmobile','$empaddress','$useraccount','1','$updatedatetime','$userID','$emptype')";
    if($conn->query($query)==true){header("Location:../employee.php?action=4");}
    else{header("Location:../employee.php?action=5");}
}
else{
    $query = "UPDATE `tbl_employee` SET `name`='$empname',`dob`='$dob',`epfno`='$empepf',`nic`='$empnic',`phone`='$empmobile',`address`='$empaddress',`useraccount_id`='$useraccount',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID', `tbl_user_type_idtbl_user_type`='$emptype' WHERE `idtbl_employee`='$recordID'";
    if($conn->query($query)==true){header("Location:../employee.php?action=6");}
    else{header("Location:../employee.php?action=5");}
}
?>