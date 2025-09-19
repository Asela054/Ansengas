<?php
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_customer_discount` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_customer_discount`.`tbl_customer_idtbl_customer` WHERE `idtbl_customer_discount`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_customer_discount'];
$obj->customer=$row['idtbl_customer'];
$obj->product=$row['tbl_product_idtbl_product'];
$obj->percent=$row['discount_percent'];
$obj->amount=$row['discount_amount'];
$obj->type=$row['discount_type'];

echo json_encode($obj);
?>