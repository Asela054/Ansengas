<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$hiddenID=$_POST['hiddenID2'];
$product=$_POST['product2'];
$newprice=$_POST['newprice2'];
$refillprice=$_POST['refillprice2'];
$emptyprice=$_POST['emptyprice2'];
$discountprice=$_POST['discountprice2'];
$updatedatetime=date('Y-m-d h:i:s');

    $update="UPDATE `tbl_areawise_product` SET `newsaleprice`=$newprice,`refillsaleprice`=$refillprice,`emptysaleprice`=$emptyprice,`discountedprice`=$discountprice,`updatedatetime`=$updatedatetime,`tbl_user_idtbl_user`=$userID WHERE `tbl_areawise_product`='$hiddenID'";
    if($conn->query($update)==true){     
        header("Location:../mainarea.php?action=6");
    }
    else{header("Location:../mainarea.php?action=5");}
?>