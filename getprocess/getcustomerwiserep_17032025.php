<?php
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_customerwise_salesrep` WHERE `idtbl_customerwise_salesrep`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_customerwise_salesrep'];
$obj->customer=$row['tbl_customer_idtbl_customer'];
$obj->product=$row['tbl_product_idtbl_product'];
$obj->employee=$row['tbl_employee_idtbl_employee'];

echo json_encode($obj);
?>