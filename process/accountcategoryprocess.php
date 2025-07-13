<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$accountcategory=addslashes($_POST['accountcategory']);
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_account_category`(`category`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$accountcategory','1','$updatedatetime','$userID')";
    if($conn->query($insert)==true){        
        header("Location:../accountcategory.php?action=4");
    }
    else{header("Location:../accountcategory.php?action=5");}
}
else{
    $update="UPDATE `tbl_account_category` SET `category`='$accountcategory',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_account_category`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../accountcategory.php?action=6");
    }
    else{header("Location:../accountcategory.php?action=5");}
}
?>