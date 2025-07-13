<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$mainarea=$_POST['mainarea'];
$product=$_POST['product'];
$newprice=$_POST['newprice'];
$refillprice=$_POST['refillprice'];
$emptyprice=$_POST['emptyprice'];
$discountprice=$_POST['discountprice'];
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_areawise_product`(`newsaleprice`, `refillsaleprice`, `emptysaleprice`, `discountedprice`, `status`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_main_area_idtbl_main_area`) VALUES ('$newprice','$refillprice','$emptyprice','$discountprice','1','$userID','$product','$mainarea')";
    if($conn->query($insert)==true){        
        header("Location:../areawiseproduct.php?action=4");
    }
    else{header("Location:../areawiseproduct.php?action=5");}
}
else{
    $update="UPDATE `tbl_areawise_product` SET `newsaleprice`='$newprice',`refillsaleprice`='$refillprice',`emptysaleprice`='$emptyprice',`discountedprice`='$discountprice',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID',`tbl_product_idtbl_product`='$product' ,`tbl_main_area_idtbl_main_area`='$mainarea' WHERE `idtbl_areawise_product`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../areawiseproduct.php?action=6");
    }
    else{header("Location:../areawiseproduct.php?action=5");}
}
?>