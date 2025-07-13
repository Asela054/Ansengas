<?php 
require_once('../connection/db.php');

$areaID=$_POST['areaID'];
$productID=$_POST['productID'];
$customerID=$_POST['customerID'];
$vehicleloadID=$_POST['vehicleloadID'];

$sqlprice="SELECT awp.`newsaleprice`, awp.`refillsaleprice`, awp.`emptysaleprice`, awp.`discountedprice` FROM `tbl_areawise_product` awp JOIN `tbl_main_area` ma ON awp.`tbl_main_area_idtbl_main_area` = ma.`idtbl_main_area` JOIN `tbl_area` sa ON awp.`tbl_main_area_idtbl_main_area` = sa.`tbl_main_area_idtbl_main_area` WHERE awp.`tbl_product_idtbl_product` = '$productID' AND sa.`idtbl_area` = '$areaID' AND awp.`status` = 1";
$resultprice=$conn->query($sqlprice);
$rowprice=$resultprice->fetch_assoc();

// $sqlsaleprice="SELECT `newsaleprice`, `refillsaleprice` FROM `tbl_customer_product` WHERE `tbl_product_idtbl_product`='$productID' AND `tbl_customer_idtbl_customer`='$customerID' AND `status`=1";
// $resultsaleprice=$conn->query($sqlsaleprice);
// $rowsaleprice=$resultsaleprice->fetch_assoc();

$sqlavaqty="SELECT `qty` FROM `tbl_vehicle_load_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$productID' AND `tbl_vehicle_load_idtbl_vehicle_load`='$vehicleloadID'";
$resultavaqty=$conn->query($sqlavaqty);
$rowavaqty=$resultavaqty->fetch_assoc();

$sqlbufferqty="SELECT `fullqty` FROM `tbl_customer_stock` WHERE `tbl_customer_idtbl_customer`='$customerID' AND `tbl_product_idtbl_product`='$productID' AND `status`=1";
$resultbufferqty=$conn->query($sqlbufferqty);
$rowbufferqty=$resultbufferqty->fetch_assoc();

$obj=new stdClass();
$obj->newsaleprice=$rowprice['newsaleprice'];
$obj->refillsaleprice=$rowprice['refillsaleprice'];
$obj->emptysaleprice=$rowprice['emptysaleprice'];
$obj->discountedprice=$rowprice['discountedprice'];
$obj->avaqty=$rowavaqty['qty'];
$obj->bufferqty=$rowbufferqty['fullqty'];

echo json_encode($obj);