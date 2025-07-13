<?php
require_once('../connection/db.php');

$productID = $_POST['productID'];
$loadID = $_POST['vehicleloadID'];

$sqlavaqty = "SELECT `qty` FROM `tbl_vehicle_load_detail` WHERE `status` = 1 AND `tbl_product_idtbl_product` = '$productID' AND `tbl_vehicle_load_idtbl_vehicle_load` = '$loadID'";
$resultavaqty = $conn->query($sqlavaqty);
$rowavaqty = $resultavaqty->fetch_assoc();

$sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
$resultvat = $conn->query($sqlvat);
$rowvat = $resultvat->fetch_assoc();

$obj = new stdClass();
$obj->avaqty = $rowavaqty['qty'];
$obj->vatamount = $rowvat['vat'];

echo json_encode($obj);
?>
