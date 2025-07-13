<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_vehicle_target` WHERE `idtbl_vehicle_target`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_vehicle_target'];
$obj->month=date("Y-m", strtotime($row['month']));
$obj->targettank=$row['targettank'];
$obj->vehicle=$row['tbl_vehicle_idtbl_vehicle'];
$obj->product=$row['tbl_product_idtbl_product'];

echo json_encode($obj);
?>