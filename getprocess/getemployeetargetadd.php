<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_employee_target` WHERE `idtbl_employee_target`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_employee_target'];
$obj->month=date("Y-m", strtotime($row['month']));
$obj->targettank=$row['targettank'];
$obj->employee=$row['tbl_employee_idtbl_employee'];
$obj->product=$row['tbl_product_idtbl_product'];

echo json_encode($obj);
?>