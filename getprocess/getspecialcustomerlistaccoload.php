<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];
$datalistarray=array();

$sql="SELECT `tbl_customer`.`idtbl_customer`, `tbl_customer`.`name`, `tbl_vehicle_load`.`date` FROM `tbl_customer_special_route` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_customer_special_route`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_customer_special_route`.`tbl_vehicle_load_idtbl_vehicle_load` WHERE `tbl_customer_special_route`.`tbl_vehicle_load_idtbl_vehicle_load`='$record'";
$result=$conn->query($sql);
while($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->idtbl_customer=$row['idtbl_customer'];
    $obj->name=$row['name'];
    $obj->date=$row['date'];

    array_push($datalistarray, $obj);
}
echo json_encode($datalistarray);
?>