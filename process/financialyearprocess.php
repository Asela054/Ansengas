<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$year=$_POST['year'];
$startdate=$_POST['startdate'];
$enddate=$_POST['enddate'];
$description=$_POST['description'];
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
  $insert="INSERT INTO `tbl_finacial_year`(`year`, `startdate`, `enddate`, `desc`, `actstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`)
 VALUES ('$year','$startdate','$enddate','$description','0',1,'$updatedatetime','$userID')";
    if($conn->query($insert)===true){
       //
         header("Location:../financialyear.php?action=4");
    }
    else{
         header("Location:../financialyear.php?action=5");
    }
}
else{

    $update="UPDATE `tbl_finacial_year` SET `year`='$year',`startdate` = '$startdate',`enddate`='$enddate',`desc`='$description',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user` = $userID WHERE `idtbl_finacial_year` = $recordID";

    if($conn->query($update)===true){
        header("Location:../financialyear.php?action=6");
    }
    else{
        header("Location:../financialyear.php?action=5");
    }
}
?>