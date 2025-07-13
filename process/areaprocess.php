<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$area=addslashes($_POST['area']);
$mainarea=addslashes($_POST['mainarea']);
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_area`(`area`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_main_area_idtbl_main_area`) VALUES ('$area','1','$updatedatetime','$userID','$mainarea')";
    if($conn->query($insert)==true){        
        header("Location:../area.php?action=4");
    }
    else{header("Location:../area.php?action=5");}
}
else{
    $update="UPDATE `tbl_area` SET `area`='$area',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' ,`tbl_main_area_idtbl_main_area`='$mainarea'WHERE `idtbl_area`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../area.php?action=6");
    }
    else{header("Location:../area.php?action=5");}
}
?>