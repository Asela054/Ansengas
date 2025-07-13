<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$code=$_POST['code'];
$subaccount=$_POST['subaccount'];
$mainclass=$_POST['mainclass'];
$subclass=$_POST['subclass'];
$mainaccount=$_POST['mainaccount'];
$accountcategory=$_POST['accountcategory'];
$updatedatetime=date('Y-m-d h:i:s');

$accountnumber=$mainclass.$subclass.$mainaccount.$code;

if($recordOption==1){
    $insert="INSERT INTO `tbl_subaccount`(`mainclasscode`, `subclasscode`, `mainaccountcode`, `code`, `subaccount`, `subaccountname`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_account_category_idtbl_account_category`) VALUES ('$mainclass','$subclass','$mainaccount','$code','$accountnumber','$subaccount','1','$updatedatetime','$userID','$accountcategory')";
    if($conn->query($insert)===true){header("Location:../account-subaccount.php?action=4");}
    else{header("Location:../account-subaccount.php?action=5");}
}
else{
    $update="UPDATE `tbl_subaccount` SET `mainclasscode`='$mainclass',`subclasscode`='$subclass',`mainaccountcode`='$mainaccount',`code`='$code',`subaccount`='$accountnumber',`subaccountname`='$subaccount',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID',`tbl_account_category_idtbl_account_category`='$accountcategory' WHERE `idtbl_subaccount`='$recordID'";
    if($conn->query($update)===true){header("Location:../account-subaccount.php?action=6");}
    else{header("Location:../account-subaccount.php?action=5");}
}
?>