<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$area=$_POST['area'];
$targetmonth=$_POST['targetmonth'];
$targetmonth = date("Y-m-d", strtotime($targetmonth));
$product=$_POST['product'];
$target=$_POST['target'];
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_area_target`(`month`, `targettank`, `targetcomplete`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`, `tbl_product_idtbl_product`) VALUES ('$targetmonth','$target','0','1','$updatedatetime','$userID','$area','$product')";
    if($conn->query($insert)==true){        
        header("Location:../areatarget.php?action=4");
    }
    else{header("Location:../areatarget.php?action=5");}
}
else{
    $update="UPDATE `tbl_area_target` SET `month`='$targetmonth',`targettank`='$target',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID',`tbl_area_idtbl_area`='$area',`tbl_product_idtbl_product`='$product' WHERE `idtbl_area_target`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../areatarget.php?action=6");
    }
    else{header("Location:../areatarget.php?action=5");}
}
?>