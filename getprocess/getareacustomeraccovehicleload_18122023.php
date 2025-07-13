<?php 
require_once('../connection/db.php');

$vehicleloadID=$_POST['vehicleloadID'];

$sql="SELECT `idtbl_area`, `area` FROM `tbl_area` WHERE `idtbl_area` IN (SELECT `tbl_area_idtbl_area` FROM `tbl_vehicle_load` WHERE `idtbl_vehicle_load`='$vehicleloadID' AND `status`=1) AND `status`=1";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$areaID=$row['idtbl_area'];

$arraylist=array();
$obj=new stdClass();
$obj->areaid=$areaID;
$obj->area=$row['area'];

array_push($arraylist, $obj);

$sqlcus="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1 AND `tbl_area_idtbl_area`='$areaID' ORDER BY `name` ASC";
$resultcus=$conn->query($sqlcus);

$arraycuslist=array();
while($rowcus=$resultcus->fetch_assoc()){
    $objcus=new stdClass();
    $objcus->customerID=$rowcus['idtbl_customer'];
    $objcus->customer=$rowcus['name'];
    
    array_push($arraycuslist, $objcus);
}

$objmain=new stdClass();
$objmain->arealist=$arraylist;
$objmain->cuslist=$arraycuslist;

echo json_encode($objmain);
?>