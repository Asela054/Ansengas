<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$code=$_POST['code'];
$accountname=$_POST['accountname'];
$mainclass=$_POST['mainclass'];
$subclass=$_POST['subclass'];
$updatedatetime=date('Y-m-d h:i:s');

$fullcode=$mainclass.$subclass.$code;

if($recordOption==1){
 $insert="INSERT INTO `tbl_mainaccount`(`mainclasscode`, `subclasscode`, `code`, `fullcode`, `accountname`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$mainclass','$subclass','$code','$fullcode','$accountname','1','$updatedatetime','$userID')";
    if($conn->query($insert)===true){header("Location:../account-mainaccount.php?action=4");}
    else{header("Location:../account-mainaccount.php?action=5");}
}
else{
    $update="UPDATE `tbl_mainaccount` SET `mainclasscode`='$mainclass',`subclasscode`='$subclass',`code`='$code',`fullcode`='$fullcode',`accountname`='$accountname',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_mainaccount`='$recordID'";
    if($conn->query($update)===true){header("Location:../account-mainaccount.php?action=6");}
    else{header("Location:../account-mainaccount.php?action=5");}
}
?>