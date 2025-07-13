<?php 
require_once('../connection/db.php');

$vehicleloadID=$_POST['vehicleloadID'];
$selectedStatus=$_POST['selectedStatus'];

$areaID = null;

if ($selectedStatus == 1) {
    $sql = "SELECT `idtbl_area`, `area` FROM `tbl_area` WHERE `status`=1";
    $result = $conn->query($sql);
} else {
    $sql = "SELECT `idtbl_area`, `area` FROM `tbl_area` WHERE `idtbl_area` IN (SELECT `tbl_area_idtbl_area` FROM `tbl_vehicle_load` WHERE `idtbl_vehicle_load`='$vehicleloadID' AND `status`=1) AND `status`=1";
    $result = $conn->query($sql);
}

$arraylist = array();

while ($row = $result->fetch_assoc()) {
    $obj = new stdClass();
    $obj->areaid = $row['idtbl_area'];
    $obj->area = $row['area'];
    $areaID = $row['idtbl_area'];
    array_push($arraylist, $obj);
}

$sqlcus = "SELECT * FROM (SELECT `tbl_customer`.`idtbl_customer`, `tbl_customer`.`name`, `tbl_customer`.`type` FROM `tbl_customer` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_customer`.`tbl_area_idtbl_area` WHERE `tbl_customer`.`status`=1 AND `tbl_area`.`idtbl_area`='$areaID' UNION ALL SELECT `tbl_customer`.`idtbl_customer`, `tbl_customer`.`name`, `tbl_customer`.`type` FROM `tbl_customer` LEFT JOIN `tbl_customer_special_route` ON `tbl_customer_special_route`.`tbl_customer_idtbl_customer`=`tbl_customer`.`idtbl_customer` WHERE `tbl_customer`.`status`=1 AND `tbl_customer_special_route`.`tbl_vehicle_load_idtbl_vehicle_load`='$vehicleloadID' UNION ALL SELECT `tbl_customer`.`idtbl_customer`, `tbl_customer`.`name`, `tbl_customer`.`type` FROM `tbl_customer` LEFT JOIN `tbl_customer_other_route` ON `tbl_customer_other_route`.`tbl_customer_idtbl_customer`=`tbl_customer`.`idtbl_customer` WHERE `tbl_customer`.`status`=1 AND `tbl_customer_other_route`.`tbl_area_idtbl_area`='$areaID') AS combined_results ORDER BY name ASC";
$resultcus = $conn->query($sqlcus);

$arraycuslist=array();
while($rowcus=$resultcus->fetch_assoc()){
    $objcus=new stdClass();
    $objcus->customerID=$rowcus['idtbl_customer'];
    $objcus->customer=$rowcus['name'];
    $objcus->customerType=$rowcus['type'];
    
    array_push($arraycuslist, $objcus);
}

// $sqlcusspecial = "";
// $resultcusspecial = $conn->query($sqlcusspecial);
// while($rowcusspecial=$resultcusspecial->fetch_assoc()){
//     $objcus=new stdClass();
//     $objcus->customerID=$rowcusspecial['idtbl_customer'];
//     $objcus->customer=$rowcusspecial['name'];
//     $objcus->customerType=$rowcusspecial['type'];
    
//     array_push($arraycuslist, $objcus);
// }

$objmain=new stdClass();
$objmain->arealist=$arraylist;
$objmain->cuslist=$arraycuslist;

echo json_encode($objmain);
?>