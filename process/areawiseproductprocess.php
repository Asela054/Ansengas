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
$encustomer_newprice=$_POST['encustomer_newprice'];
$encustomer_refillprice=$_POST['encustomer_refillprice'];
$encustomer_emptyprice=$_POST['encustomer_emptyprice'];
$discount_price=$_POST['discount_price'];
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_areawise_product`(`newsaleprice`, `refillsaleprice`, `emptysaleprice`, `encustomer_newprice`, `encustomer_refillprice`, `encustomer_emptyprice`, `discount_price`, `status`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_main_area_idtbl_main_area`) VALUES ('$newprice','$refillprice','$emptyprice','$encustomer_newprice','$encustomer_refillprice','$encustomer_emptyprice','$discount_price','1','$userID','$product','$mainarea')";
    if($conn->query($insert)==true){        
        header("Location:../areawiseproduct.php?action=4");
    }
    else{header("Location:../areawiseproduct.php?action=5");}
}
else{
    $update="UPDATE `tbl_areawise_product` SET `newsaleprice`='$newprice',`refillsaleprice`='$refillprice',`emptysaleprice`='$emptyprice',`encustomer_newprice`='$encustomer_newprice',`encustomer_refillprice`='$encustomer_refillprice',`encustomer_emptyprice`='$encustomer_emptyprice',`discount_price`='$discount_price',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID',`tbl_product_idtbl_product`='$product' ,`tbl_main_area_idtbl_main_area`='$mainarea' WHERE `idtbl_areawise_product`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../areawiseproduct.php?action=6");
    }
    else{header("Location:../areawiseproduct.php?action=5");}
}
?>