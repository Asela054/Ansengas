<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$customer=$_POST['customer'];
$targetmonth=$_POST['targetmonth'];
$targetmonth = date("Y-m-d", strtotime($targetmonth));
$product=$_POST['product'];
$target=$_POST['target'];
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_cutomer_target`(`month`, `targettank`, `targetcomplete`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`) VALUES ('$targetmonth','$target','0','1','$updatedatetime','$userID','$customer','$product')";
    if($conn->query($insert)==true){        
        header("Location:../customertarget.php?action=4");
    }
    else{header("Location:../customertarget.php?action=5");}
}
else{
    $update="UPDATE `tbl_cutomer_target` SET `month`='$targetmonth',`targettank`='$target',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID',`tbl_customer_idtbl_customer`='$customer',`tbl_product_idtbl_product`='$product' WHERE `idtbl_cutomer_target`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../customertarget.php?action=6");
    }
    else{header("Location:../customertarget.php?action=5");}
}
?>