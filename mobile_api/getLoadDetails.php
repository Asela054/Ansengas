<?php

require_once('dbConnect.php');

$loadID = $_POST['vehicleloadID'];

$sqlavaqty = "SELECT `tbl_vehicle_load_detail`.`qty`,`tbl_vehicle_load_detail`.`emptyqty`,`tbl_vehicle_load_detail`.`loadqty`,`tbl_vehicle_load_detail`.`tbl_product_idtbl_product`,`tbl_product`.`product_name`,`tbl_product`.`tbl_product_category_idtbl_product_category` FROM `tbl_vehicle_load_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_vehicle_load_detail`.`tbl_product_idtbl_product` WHERE `tbl_vehicle_load_detail`.`status` = 1 AND `tbl_vehicle_load_detail`.`tbl_vehicle_load_idtbl_vehicle_load` = '$loadID'";
$resultavaqty = $con->query($sqlavaqty);
$products = array();

while ($rowavaqty = mysqli_fetch_array($resultavaqty)) {
    $product = array(
        "id" => $rowavaqty['tbl_product_idtbl_product'],
        "productname" => $rowavaqty['product_name'],
        "categoryid" => $rowavaqty['tbl_product_category_idtbl_product_category'],
        "qty" => $rowavaqty['qty'],
        "emptyqty" => $rowavaqty['emptyqty'],
        "loadqty" => $rowavaqty['loadqty']
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
