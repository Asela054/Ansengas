<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$hiddenID=$_POST['hiddenID'];
$product=$_POST['product'];
$newprice=$_POST['newprice'];
$refillprice=$_POST['refillprice'];
$emptyprice=$_POST['emptyprice'];
$discountprice=$_POST['discountprice'];
$updatedatetime=date('Y-m-d h:i:s');

    $insert="INSERT INTO `tbl_areawise_product`(`newsaleprice`, `refillsaleprice`, `emptysaleprice`, `discountedprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_main_area_idtbl_main_area`) VALUES ('$newprice','$refillprice','$emptyprice','$discountprice','1','$updatedatetime','$userID','$product','$hiddenID')";
    if($conn->query($insert)==true){        
        header("Location:../mainarea.php?action=4");
    }
    else{header("Location:../mainarea.php?action=5");}
?>