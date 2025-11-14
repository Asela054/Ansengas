<?php 
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location:index.php");
    exit;
}

require_once('../connection/db.php');
$userID = $_SESSION['userid'];
$updatedatetime = date('Y-m-d h:i:s');

if (!isset($_POST['product'], $_POST['disvalue'], $_POST['hiddenID'])) {
    header("Location:../customer.php?action=5");
    exit;
}

$products = $_POST['product'];
$disvalue = $_POST['disvalue'];
$hiddenID = $_POST['hiddenID'];

$duplicate = false;
$success = false;

$check_query = "SELECT * FROM `tbl_customer_product_special` WHERE `tbl_customer_idtbl_customer`='$hiddenID' AND `tbl_product_idtbl_product`='$products'";
$result = $conn->query($check_query);

if ($result->num_rows == 0) {
    $insert = "INSERT INTO `tbl_customer_product_special`(`discountprice`, `status`, `insertdatetime`, `updateuser`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`) VALUES ('$disvalue', '1', '$updatedatetime', '$userID', '$hiddenID', '$products')";
    
    if ($conn->query($insert) === true) {
        $success = true;
    }
} else {
    $duplicate = true;
}

if ($duplicate) {
    $action = 7;
} elseif ($success) {
    $action = 4; // Success
} else {
    $action = 5; // Error
}

header("Location:../customer.php?action=$action");
exit;
?>