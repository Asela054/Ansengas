<?php
require_once('../connection/db.php');

$productID = $_GET['productId'];  // Use $_GET instead of $_POST

$sqlavaqty = "SELECT `fullqty` FROM `tbl_stock` WHERE `status` = 1 AND `tbl_product_idtbl_product` = '$productID'";
$resultavaqty = $conn->query($sqlavaqty);
$rowavaqty = $resultavaqty->fetch_assoc();

$obj = new stdClass();
$obj->avaqty = $rowavaqty['fullqty'];

echo json_encode($obj);
?>
