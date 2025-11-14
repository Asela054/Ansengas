<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit;
}

require_once('../connection/db.php');

$userID = $_SESSION['userid'];
$updatedatetime = date('Y-m-d H:i:s');

// Validate required POST data
if (!isset($_POST['product'], $_POST['disvalue'], $_POST['hiddenID'])) {
    header("Location: ../customer.php?action=error");
    exit;
}

$products = $_POST['product'];
$disvalue = $_POST['disvalue'];
$hiddenID = $_POST['hiddenID'];

// Prepare and execute the insert query
$insert = "INSERT INTO `tbl_customer_product_special`
(`discountprice`, `status`, `insertdatetime`, `updateuser`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`)
VALUES ('$disvalue', '1', '$updatedatetime', '$userID', '$hiddenID', '$products')";

if ($conn->query($insert) === TRUE) {
    // Redirect if insert successful
    header("Location: ../customer.php?action=5");
} else {
    // Redirect with error if query fails
    header("Location: ../customer.php?action=error");
}
exit;
?>
