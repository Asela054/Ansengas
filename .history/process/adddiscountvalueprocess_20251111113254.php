<?php 
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location:index.php");
    exit;
}

require_once('../connection/db.php');
$userID = $_SESSION['userid'];

$updatedatetime = date('Y-m-d h:i:s');

$products = $_POST['product'];
$disvalue = $_POST['disvalue'];
$hiddenID = $_POST['hiddenID'];

    $insert = "INSERT INTO `tbl_customer_product_special`(`discountprice`, `status`, `insertdatetime`, `insertdatetime`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`) VALUES ('$disvalue','1','$updatedatetime','$userID','$hiddenID','$product','$salesrep')";
    if ($conn->query($insert) !== true) {
        header("Location:../customer.php?action=5");
        exit;
    }


$action = (($recordOption == 1) ? 4 : 6);
header("Location:../customer.php?action=$action");
exit;
?>


