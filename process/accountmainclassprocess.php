<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$code=$_POST['code'];
$classname=$_POST['classname'];
$accounttype=$_POST['accounuttype'];
$transactiontype=$_POST['transactiontype'];
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_mainclass`(`code`, `class`, `accounttype`, `transactiontype`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$code','$classname','$accounttype','$transactiontype','1','$updatedatetime','$userID')";
    if($conn->query($insert)===true){header("Location:../account-mainclass.php?action=4");}
    else{header("Location:../account-mainclass.php?action=5");}
}
else{
    $update="UPDATE `tbl_mainclass` SET `code`='$code',`class`='$classname',`accounttype`='$accounttype',`transactiontype`='$transactiontype',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_mainclass`='$recordID'";
    if($conn->query($update)===true){header("Location:../account-mainclass.php?action=6");}
    else{header("Location:../account-mainclass.php?action=5");}
}
?>