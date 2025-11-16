<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit;
}

require_once('../connection/db.php');

$userID = $_SESSION['userid'];
$updatedatetime = date('Y-m-d H:i:s');

if (empty($_POST['product']) || empty($_POST['disvalue']) || empty($_POST['hiddenID'])) {
    echo "error_missing_fields";
    exit;
}

$products = $_POST['product']; // array
$disvalue = $_POST['disvalue'];
$hiddenID = $_POST['hiddenID'];

$success = true;

foreach ($products as $productID) {
    $productID = $conn->real_escape_string($productID);
    $sql = "INSERT INTO `tbl_customer_product_special`
        (`discountprice`, `status`, `insertdatetime`, `updateuser`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`)
        VALUES ('$disvalue', '1', '$updatedatetime', '$userID', '$hiddenID', '$productID')";
    
    if (!$conn->query($sql)) {
        $success = false;
        break;
    }
}

if ($success) {
    echo "success";
} else {
    echo "error";
}
?>
