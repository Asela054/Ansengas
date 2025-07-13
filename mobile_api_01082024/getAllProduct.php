<?php

require_once('dbConnect.php');

$refId="3";//$_POST['refId'];
$sqlvehcleload="SELECT * FROM `tbl_vehicle_load` WHERE `refid`='$refId' AND `approvestatus`='1' AND `unloadstatus`='0' AND `status`='1' AND `date`=DATE(Now())";
$resultvehcleload = mysqli_query($con, $sqlvehcleload);
$rowvehcleload = mysqli_fetch_row($resultvehcleload);

$idload = $rowvehcleload[0];

$sql="SELECT `tbl_product`.`idtbl_product`, `tbl_product`.`product_code`,`tbl_product`.`product_name`, `tbl_product`.`size`,`tbl_product`.`unitprice`,`tbl_product`.`refillprice`,`tbl_product`.`tbl_product_category_idtbl_product_category`,`tbl_vehicle_load_detail`.`qty` FROM `tbl_product` INNER JOIN `tbl_vehicle_load_detail` ON `tbl_product`.`idtbl_product`=`tbl_vehicle_load_detail`.`tbl_product_idtbl_product` WHERE `tbl_product`.`status`='1' AND `tbl_vehicle_load_detail`.`tbl_vehicle_load_idtbl_vehicle_load`='$idload' ORDER BY `tbl_product`.`orderlevel` ASC";
$res = mysqli_query($con, $sql);
$result = array();

while ($row = mysqli_fetch_array($res)) {
    array_push($result, array( "id" => $row['idtbl_product'],"product_code" => $row['product_code'],"product_name" => $row['product_name'],"size" => $row['size'],"unitprice"=>$row['unitprice'],"refillprice"=>$row['refillprice'], "category" => $row['tbl_product_category_idtbl_product_category'],"qty"=>$row['qty']));
}

print(json_encode($result));
mysqli_close($con);

?>