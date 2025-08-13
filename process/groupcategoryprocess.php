<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$category=addslashes($_POST['category']);
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_group_category`(`category`, `status`, `insertdatetime`, `tbl_user_idtbl_user`) VALUES ('$category','1','$updatedatetime','$userID')";
    if($conn->query($insert)==true){        
        header("Location:../groupcategory.php?action=4");
    }
    else{header("Location:../groupcategory.php?action=5");}
}
else{
    $update="UPDATE `tbl_group_category` SET `category`='$category',`updatedatetime`='$updatedatetime',`updateuser`='$userID' WHERE `idtbl_group_category`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../groupcategory.php?action=6");
    }
    else{header("Location:../groupcategory.php?action=5");}
}
?>