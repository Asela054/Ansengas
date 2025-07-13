<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];
$sql="SELECT * FROM `tbl_mainclass` WHERE `idtbl_mainclass`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();


$obj=new stdClass();
$obj->id=$row['idtbl_mainclass'];
$obj->code=$row['code'];
$obj->class=$row['class'];
$obj->accounttype=$row['accounttype'];
$obj->transactiontype=$row['transactiontype'];
echo json_encode($obj);
?>