<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$holiday=$_POST['holiday'];
$holidaytitle=addslashes($_POST['holidaytitle']);
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_month_holiday`(`date`, `title`, `status`, `insertdatetime`, `tbl_user_idtbl_user`) VALUES ('$holiday','$holidaytitle','1','$updatedatetime','$userID')";
    if($conn->query($insert)==true){        
        header("Location:../holidays.php?action=4");
    }
    else{header("Location:../holidays.php?action=5");}
}
else{
    $update="UPDATE `tbl_month_holiday` SET `date`='$holiday',`title`='$holidaytitle',`updatedatetime`='$updatedatetime', `updateuser`='$userID' WHERE `idtbl_month_holiday`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../holidays.php?action=6");
    }
    else{header("Location:../holidays.php?action=5");}
}
?>