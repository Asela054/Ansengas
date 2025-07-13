<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_advanced_payment` WHERE `idtbl_advanced_payment`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_advanced_payment'];
$obj->date=$row['date'];
$obj->invoice=$row['invoice_id'];
$obj->payment=$row['payment_amount'];
$obj->customer=$row['tbl_customer_idtbl_customer'];

echo json_encode($obj);
?>