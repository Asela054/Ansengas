<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_area_target` WHERE `idtbl_area_target`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_area_target'];
$obj->month=date("Y-m", strtotime($row['month']));
$obj->targettank=$row['targettank'];
$obj->area=$row['tbl_area_idtbl_area'];
$obj->product=$row['tbl_product_idtbl_product'];

echo json_encode($obj);
?>