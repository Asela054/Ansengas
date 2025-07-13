<?php

require_once('dbConnect.php');

$loadID = $_POST['vehicleloadID'];

$sqlavaqty = "SELECT `qty`,`emptyqty`,`tbl_product_idtbl_product` FROM `tbl_vehicle_load_detail` WHERE `status` = 1 AND `tbl_vehicle_load_idtbl_vehicle_load` = '$loadID'";
$resultavaqty = $con->query($sqlavaqty);
$products = array();

while ($rowavaqty = mysqli_fetch_array($resultavaqty)) {
    $product = array(
        "id" => $rowavaqty['tbl_product_idtbl_product'],
        "qty" => $rowavaqty['qty'],
        "emptyqty" => $rowavaqty['emptyqty']
    );
    array_push($products, $product);
}

$sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
$resultvat = $con->query($sqlvat);
$result2 = array();

while ($rowvat = mysqli_fetch_array($resultvat)) {
    $result2["vatamount"] = $rowvat['vat'];
}

$sqlloadinfo = "SELECT `veiwallcustomerstatus` FROM `tbl_vehicle_load` WHERE `idtbl_vehicle_load`='$loadID'";
$resultloadinfo = $con->query($sqlloadinfo);
$loadarray = array();

while ($rowloadinfo = mysqli_fetch_array($resultloadinfo)) {
    $loadarray["allowallcustomer"] = $rowloadinfo['veiwallcustomerstatus'];
}

$output = array(
    "products" => $products,
    "vatamount" => $result2["vatamount"],
    "loadinfo" => $loadarray
);

print(json_encode($output));

mysqli_close($con);

?>