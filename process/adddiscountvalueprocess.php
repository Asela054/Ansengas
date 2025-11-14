<?php 
session_start();
if (!isset($_SESSION['userid'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

require_once('../connection/db.php');
$userID = $_SESSION['userid'];
$updatedatetime = date('Y-m-d H:i:s');

if (empty($_POST['product']) || empty($_POST['disvalue']) || empty($_POST['hiddenID'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing fields']);
    exit;
}

$products = $_POST['product'];
$disvalue = $_POST['disvalue'];
$hiddenID = $_POST['hiddenID'];

$insert = "INSERT INTO `tbl_customer_product_special`
(`discountprice`, `status`, `insertdatetime`, `updateuser`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`)
VALUES ('$disvalue', '1', '$updatedatetime', '$userID', '$hiddenID', '$products')";

if ($conn->query($insert) === TRUE) {
    echo json_encode(['status' => 'success', 'message' => 'Discount added successfully!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
}
exit;
?>
