<?php
require_once('dbConnect.php');

$driverID = $_POST["refId"];

// $sql = "SELECT `tbl_employee_target`.`month`, `tbl_employee_target`.`targettank`, `tbl_employee_target`.`targetcomplete`, `tbl_product`.`product_name` FROM `tbl_employee_target` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_employee_target`.`tbl_employee_idtbl_employee` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_employee_target`.`tbl_product_idtbl_product` WHERE `tbl_employee`.`useraccount_id`='$driverID' AND YEAR(`tbl_employee_target`.`month`) = YEAR(CURRENT_DATE()) AND MONTH(`tbl_employee_target`.`month`) = MONTH(CURRENT_DATE())";
$sql="SELECT * FROM (SELECT `tbl_employee_target`.`month`, `tbl_employee_target`.`targettank`, `tbl_product`.`idtbl_product`, `tbl_product`.`product_name` FROM `tbl_employee_target` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_employee_target`.`tbl_employee_idtbl_employee` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_employee_target`.`tbl_product_idtbl_product` WHERE `tbl_employee`.`useraccount_id`='$driverID' AND YEAR(`tbl_employee_target`.`month`) = YEAR(CURRENT_DATE()) AND MONTH(`tbl_employee_target`.`month`) = MONTH(CURRENT_DATE())) AS `dmain` LEFT JOIN (SELECT SUM(`newqty`+`refillqty`+`emptyqty`+`trustqty`) AS `targetcomplete`, `tbl_invoice_detail`.`tbl_product_idtbl_product` FROM `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_vehicle_load`.`driverid` WHERE `tbl_invoice_detail`.`status`=1 AND `tbl_invoice`.`status`=1 AND YEAR(`tbl_invoice`.`date`) = YEAR(CURRENT_DATE()) AND MONTH(`tbl_invoice`.`date`) = MONTH(CURRENT_DATE()) AND `tbl_employee`.`useraccount_id`='$driverID' GROUP BY `tbl_invoice_detail`.`tbl_product_idtbl_product`) AS `dsub` ON `dsub`.`tbl_product_idtbl_product`=`dmain`.`idtbl_product`";
$res = mysqli_query($con, $sql);
$result = array();

while ($row = mysqli_fetch_array($res)) {
    array_push($result, array("target" => $row['targettank'], "complete" => $row['targetcomplete'], "product_name" => $row['product_name']));
}

print(json_encode($result));
mysqli_close($con);
?>
