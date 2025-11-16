<?php
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d H:i:s');

if(!isset($_POST['product'], $_POST['disvalue'], $_POST['hiddenID'])){
    header("Location:../customer.php?action=5");
    exit;
}

$products=$_POST['product'];
$disvalue=$_POST['disvalue'];
$hiddenID=$_POST['hiddenID'];

$insert="INSERT INTO `tbl_customer_product_special`
(`discountprice`, `status`, `insertdatetime`, `updateuser`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`)
VALUES ('$disvalue', '1', '$updatedatetime', '$userID', '$hiddenID', '$products')";

if($conn->query($insert)==true){
    header("Location:../customer.php?action=4");
}
else{
    header("Location:../customer.php?action=5");
}
?>