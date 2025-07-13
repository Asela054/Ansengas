<?php

require_once('dbConnect.php');

$productID = $_POST['productID'];
$loadID = $_POST['vehicleloadID'];

$sqlavaqty = "SELECT `qty`,`emptyqty` FROM `tbl_vehicle_load_detail` WHERE `status` = 1 AND `tbl_product_idtbl_product` = '$productID' AND `tbl_vehicle_load_idtbl_vehicle_load` = '$loadID'";
$resultavaqty = $con->query($sqlavaqty);
$result = array();

while ($rowavaqty = mysqli_fetch_array($resultavaqty)) {
    array_push($result, array( "qty" => $rowavaqty['qty'], "emptyqty" => $rowavaqty['emptyqty']));
}

$sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
$resultvat = $con->query($sqlvat);
$result2 = array();

while ($rowvat = mysqli_fetch_array($resultvat)) {
    array_push($result2, array( "id" => $rowvat['idtbl_vat_info'], "vat_amount" => $rowvat['vat']));
}

$obj = new stdClass();
$obj->avaqty = $result[0]['qty'];
$obj->emptyqty = $result[0]['emptyqty'];
$obj->vatamount = $result2[0]['vat_amount'];

print(json_encode($obj));

mysqli_close($con);

?>
