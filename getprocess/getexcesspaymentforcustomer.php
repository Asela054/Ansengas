<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT idtbl_invoice_excess_payment, SUM(excess_amount) AS excess_amount FROM `tbl_invoice_excess_payment` WHERE `tbl_customer_idtbl_customer`='$record' AND `paystatus`=0 AND `status`=1";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->amount=$row['excess_amount'];

echo json_encode($obj);
?>