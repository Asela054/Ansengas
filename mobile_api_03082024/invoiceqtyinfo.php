<?php
require_once('dbConnect.php');

$loadID = $_POST['vehicleloadID'];

$sqlavaqty = "SELECT SUM(`tbl_invoice_detail`.`newqty`+`tbl_invoice_detail`.`refillqty`+`tbl_invoice_detail`.`trustqty`) AS `sumqty`, `tbl_product`.`idtbl_product`, `tbl_product`.`product_name` FROM `tbl_invoice_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` WHERE `tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`='$loadID' AND `tbl_invoice`.`status`=1 AND `tbl_invoice_detail`.`status`=1 GROUP BY `tbl_invoice_detail`.`tbl_product_idtbl_product` ORDER BY `tbl_product`.`orderlevel` ASC";
$resultavaqty = $con->query($sqlavaqty);
$products = array();

while ($rowavaqty = mysqli_fetch_array($resultavaqty)) {
    $product = array(
        "id" => $rowavaqty['idtbl_product'],
        "sumqty" => $rowavaqty['sumqty'],
        "product_name" => $rowavaqty['product_name']
    );
    array_push($products, $product);
}

print(json_encode($products));

mysqli_close($con);