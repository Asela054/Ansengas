<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$code=$_POST['code'];
$subclass=$_POST['subclass'];
$mainclass=$_POST['mainclass'];
$updatedatetime=date('Y-m-d h:i:s');

$fullcode=$mainclass.$code;

if($recordOption==1){
  $insert="INSERT INTO `tbl_subclass`(`mainclasscode`, `code`, `fullcode`, `subclass`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$mainclass','$code','$fullcode','$subclass','1','$updatedatetime','$userID')";
    if($conn->query($insert)===true){header("Location:../account-subclass.php?action=4");}
    else{header("Location:../account-subclass.php?action=5");}
}
else{
    $update="UPDATE `tbl_subclass` SET `mainclasscode`='$mainclass',`code`='$code',`fullcode`='$fullcode',`subclass`='$subclass',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_subclass`='$recordID'";
    if($conn->query($update)===true){header("Location:../account-subclass.php?action=6");}
    else{header("Location:../account-subclass.php?action=5");}
}
?>